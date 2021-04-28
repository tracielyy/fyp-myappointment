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
            if ($auth_user != NULL) {
                $_SESSION['user'] = serialize($auth_user); // Store User Data In Session
                //header("Location:debugreceive.php"); // Redirect Upon Success Authenticate
                echo nl2br(PHP_EOL . "Success" . PHP_EOL);

                // Clear Fields
                $loginArr = array(
                    'email' => '',
                    'password' => '',
                );
            } else {
                $err_msg = "Invalid Credentials!";
            }
        }
        ?>

        <!-- HTML Page Design -->
        <div>
            <!-- Navigation -->
            <?php include COMPONENT_PATH . '/navbar.php' ?>

            <!-- Login Card -->

            <!-- After the "Login" button -->
            <!-- ("Register Now") & ("Forgot your password?") [Hyperlink(s)] -->

            <!-- Error Message Display -->
            <?php echo $err_msg; ?>

        </div>

    </body>
</html>