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
        // Used to store correct data
        $resetArr = array(
            'email' => '',
        );

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
            } else if (!preg_match($email_pattern, $resetArr['email'])) {
                // Store Some Error Message
            } else {
                $validArr['email'] = True; // Pass Validation
            }

            /* ------------ End Validation ------------ */

            // -- Make Sure It Is A Valid Patient/Medical Personnel (Can Admin Reset Password???)
            $user_exist = Account_User::check_user_exist($resetArr['email']);

            // -- Invoke Email Send To User To Reset Password
            if ($user_exist) {
                $to = $resetArr['email'];
                // Need To Generate Hash For URL (NOT IMPLEMENTED YET)
                $subject = "FYP-21-S2-24: Password Reset";
                $message = "Click on the link to reset password";
                $mail = new Email($to, $subject, $message);
                $mail->send_mail();
            } else {
                echo "This email does not exist";
            }
        }
        ?>
        <!-- Ask For The Email To Reset -->
        <!-- Reset Form -->
        <form method="post"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <p>Enter Email Address To Reset Password</p>
            <input type="email" name="email" required placeholder="Email"  value="<?php echo $resetArr['email']; ?>"/>
            <button type="submit" name="resetpassword" value="reset">Reset</button>
        </form>

    </body>
</html>