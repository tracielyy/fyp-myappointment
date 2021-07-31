<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once EMAIL_MOD . '/Email.php';
require_once UTIL_MOD . '/StringUtils.php';
require_once UTIL_MOD . '/Regex.php';
require_once AUTH_MOD . '/Authentication.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Normal_User.php';
require_once SECURE_MOD . '/Security.php';

/*
 * 
 *  LOGIN (PATIENT)
 * 
 */

if (isset($_SESSION['user'])):
    $user = unserialize((string) $_SESSION['user']);

    // MAKE SURE OBJECT IS AN ACCOUNT_USER
    if ($user instanceof Account_User):

        if ($user instanceof Normal_User):
            header("Location:./../");
        else: # -- Some Other Landing Page
        endif;
    endif;
else:
    ?><!DOCTYPE html>
    <html>
        <head>
            <!-- Title -->
            <title>FYP-21-S2-24</title>

            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

            <!-- Styling -->
            <?php include TEMPLATES_PATH . '/bootstrap.php'; ?>
            <link rel="stylesheet" href="./../css/loginRegister.css">

        </head>
        <body>
            <!-- HTML Page Design -->
            <?php
            // Used to store correct data
            $loginArr = array(
                'email' => '',
                'password' => '',
            );

            // -- Msg Variables
            $msg = "";

            $validArr = array();
            $login_status = array(
                'single_logon' => false,
                'auth' => false
            );

            // -- When Redirect or Load The Page
            if ($_SERVER['REQUEST_METHOD'] == "GET") {
                if (isset($_SESSION["user"])) {
                    echo unserialize($_SESSION["user"]);
                }
            }

            // Upon clicking "Login" Button
            if ($_SERVER["REQUEST_METHOD"] == "POST"):

                /* Load Data to Array */
                foreach ($_POST as $key => $value) :
                    if (isset($loginArr[$key])) :
                        $loginArr[$key] = htmlspecialchars($value);
                        $validArr[$key] = False; // Set All Field Validation Check As False
                    endif;
                endforeach;


                // Possible Validation of Email Before Firestore Query
                /* ------------ Start Validation ------------ */


                // -- Email Validation
                if (empty($loginArr['email'])) :
                // Store Some Error Message
                elseif (!Regex::validate_email($loginArr['email'])):
                // Store Some Error Message
                else :
                    $validArr['email'] = True; // Pass Validation
                endif;

                // Password
                $validArr["password"] = True;

                /* ------------ End Validation ------------ */
                if (!in_array(FALSE, $validArr)):

                    # -- Start Authenticating User (boolean)
                    $login_status['auth'] = Authentication::authenticate_user($loginArr['email'], User_Type::PATIENT, $loginArr['password']);

                    # -- Check If There Is Any "token" generated ---
                    if (!isset($_SESSION['token'])) :

                        // Default Session Token Length
                        $token_length = 15;
                        $_SESSION['token'] = StringUtils::generate_token($token_length);
                    endif;

                    # -- User Authenticated ----
                    if ($login_status['auth']) :

                        # -- Check If There Are Any Other Login Session (Terminate Other Session?)
                        $auth_patient = Patient::retrieve_patient($loginArr['email']);
                        $login_status['single_logon'] = Authentication::check_session($auth_patient->get_session(), session_id(), $_SESSION['token']);

                        # -- Get IP Address ---
                        // whether ip is from share internet
                        if (!empty($_SERVER['HTTP_CLIENT_IP'])):
                            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];

                        //whether ip is from proxy
                        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) :
                            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];

                        //whether ip is from remote address
                        else :
                            $ipaddress = $_SERVER['REMOTE_ADDR'];
                        endif;

                        if ($login_status['single_logon'] /* There Is Only ONE Logon */) :
                            $login_status = Authentication::login($auth_patient->get_email(), session_id(), $_SESSION['token'], $ipaddress); # Error
                            $auth_patient = Patient::retrieve_patient($loginArr['email']); // Reload After Login Session Update
                            $_SESSION['user'] = serialize($auth_patient); // Store User Data In Session
                            header("Location:./../"); // Redirect Upon Success Authenticate
                            # -- Clear Fields
                            $loginArr = array(
                                'email' => '',
                                'password' => '',
                            );
                            $login_status['single_logon'] = true;
                        endif; # -- END CHECK FOR SINGLE LOGON

                    endif; # -- END OF AUTHENTICATION
                endif; # -- END VALIDATION
            endif; # -- END POST REQUEST
            ?>
            <div>
                <!-- Navigation (include_once -> prevent "headers already sent" error) -->
                <?php include_once TEMPLATES_PATH . '/navbar.php' ?>

                <!-- Login Card -->
                <div class="center row m-4">
                    <div class="container col-md-10 col-lg-6 col-xl-4 col-xxl-4">
                        <div class="my-5 col-sm-12">
                            <div class="shadow card p-2 rounded1">
                                <div class="card-body m-1">
                                    <h1 class="card-title pt-2 pb-3">Login</h1>
                                    <div class="px-1" id="login-card">
                                        <!-- Form -->
                                        <form id = "login-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                            <div class="row pb-2">

                                                <div class="col">
                                                    <!-- EMAIL -->
                                                    <input id ="email" type="email" class="form-control" name="email" required
                                                           placeholder="Email" value="<?php echo $loginArr['email']; ?>" />
                                                </div>
                                            </div>
                                            <div class="row pb-2">
                                                <div class="col">
                                                    <!-- PASSWORD -->
                                                    <input id="password" type="password" class="form-control" name="password"
                                                           placeholder="Password"
                                                           value="<?php echo $loginArr['password']; ?>" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <a href="<?php echo FORGOT_PW_WEB; ?>" style="float: right">Forgot password?</a>
                                            </div>
                                            <div class="row pt-2">
                                                <div class="d-grid gap-2 d-lg-block">
                                                    <!-- Login Submission -->
                                                    <button class="btn btn-primary" style="float: right"
                                                            type="submit">Login</button><br />
                                                </div>
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
            </div>
            <?php
            /* ERROR MESSAGES */

            function set_login_err_msg(string $err_msg): void {
                echo "
                <script>
                    if(document.getElementById('login-feedback')){
                        $('#login-card').remove('#login-feedback');
                    }                     

                    var login_feedback = \"<div id='login-feedback' class='alert alert-danger'>{$err_msg}</div>\";
                    $('#login-card').prepend(login_feedback);

                </script>
                ";
            }

            function remove_login_err_message(): void {
                echo "
                <script>
                    $('#login-feedback').remove();
                </script>
                ";
            }

            if ($login_status['auth'] === false):
                set_login_err_msg("Invalid Credentials, please try again");
            elseif ($login_status['single_logon'] === false):
                set_login_err_msg("The account is logged in at another location");
            endif;

            if ($_SERVER["REQUEST_METHOD"] != "POST"):
                remove_login_err_message();
            endif;

        endif; # -- END IF SESSION CHECK
        ?>
    </body>
</html>