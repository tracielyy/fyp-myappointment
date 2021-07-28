<?php
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once USER_MOD . '/Account_User.php';
require_once EMAIL_MOD . '/EmailTemplate.php';
require_once UTIL_MOD . '/Regex.php';
require_once UTIL_MOD . '/StringUtils.php';
/*
 * FORGOT PASSWORD
 */
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Title -->
        <title>FYP-21-S2-24: Password Reset</title>
        <!-- Styling -->
        <?php require TEMPLATES_PATH . '/bootstrap.php' ?>

        <style>
<?php include './../css/loginRegister.css';
?>
        </style>
    </head>

    <body>
        <!-- PHP Script -->
        <?php
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
            $user_exist = Account_User::check_email_exist($resetArr['email']);

            // -- Invoke Email Send To User To Reset Password
            if ($user_exist) {


                // -- Generate Token (Security) 
                $token_length = 25; # Size Not Determined Yet
                $token = StringUtils::generate_token($token_length);

                // -- Send Emaill With Token To User 
                $to = $resetArr['email'];
                EmailTemplate::template_passwordreset($to, $token);

                $msg = "Successfully sent";
                // -- Updating The Token To The Database
                Account_User::request_password_reset($to, $token);
            } else {
                $msg = "This email does not exist";
            }
        }
        ?>
        <!-- Display Message Info -->
        <div><?php echo $msg; ?></div>

        <!-- HTML FRONT END CODE -->

        <div>
            <!-- Navigation -->
            <?php include TEMPLATES_PATH . '/navbar.php' ?>

            <!-- Login Card -->
            <div class="center row m-4">
                <div class="container col-md-10 col-lg-6 col-xl-4 col-xxl-4">
                    <div class="my-5 col-sm-12">
                        <div class="shadow card p-2 rounded1">
                            <div class="card-body m-1">
                                <h1 class="card-title pt-2 pb-3">Password Recovery</h1>
                                <div class="px-1">
                                    <p class="text-muted"> Enter email address to reset password </p>
                                    <!-- Form -->
                                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <div class="row pb-2">
                                            <div class="col-4 d-none d-lg-block">  
                                                <p class="pt-2"> Email: </p>
                                            </div>
                                            <div class="col-lg-8 col-xs-12">
                                                <!-- EMAIL -->
                                                <input type="email" class="form-control" name="email" required
                                                       placeholder="Email" value="<?php echo $resetArr['email']; ?>" />
                                            </div>
                                        </div>
                                        <div class="row pt-2">
                                            <div class="d-grid gap-2 d-lg-block">
                                                <!-- Login Submission -->
                                                <button class="btn btn-primary" style="float: right"
                                                        type="submit"  name="resetpassword" value="reset" >Reset</button><br />
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
        </div>


    </body>

</html>