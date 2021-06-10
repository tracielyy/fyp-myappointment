<!DOCTYPE html>
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once UTILS_PATH . '/Email.php';
require_once UTILS_PATH . '/Regex.php';
require_once UTILS_PATH . '/Time.php';
require_once UTILS_PATH . '/StringUtils.php';
require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';
?>

<html>
    <head>
        <!-- Title -->
        <title>FYP-21-S2-24</title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Styling -->
        <?php include COMPONENTS_PATH . '/bootstrap.php'; ?>
        <link rel="stylesheet" href="./css/loginRegister.css"/> 

    </head>
    <body>


        <?php
        // Used to store correct data
        $loginArr = array(
            'email' => '',
            'password' => '',
        );

        // -- Msg Variables
        $err_msg = "";

        $validArr = array();

        // -- When Redirect or Load The Page
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if (isset($_SESSION["user"])) {
                echo unserialize($_SESSION["user"]);
            }
        }

        // Upon clicking "Login" Button
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            /* Load Data to Array */
            foreach ($_POST as $key => $value) {
                if (isset($loginArr[$key])) {
                    $loginArr[$key] = htmlspecialchars($value);
                    $validArr[$key] = False; // Set All Field Validation Check As False
                }
            }

            // Possible Validation of Email Before Firestore Query
            /* ------------ Start Validation ------------ */


            // -- Email Validation
            if (empty($loginArr['email'])) {
                // Store Some Error Message
            } else if (!Regex::validate_email($loginArr['email'])) {
                // Store Some Error Message
            } else {
                $validArr['email'] = True; // Pass Validation
            }

            // Password
            $validArr["password"] = True;

            /* ------------ End Validation ------------ */
            if (!in_array(FALSE, $validArr)) {

                # -- Start Authenticating User (boolean)
                $auth = AccountUserFunctions::authenticate_user($loginArr);

                # -- Check If There Is Any "token" generated ---
                if (!isset($_SESSION['token'])) {

                    // Default Session Token Length
                    $token_length = 15;
                    $_SESSION['token'] = StringUtils::generate_token($token_length);
                }

                # -- User Authenticated ----
                if ($auth) {

                    # -- Check If There Are Any Other Login Session (Terminate Other Session?)
                    $auth_user = AccountUserFunctions::load_user_data($loginArr);
                    $session_logon_allowed = AccountUserFunctions::check_session($auth_user->get_session(), session_id(), $_SESSION['token']);

                    # -- Get IP Address ---
                    // whether ip is from share internet
                    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
                    }
                    //whether ip is from proxy
                    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    }
                    //whether ip is from remote address
                    else {
                        $ipaddress = $_SERVER['REMOTE_ADDR'];
                    }

                    if ($session_logon_allowed) {

                        $login_status = AccountUserFunctions::login($auth_user->get_email(), session_id(), $_SESSION['token'], $ipaddress); # Error
                        $auth_user = AccountUserFunctions::load_user_data($loginArr); // Reload After Login Session Update
                        //header("Location:debugreceive.php"); // Redirect Upon Success Authenticate
                        # -- Clear Fields
                        $loginArr = array(
                            'email' => '',
                            'password' => '',
                        );
                    } else {
                        $msg = "Account is logged in at another location";
                    }
                } else {
                    $msg = "Invalid Credentials!";
                }
            } else {
                // When Validation Fails
            }
        }
        ?>
        <!-- HTML Page Design -->
        <div>
            <!-- Navigation -->
            <?php include COMPONENTS_PATH . '/navbar.php' ?>

            <!-- Login Card -->
            <div class="center row m-4">
                <div class="container col-md-6 col-lg-4">
                    <div class="my-5 col-sm-12">
                        <div class="shadow card p-2 rounded1">
                            <div class="card-body m-1">
                                <h1 class="card-title px-1 py-3">Login</h1>
                                <div class="px-1">
                                    <!-- Form -->
                                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <div class="row pb-2">
                                            <div class="col">
                                                Email:
                                            </div>
                                            <div class="col">
                                                <!-- EMAIL -->
                                                <input type="email" class="form-control" name="email" required
                                                    placeholder="Email" value="<?php echo $loginArr['email']; ?>" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">Password: </div>
                                            <div class="col">
                                                <!-- PASSWORD -->
                                                <input type="password" class="form-control" name="password"
                                                    placeholder="Password"
                                                    value="<?php echo $loginArr['password']; ?>" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col ">
                                            </div>
                                            <!-- Login Submission -->
                                            <div class="col py-3"><button class="btn btn-primary" type="submit"
                                                    style="float: right" ;>Login</button><br /></div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                            <!-- Should Insert ("Already have an account? Sign In")  [Hyperlink to login.php] -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- After the "Login" button -->
            <!-- ("Register Now") & ("Forgot your password?") [Hyperlink(s)] -->

            <!-- Error Message Display -->
            <?php echo $err_msg; ?>

        </div>

    </body>
</html>