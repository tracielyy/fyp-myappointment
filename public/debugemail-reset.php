<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
/* Load Config File */
require_once '../resources/config.php';
?>
<html>
    <head>
        <!-- Title -->
        <title>FYP-21-S2-24: Password Reset</title>
        <!-- Styling -->
        <?php require COMPONENT_PATH . '/bootstrap.php' ?>

    </head>
    <body>
        <!-- PHP Script -->
        <?php
        require_once ENTITIES_PATH . '/Account_User.php';
        require_once UTILS_PATH . '/Email.php';
        require_once UTILS_PATH . '/Regex.php';


        // Used to store correct data
        $resetArr = array(
            'email' => '',
        );

        // -- Misc Variables
        $msg = "";

        // -- Regex
        $email_pattern = '/^[a-zA-Z0-9]+(.[_a-z0-9-]+)(?!.*[~@\%\/\\\&\?\,\'\;\:\!\-]{2}).*@[a-z0-9-]+(.[a-z0-9-]+)(.[a-z]{2,3})$/';
        // Upon clicking "Login" Button
        if ($_SERVER["REQUEST_METHOD"] == "POST") {



            /* Load Data to Array */
            foreach ($_POST as $key => $value) {
                if (isset($resetArr[$key])) {
                    $resetArr[$key] = htmlspecialchars($value); // Containing Any Values To Reset Password
                    $validArr[$key] = False; // Set All Field Validation Check As False
                }
            }

            // Possible Validation of Email Before Firestore Query
            /* ------------ Start Validation ------------ */

            // -- Email Validation
            if (empty($resetArr['email'])) {
                // Store Some Error Message
            } else if (!Regex::validate_email($resetArr['email'])) {
                // Store Some Error Message
            } else {
                $validArr['email'] = True; // Pass Validation
            }

            /* ------------ End Validation ------------ */

            // -- Make Sure It Is A Valid Patient/Medical Personnel (Can Admin Reset Password???)
            $user_exist = Account_User::check_user_exist($resetArr['email']);

            // -- Invoke Email Send To User To Reset Password
            if ($user_exist) {
                // -- Recipient
                $to = $resetArr['email'];
                $to_name = Account_User::retrieve_user_fullname($resetArr['email']);

                // -- Email Subject
                $subject = "FYP-21-S2-24: Password Reset";

                // -- Generate Token (Security) 
                $token_length = 25; # Size Not Determined Yet
                $token = Account_User::generate_token($token_length);
                # -- Token Expiry Date Needs To Be Set -- #
                // -- Password Reset Link With Token (To Be Added To The Email Message)
                // <link>?token=<passwordtoken>&email=<email>
                $unique_password_url = "http://localhost/MyAppointment/public/debugpasswordreset.php?token={$token}&email={$resetArr["email"]}";
                $request_another_url = "http://localhost/MyAppointment/public/debugpasswordreset.php";


                // -- Clickable Links
                $user_email = "<a href=mailto:{$resetArr["email"]}>{$resetArr["email"]}</a>";
                $reset_password = "<a href={$unique_password_url} style='color:red; text-decoration:none;'>here</a>";
                $reset_password_url = "<a href={$unique_password_url}>{$unique_password_url}</a>";
                $request_another = "<a href={$request_another_url} style='color:teal;'>request another</a>";

                // -- Miscellaneous
                $break = "<br/><br/>";
                $sign_off = "Sincerely, <br/>FYP-21-S2-24 Team";

                // -- Message
                $message = "<span style='color:black;'>Hi {$to_name},{$break}";
                $message .= "We have received a request to reset the password for the MyAppointment account associated with {$user_email}. {$break}";
                $message .= "You can reset your password by clicking {$reset_password} or copy the link below in your browser:<br/>";
                $message .= "{$reset_password_url}{$break}";
                $message .= "If you did make this request, please disregard this email. ";
                $message .= "Please note that your password will not change unless you click the link above and create a new one. ";
                $message .= "This link will expire in one day. If your link has expired, you can always {$request_another}. {$break}";
                $message .= "If you have requested multiple reset emails, please make sure you click the link inside the most recent email.{$break}";
                $message .= "{$sign_off}</span>";

                // -- Create New Email Object
                $mail = new Email();
                $mail->addAddress($to);

                // -- Content
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;
                $mail->AltBody = $message;
                $mail->send();
                $msg = "Successfully sent";
                Account_User::request_password_reset($to, $token);
            } else {
                $msg = "This email does not exist";
            }
        }
        ?>
        <!-- Display Message Info -->
        <div><?php echo $msg; ?></div>
        <!-- Reset Form (Ask For Email To Reset) -->
        <form method="post"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <p>Enter Email Address To Reset Password</p>
            <input type="email" name="email" required placeholder="Email"  value="<?php echo $resetArr['email']; ?>"/>
            <button type="submit" name="resetpassword" value="reset">Reset</button>
        </form>

    </body>
</html>