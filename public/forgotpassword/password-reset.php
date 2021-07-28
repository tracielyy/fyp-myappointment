<!DOCTYPE html>
<html lang="en">
    <?php
    /* Load Config File */
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
    require VENDOR_PATH . '/autoload.php';

// -- Import Project Classes -- //
    require_once USER_MOD . '/Account_User.php';
    require_once EMAIL_MOD . '/EmailTemplate.php';
    require_once UTIL_MOD . '/Regex.php';
    require_once TIME_MOD . '/Time.php';
    require_once SECURE_MOD . '/Security.php';

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
            if (Account_User::check_email_exist($email)) {
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

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
        <!-- Title -->
        <title>FYP-21-S2-24: Password Reset</title>
        <!-- Styling -->
        <?php require TEMPLATES_PATH . '/bootstrap.php' ?>
        <link rel="stylesheet" href="./../css/loginRegister.css">

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
            echo "here";
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
            if (!in_array(FALSE, $validArr)) {
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
            ?>
            <div>
                <!-- Navigation -->
                <?php include TEMPLATES_PATH . '/navbar.php' ?>

                <!-- Login Card -->
                <div class="center row m-4">
                    <div class="container col-md-10 col-lg-6 col-xl-4 col-xxl-4">
                        <div class="my-5 col-sm-12">
                            <div class="shadow card p-2 rounded1">
                                <div class="card-body m-1">
                                    <h1 class="card-title pt-2 " style="padding: 0px;margin: 0px;">Password Recovery<h3>
                                            <?php echo $email; ?></h3>
                                    </h1>
                                    <div class="px-1">
                                        <p class="text-muted mt-2"> Enter your new password and confirm it </p>
                                        <!-- Form -->
                                        <form method="post" id="resetpassword"
                                              action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                                            <div class="row pb-2">
                                                <div class="col-4 d-none d-lg-block">
                                                    <p class="pt-2"> New Password: </p>
                                                </div>
                                                <div class="col-lg-8 col-xs-12">
                                                    <!-- NEW PASSWORD -->
                                                    <input type="password" id="password" name="password" class="form-control"
                                                           required placeholder="New Password"
                                                           value="<?php echo $resetArr['password']; ?>" />
                                                </div>
                                            </div>

                                            <div class="row pb-2">
                                                <div class="col-4 d-none d-lg-block">
                                                    <p class="pt-2"> Confirm Password: </p>
                                                </div>
                                                <div class="col-lg-8 col-xs-12">
                                                    <!-- CONFIRM PASSWORD -->
                                                    <input type="password" name="confirmpassword" class="form-control" required
                                                           placeholder="Confirm Password"
                                                           value="<?php echo $resetArr['confirmpassword']; ?>" />
                                                </div>
                                            </div>

                                            <div class="row pt-2">
                                                <div class="d-grid gap-2 d-lg-block">
                                                    <!-- Reset Submission -->
                                                    <button class="btn btn-primary" style="float: right" type="submit"
                                                            name="resetpasswordbttn" value="reset">Reset Password</button><br />
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
            <script>
                $("#resetpassword").validate({
                    rules: {
                        password: {
                            required: true,
                            oneDigit: true,
                            lowerCase: true,
                            upperCase: true,
                            specialChar: true,
                            minlength: 8,
                            maxlength: 32

                        },
                        confirmpassword: {
                            required: true,
                            equalTo: "#password"
                        }
                    },
                    messages: {
                        password: {
                            required: "Please provide a password",
                            minlength: "Password needs to be at least 8 characters",
                            maxlength: "Password exceeded 32 characters limit"
                        },
                        confirmpassword: {
                            required: "Please provide a confirm password",
                            equalTo: "Please enter the same password"
                        }
                    },
                    errorElement: "em",
                    errorPlacement: function (error, element) {
                        // This is the default behavior 
                        error.insertAfter(element);
                        error.addClass("help-block invalid-feedback");
                    },
                    success: function (label, element) {

                        $(element).addClass("is-valid");

                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).addClass("is-invalid").removeClass("is-valid");
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).addClass("is-valid").removeClass("is-invalid");

                    }
                });

                /*----------------------------------------------
                 CLIENT SIDE REGULAR EXPRESSION FOR PASSWORD
                 -----------------------------------------------*/

                $.validator.addMethod("oneDigit", function (value, element) {
                    return this.optional(element) ||
                            /(?=.*[0-9])/
                            .test(value);
                }, "Password needs at least one digit.");

                $.validator.addMethod("lowerCase", function (value, element) {
                    return this.optional(element) ||
                            /(?=.*[a-z])/
                            .test(value);
                }, "Password needs at least one lower case character.");

                $.validator.addMethod("upperCase", function (value, element) {
                    return this.optional(element) ||
                            /(?=.*[A-Z])/
                            .test(value);
                }, "Password needs at least one upper case character.");

                $.validator.addMethod("specialChar", function (value, element) {
                    return this.optional(element) ||
                            /(?=.*[\*\.\!\@\$\%\^\&\(\)\{\}\[\]\:\;\<\>\,\?\/\~\_\+\-\=\|\#])/.test(value);
                }, "Password needs at least one special character. e.g. [!@#$%^&*]");
            </script>

            <?php
        } else {

            # Display Invalid URL
            echo "Invalid URL";
        }
        ?>

    </body>

</html>