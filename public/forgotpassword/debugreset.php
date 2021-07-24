<?php
/* Load Config File */
require_once '../../resources/config.php';
require '../../vendor/autoload.php';

// -- Import Project Classes -- //
require_once USER_MOD . '/Account_User.php';
require_once EMAIL_MOD . '/EmailTemplate.php';
require_once UTIL_MOD . '/Regex.php';
require_once TIME_MOD . '/Time.php';

/*
 * PASSWORD RESET
 */

// -- Misc Variables -- //
$msg = "";
$validURL = false;

# Set Cookie MUST Be Done Before The <html> tag
$url_name = "url";
$url_value = htmlspecialchars($_SERVER['PHP_SELF']);

$email_name = "email";
$email_value = "";
// -- Check If User Is Signed In (When Redirect or Load The Page) -- //
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['token']) && isset($_GET['email'])) {

        # Variable Assignment
        $token = $_GET['token'];
        echo $token;

//        $email = urlencode( $_GET['email']);
//        $email = str_replace("+", "%2B", $email);
//        $email = urldecode($email);

        $email = $_GET['email'];
        $email_value = $email;

        # Only Add `token` & `email` If It Is Present
        $url_value .= "?token={$token}&email={$email}";

        # Cross Check `email` With Google Cloud Firestore
        if (Account_User::check_user_exist($email)) {
            echo "User Exist";

            # Cross Check `token` With Google Cloud Firestore
            $validURL = Account_User::validate_password_token($email, $token);
            if ($validURL) {
                
            }
        } else {
            $msg = "";
        }
        # TBD
    }
}

# Setting Cookies
setcookie($email_name, $email_value, time() + 3600);
setcookie($url_name, $url_value, time() + 3600);
?>
<html>
    <head>
        <!-- Title -->
        <title>FYP-21-S2-24: Password Reset</title>
        <!-- Styling -->
        <?php require TEMPLATES_PATH . '/bootstrap.php' ?>

    </head>
    <body>
        <!-- PHP Script -->
        <?php
        // -- Used to store correct data
        $resetArr = array(
            'password' => '',
            'confirmpassword' => ''
        );

        // -- Validation Array
        $validArr = array();

        // -- When The User Submit Password Reset -- //
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            /* Load Data to Array */
            foreach ($_POST as $key => $value) {

                # If Only If $key Is PREVIOUSLY Set
                if (isset($resetArr[$key])) {
                    $resetArr[$key] = htmlspecialchars($value); // Containing Any Values To Reset Password
                    $validArr[$key] = False; // Set All Field Validation Check As False
                }
            }

            // Possible Validation of Email Before Firestore Query
            /* ------------ Start Validation ------------ */

            // -- Password Validation
            if (empty($resetArr['password'])) {
                // Store Some Error Message
            } else if (!Regex::validate_password($resetArr['password'])) {
                // Store Some Error Message
            } else {
                $validArr['password'] = True; // Pass Validation
            }

            // -- Confirm Password Validation & Checks
            if (empty($resetArr['confirmpassword'])) {
                // Store Some Error Message
            } else if ($resetArr['confirmpassword'] !== $resetArr['password']) {
                $msg = "Password does not match";
            } else {
                $validArr['confirmpassword'] = True; // Pass Validation
            }


            /* ------------ End Validation ------------ */
            if (in_array(FALSE, $validArr)) {
                echo "Password Pass";

                // -- Store The Password To Database -- //
                echo $_COOKIE['email'];
                if (Account_User::change_reset_password($_COOKIE['email'], $resetArr['password'])) {
                    // UPDATE PASSWORD TOKEN
                    Account_User::update_password_token($_COOKIE['email']);
                    // -- Possible Termination Of Other Sessions -- //
                    // -- Need To Email To Inform Password Change -- //
                    $to = $_COOKIE['email'];
                    EmailTemplate::template_passwordchanged($to);


                    echo "Password Changed Successfully";
                } else {
                    echo "Password Changed FAIL";
                }
            } else {
                $location = "Location:{$_COOKIE['url']}";
                header($location);
                echo "Password Fail";
            }
        }
        ?>
        <!-- Display Message Info -->
        <div><?php echo $msg; ?></div>
        <!-- Reset Form (Ask For Email To Reset) -->
        <?php
        # Check If The Given URL Is Valid
        if ($validURL) {

            # Form
            echo "<form method ='post' action='";
            echo htmlspecialchars($_SERVER['PHP_SELF']);
            echo "'>";

            # Title
            echo "<p>Password Reset For {$email}</p>";

            # Password
            echo '<input type="password" name="password" required placeholder="Password"  value="';
            echo $resetArr['password'];
            echo '"/>';

            # Confirm Password
            echo '<input type="password" name="confirmpassword" required placeholder="Confirm Password"  value="';
            echo $resetArr['confirmpassword'];
            echo '"/>';

            # Submit For Reset
            echo '<button type="submit" name="resetpassword" value="reset">Reset</button>';

            echo '</form>';
        } else {

            # Display Invalid URL
            echo "Invalid URL";
        }
        ?>
    </body>
</html>






