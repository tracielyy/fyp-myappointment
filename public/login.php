<!--
   Developed By FYP-21-S2-24
-->
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
?>

<html>
    <head>
        <!-- Title -->
        <title>FYP-21-S2-24</title>
        <!-- Styling -->
        <?php include COMPONENT_PATH . '/bootstrap.php'; ?>
        <link rel="stylesheet" href="./css/loginRegister.css"/> 


    </head>
    <body>
        <!-- Logic & Validation -->
        <?php
        require_once ENTITIES_PATH . '/Account_User.php';
        require_once ENTITIES_PATH . '/Patient.php';

        // Used to store correct data
        $loginArr = array(
            'email' => '',
            'password' => '',
        );

        // -- Error Message
        $err_msg = "";

        // -- Regex
        $email_pattern = '/^[a-zA-Z0-9]+(.[_a-z0-9-]+)(?!.*[~@\%\/\\\&\?\,\'\;\:\!\-]{2}).*@[a-z0-9-]+(.[a-z0-9-]+)(.[a-z]{2,3})$/';

        // -- When Redirect or Load The Page
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            // If the user is logged in
            if (isset($_SESSION["user"])) {
                // Redirect User to home page / patient  page
                //header("Location:'index");
            }
        }



        // Upon clicking "Login" Button
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            /* Load Data to Array */
            foreach ($_POST as $key => $value) {
                if (isset($loginArr[$key])) {
                    $loginArr[$key] = htmlspecialchars($value);
                }
            }

            // Possible Validation of Email Before Firestore Query
            /* ------------ Start Validation ------------ */


            // -- Email Validation
            if (empty($loginArr['email'])) {
                // Store Some Error Message
            } else if (!preg_match($email_pattern, $loginArr['email'])) {
                // Store Some Error Message
            } else {
                $validArr['email'] = True; // Pass Validation
            }

            /* ------------ End Validation ------------ */

            // Start Authenticating User
            $auth_user = Account_User::login($loginArr, session_id());

            // Check If There Are Any Other Login Session (Terminate Other Session?)
            $session_logon_allowed = self::check_token($auth_user->get_session(), session_id());

            // User Authenticated
            if ($auth_user != NULL) {
                // Check Active Session
                $_SESSION['user'] = serialize($auth_user); // Store User Data In Session
                //header("Location:debugreceive.php"); // Redirect Upon Success Authenticate
                echo nl2br(PHP_EOL . "Success" . PHP_EOL);

                // Clear Fields
                $loginArr = array(
                    'email' => '',
                    'password' => '',
                );
            } else {
                $msg = "Invalid Credentials!";
            }
        }
        ?>

        <!-- HTML Page Design -->
        <div>
            <!-- Navigation -->
            <?php include COMPONENT_PATH . '/navbar.php' ?>

            <!-- Login Card -->
        <div class="row m-4" ></div>
        <div class="center container col-md-6 col-lg-4">
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
                                        <input type="email" class="form-control" name="email" required placeholder="Email"  value="<?php echo $loginArr['email']; ?>"/>
                                    </div>
                                </div>
                                <div class="row">
                                <div class="col">Password: </div>
                                    <div class="col">
                                        <!-- PASSWORD -->
                                        <input type="password" class="form-control" name="password" placeholder="Password" value="<?php echo $loginArr['password']; ?>"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col ">
                                    </div>
                                    <!-- Login Submission -->
                                    <div class="col py-3"><button class="btn btn-primary" type="submit" style="float: right";>Login</button><br /></div>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- Should Insert ("Already have an account? Sign In")  [Hyperlink to login.php] -->
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