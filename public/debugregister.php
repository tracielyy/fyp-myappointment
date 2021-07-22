<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require '../vendor/autoload.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once UTIL_MOD . '/Regex.php';
require_once UTIL_MOD . '/StringUtils.php';
require_once EMAIL_MOD . '/EmailTemplate.php';
require_once EMAIL_MOD . '/EmailVerify.php';

require_once SECURE_MOD . '/ValidateIC.php';


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    setcookie("email_verified", "", time() - 3600);
}
// -- SETTING A EXPIRABLE COOKIE (ONLY VIA SECURE PROTOCOL) -- valid for 1hr
setcookie("email_verified", false, time() + 3600, "/", "MyAppointment.tracieqwynn.tech", 1);
//setcookie("email_verified", false, time() + 3600);
//echo (isset($_COOKIE["email_verified"]) && $_COOKIE["email_verified"]) ? "true" : "false";
// Used to store correct data
$registerArr = array(
    'nric' => '',
    'firstname' => '',
    'lastname' => '',
    'contactnumber' => '',
    'address' => '',
    'dob' => '', //Time::date_format_change(Time::get_current_date(), Time::CALENDAR_FORMAT_DEFAULT),
    'gender' => '',
    'email' => '',
    'password' => '',
    'confirmpassword' => ''
);

// -- Storage Array -- //
$patient_register = array(
    'profile' => array(
        'name' => array('firstname' => '', 'lastname' => ''),
        'contactnumber' => '',
        'address' => '',
        'dob' => '',
        'gender' => '',
        'nric' => ''
    ),
    'credentials' => array('email' => '', 'password' => '')
);


// Some Variables
$err_msg = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") :

    /* Load Data to Array */
    foreach ($_POST as $key => $value) {
        if (isset($registerArr[$key])) {
            $registerArr[$key] = htmlspecialchars($value);
            $validArr[$key] = False; // Set All Field Validation Check As False
            $err_msg[$key] = "";
        }
    }
    $validArr['gender'] = False; // Set All Field Validation Check As False

endif;

if (isset($_COOKIE['email_verified'])):
    if ($_COOKIE['email_verified'] == 'true'):
        $_COOKIE['email_verified'] = true;
    elseif ($_COOKIE['email_verified'] == 'false'):
        $_COOKIE['email_verified'] = false;
    endif;

endif;




// Upon clicking "Login" Button 
if ($_SERVER["REQUEST_METHOD"] == "POST") :



    if (isset($_POST['ajax_emailverify'])):
        $otp_requester = $_POST['email'];
        // Generate OTP
        $otp = StringUtils::generate_otp(6);

        // Update OTP In Database
        Account_User::update_email_otp($otp_requester, $otp);

        // Send OTP To OTP Requester
        EmailTemplate::template_emailotp($otp_requester, $otp);
    endif;


    if (isset($_POST['register_patient'])):
        /* ------------ Start Validation ------------ */

        // -- First Name
        if (empty($registerArr['firstname'])) {
            $err_msg['firstname'] = "Required";
        } elseif (!Regex::validate_name($registerArr['firstname'])) {
            $err_msg['firstname'] = "Invalid Firstname";
        } else {
            $validArr['firstname'] = True; // Pass Validation
        }

        // -- Last Name
        if (empty($registerArr['lastname'])) {
            $err_msg['lastname'] = "Required";
        } elseif (!Regex::validate_name($registerArr['lastname'])) {
            $err_msg['lastname'] = "Invalid Lastname";
        } else {
            $validArr['lastname'] = True; // Pass Validation
        }

        // -- Contact Number Validation
        if (empty($registerArr['contactnumber'])) {
            $err_msg['contactnumber'] = "Required";
        } elseif (!Regex::validate_phone($registerArr['contactnumber'])) {
            $err_msg['contactnumber'] = "Incorrect Format";
        } else {
            $validArr['contactnumber'] = True; // Pass Validation
        }

// -- Gender Validation (Just Make Sure Either Male Or Female Is 'Checked')
        if (empty($registerArr['gender'])) {
            // Store Some Error Message
            $err_msg['gender'] = "Not Selected";
        } elseif (!($registerArr['gender'] == 'F' || $registerArr['gender'] == 'M')) {
            // Store Some Error Message
            $err_msg['gender'] = "Invalid";
        } else {
            $validArr['gender'] = True; // Pass Validation
        }

        // -- Date Of Birth (DOB) Validation
        if (empty($registerArr['dob'])) {
            $err_msg['dob'] = "Required";
        } else {
            $validArr['dob'] = True; // Pass Validation
        }


        // -- Address Validation (Unsure Of What Further Validation To Be Done)
        if (empty($registerArr['address'])) {
            $err_msg['address'] = "Required";
        } else {
            $validArr['address'] = True; // Pass Validation
        }


        // VALIDATE NRIC
        $validate_ic = new ValidateIC($registerArr['nric']);
        $valid_nric = $validate_ic->validate_nric();
        if (empty($registerArr['nric'])) {
            $err_msg['nric'] = "Required";
        } elseif (!$valid_nric) {
            $err_msg['nric'] = "Invalid NRIC Format";
        } else {
            $validArr['nric'] = True; // Pass Validation
        }


        // -- Email Validation
        if (empty($registerArr['email'])) {
            // Store Some Error Message
            $err_msg['email'] = "Field Cannot Be Empty";
        } elseif (!Regex::validate_email($registerArr['email'])) {
            // Store Some Error Message
            $err_msg['email'] = "Invalid";
        } elseif (isset($_COOKIE['email_verified']) && $_COOKIE['email_verified'] === true) {
            echo "<script>console.log('{$_COOKIE['email_verified']}');</script>";
            $registerArr['email'] = StringUtils::clean_input($registerArr['email']);
            $validArr['email'] = True; // Pass Validation
        }

// -- Password Validation
        if (empty($registerArr['password'])) {
            // Store Some Error Message
            $err_msg['password'] = "Required";
        } elseif (!Regex::validate_password($registerArr['password'])) {
            // Store Some Error Message
            $err_msg['password'] = "Invalid";
        } else {
            $validArr['password'] = True; // Pass Validation
        }

// -- Confirm Password Validation (Check if it is the same as 'Password')
        if (empty($registerArr['confirmpassword'])) {
            // Store Some Error Message
            $err_msg['confirmpassword'] = "Required";
        } elseif ($registerArr['confirmpassword'] !== $registerArr['password']) {
            // Store Some Error Message
            $err_msg['confirmpassword'] = "Password Does Not Match";
        } else {
            $validArr['confirmpassword'] = True; // Pass Validation
        }




        /* ------------ End Validation ------------ */

// If Valid User Information (After Validation)
        if (!in_array(False, $validArr)) {
// > Check If User Already Exist (Email & Contact Number)
            $exist = Account_User::check_user_exist($registerArr['email']);
            if (!$exist) {

# Change The Date Back To Database Default
                $registerArr['dob'] = Time::date_format_default($registerArr['dob']);

                /* Load To Patient Registration Array */
                foreach ($registerArr as $key => $value) {

# Loading Of Basic Profile Information
                    if (isset($patient_register['profile'][$key])) {
                        $patient_register['profile'][$key] = htmlspecialchars($value);
                    } elseif (isset($patient_register['credentials'][$key])) {
                        $patient_register['credentials'][$key] = htmlspecialchars($value);
                    } elseif (isset($patient_register['profile']['name'][$key])) {
                        $patient_register['profile']['name'][$key] = htmlspecialchars($value);
                    }
                }


// > Salt Generation (?)
// > Need To Encrypt The Password Then Store In Database
                Patient::create_patient($patient_register);  // -- Need To Monitor & Change If Database Info Change -- //
                $patient_created = Account_User::check_user_exist($registerArr['email']);
                if ($patient_created) {
                    # Send Email To Inform Patient
                    EmailTemplate::template_patientregistration($registerArr['email']);
                    
                    # Remove The OTP
                    $email_verify = new EmailVerify($registerArr['email']);
                    $email_verify->remove_db_verify();
                }
// Reset Information
                $registerArr = array(
                    'firstname' => '',
                    'lastname' => '',
                    'contactnumber' => '',
                    'address' => '',
                    'dob' => '',
                    'gender' => '',
                    'email' => '',
                    'password' => '',
                    'confirmpassword' => ''
                );
                header("Location:./debuglogin.php");

// -- Need To Send A Email To Ask Patient To Verify Email -- //
            } else {
                echo "User already exist";
            }
        } else {
// Any Actions Or Displays For Errors
            echo "<div style='color:red;'>Register Fail!</div>";
        }
    endif; # END REGISTER PATIENT
endif; # END POST REQUEST
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register</title>
        <!-- Styling -->
        <link rel="stylesheet" href="./css/loginRegister.css">
        <?php require TEMPLATES_PATH . '/bootstrap.php' ?>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    </head>

    <body>
        <!-- Navigation -->
        <?php
        require TEMPLATES_PATH . '/navbar.php';
        ?>
        <!-- Registration -->
        <div class="row m-4">
            <div class="container center col-md-10 col-lg-6">
                <div class="col-auto">
                    <div class="shadow card p-2 rounded1">
                        <div class="card-body m-2">
                            <h1 class="card-title px-5 py-3">Register</h1>
                            <div class="px-5">
                                <!-- Form -->
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                    <div class="row">
                                        <div class="col d-none d-lg-block">First Name</div>
                                        <div class="col d-none d-lg-block">Last Name</div>
                                    </div>
                                    <div class="row">
                                        <div class="col" id="firstname_container">
                                            <!-- First Name -->
                                            <input id="firstname" class="form-control" type="text" name="firstname"
                                                   placeholder="First Name" value="<?php echo $registerArr['firstname']; ?>" />
                                        </div>
                                        <div class="col" id="lastname_container">
                                            <!-- Last Name -->
                                            <input id="lastname" class="form-control" type="text" name="lastname"
                                                   placeholder="Last Name" value="<?php echo $registerArr['lastname']; ?>" />
                                        </div>
                                    </div><br/>

                                    <div class="row">
                                        <div class="col d-none d-lg-block">Date of Birth</div>
                                        <div class="col d-none d-lg-block">Select Gender:</div>
                                    </div>

                                    <div class="row">
                                        <div class="col" id="dob_container">
                                            <!-- Date Of Birth -->
                                            <input id="dob" class="form-control" type="date" name="dob"
                                                   value="<?php echo htmlspecialchars($registerArr['dob']); ?>" />
                                        </div>

                                        <div class="col py-2" id="gender_container">
                                            <!-- Gender -->
                                            <input class="form-check-input" type="radio" id="gender_f" name="gender" value="F" <?php
                                            if ($registerArr['gender'] == "F") {
                                                echo "checked";
                                            }
                                            ?> />
                                            <label for="Female" class="btnLabel">Female</label>

                                            <input class="form-check-input" type="radio" id="gender_m" name="gender" value="M" <?php
                                            if ($registerArr['gender'] == "M") {
                                                echo "checked";
                                            }
                                            ?> />
                                            <label for="Male">Male</label>


                                        </div>
                                    </div><br/>

                                    <div class="row">
                                        <div class="col d-none d-lg-block">Email</div>
                                        <div class="col d-none d-lg-block">Contact Number</div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <!-- Email -->
                                            <div class="input-group" id="email_container">
                                                <input onkeyup="typedEmail()" id="email" type="text" class="form-control" placeholder="Email"
                                                       aria-label="Email" aria-describedby="button-addon2" name="email"
                                                       value="<?php echo htmlspecialchars($registerArr['email']); ?>" autocomplete="off">
                                                <button class="btn btn-outline-secondary" type="button" disabled="disable" id="vrfyEmailBttn">
                                                    Verify
                                                </button>
                                                <?php
                                                // THIS SET OF PHP CODE MUST BE AFTER THE EMAIL HTML
                                                if (isset($_COOKIE['email_verified']) && $_COOKIE['email_verified'] === true):
                                                    echo "  
                                                    <script>
                                                        console.log('{$_COOKIE['email_verified']
                                                    }');
                                                        console.log('the email is verified');
                                                        var email_feedback = \"<div id='email-feedback' class='valid-feedback'>Verified</div>\";
                                                        $('#email_container').append(email_feedback);
                                                        $('#email').addClass('is-valid');
                                                    </script>";
                                                endif;
                                                ?>
                                            </div>

                                            <!-- OTP -->
                                            <div class="input-group my-1" id="onetimepass">
                                                <input id="otp" type="text" class="form-control" placeholder="Enter OTP"
                                                       aria-label="otp" aria-describedby="button-addon2"
                                                       value="">
                                                <button class="btn btn-outline-secondary" type="button"
                                                        id="submitOTP">Submit OTP</button>
                                            </div>
                                        </div>
                                        <div class="col" id="contactnumber_container">
                                            <!-- Contact Number -->
                                            <input id="contactnumber" class="form-control" type="text" name="contactnumber"
                                                   placeholder="Contact No. (+65)"
                                                   value="<?php echo htmlspecialchars($registerArr['contactnumber']);
                                                ?>" />
                                        </div>
                                    </div><br/>

                                    <div class="row">
                                        <div class="col d-none d-lg-block">Address</div>
                                        <div class="col">NRIC</div>
                                    </div>

                                    <div class="row">
                                        <div class="col" id="address_container">
                                            <!-- Address -->
                                            <input id="address" class="form-control" type="text" name="address"
                                                   placeholder="Address"
                                                   value="<?php echo htmlspecialchars($registerArr['address']); ?>" />
                                        </div>
                                        <div class="col" id="nric_container">
                                            <!-- NRIC -->
                                            <input id="nric" class="form-control" type="text" name="nric" placeholder="NRIC"
                                                   value="<?php echo htmlspecialchars($registerArr['nric']); ?>" />
                                        </div>
                                    </div><br/>

                                    <div class="row">
                                        <div class="col d-none d-lg-block">Password</div>
                                        <div class="col d-none d-lg-block">Confirm Password</div>
                                    </div>

                                    <div class="row">
                                        <div class="col" id="password_container">
                                            <!-- Password -->
                                            <input id="password" class="form-control" type="password" name="password"
                                                   placeholder="Password"
                                                   value="<?php echo htmlspecialchars($registerArr['password']); ?>" />
                                        </div>
                                        <div class="col" id="confirmpassword_container">
                                            <!-- Confirmation Password -->
                                            <input id="confirmpassword" class="form-control" type="password"
                                                   name="confirmpassword" placeholder="Confirm Password"
                                                   value="<?php echo htmlspecialchars($registerArr['confirmpassword']); ?>" />
                                        </div>
                                    </div><br/>

                                    <div class="row">
                                        <div class="col d-none d-lg-block">
                                        </div>
                                        <!-- Registration Submission -->
                                        <div class="col py-3"><button class="btn btn-primary" type="submit" name="register_patient"
                                                                      style="float: right" ;>Register</button><br /></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Should Insert ("Already have an account? Sign In")  [Hyperlink to login.php] -->
                    </div>
                </div>
            </div>
        </div> 
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET"):
            echo "
                <script>
                    $('#email_container').remove('#email_feedback');
                    $('#email').removeClass('is-valid');
                </script>
                ";
        endif;

        if ($_SERVER['REQUEST_METHOD'] == "POST"):
            if (isset($_POST['register_patient'])):
                if (isset($_COOKIE['email_verified']) && $_COOKIE['email_verified'] === false):
                    echo "
                        <script>
                            console.log('the email not verified');

                            $('#email_container').remove('#email_feedback');
                            $('#email').removeClass('is-valid');

                            var email_feedback = \"<div id='email-feedback' class='invalid-feedback'>Not Verified</div>\";
                            $('#email_container').append(email_feedback);
                            $('#email').addClass('is-invalid');
                            $('#vrfyEmailBttn').prop('disabled', false);
                        </script>
                        ";
                endif;

                // VALIDATION AND SHOW ERROR MESSAGE
                foreach ($validArr as $key => $value):
                    # If Not Valid
                    if (!$value):
                        echo "
                        <script>
                            console.log('{$key}');
                            var feedback = \"<div id='{$key}-feedback' class='invalid-feedback'>{$err_msg[$key]}</div>\";
                            $('#{$key}_container').append(feedback);
                            $('#{$key}').addClass('is-invalid');
                        </script>
                         ";
                        if ($key == 'gender'):
                            echo "
                            <script>
                                $('#{$key}_f').addClass('is-invalid');
                                $('#{$key}_m').addClass('is-invalid');
                            </script>
                             ";
                        endif;
                    endif;
                endforeach;
            endif; # -- WHEN CLICK ON REGISTER PATIENT
        endif;
        ?>
        <script>
            // DISABLE THE `VERIFY` BUTTON WHEN NEEDED
            function typedEmail()
            {
                $("#vrfyEmailBttn").show();
                document.cookie = 'email_verified=false';
                console.log(document.cookie);
                $('#email_container').remove('#email_feedback');
                $("#email").removeClass("is-valid");
                $("#email").removeClass("is-invalid");

                if (document.getElementById("email").value === "") {
                    document.getElementById('vrfyEmailBttn').disabled = true;
                } else {
                    document.getElementById('vrfyEmailBttn').disabled = false;
                }
            }


            // DEFAULT HIDE THE CONTENT INSIDE THE `onetimepass` 
            $('#onetimepass').children().hide();
            // WHEN USER CLICKS TO VERIFY EMAIL
            $("#vrfyEmailBttn").on('click', function clickedVerify()
            {
                $('#onetimepass').show();
                $('#otp').val("");
                $('#otp').removeClass("is-invalid");

                $('#email_container').remove('#email_feedback');
                $("#email").removeClass("is-invalid");
                $("#email").removeClass("is-valid");

                $('#onetimepass').children().show();
                // -- EMAIL VERIFY TRIGGER
                $.ajax({
                    type: "POST",
                    url: "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>",
                    data: {
                        ajax_emailverify: true,
                        email: $('#email').val()
                    },
                    success: function () {
                        console.log("Email Sent");
                    },
                    error: function () {
                        console.log("Email Not Sent");
                    }
                });

            });

            // WHEN USER SUBMITS THE OTP
            $("#submitOTP").on('click', function clickedSubmitOTP()
            {
                // -- EMAIL VERIFY TRIGGER
                $.ajax({
                    type: "POST",
                    url: "otpvalidate.php",
                    data: {
                        ajax_otp: true,
                        email: $('#email').val(),
                        otp: $('#otp').val()
                    },
                    success: function (valid_otp) {
                        console.log("Email Validate");
                        console.log(valid_otp);
                        console.log(JSON.stringify(valid_otp.replace(/(\r\n|\n|\r)/gm, "")));
                        var valid_status = valid_otp.replace(/(\r\n|\n|\r)/gm, "");
                        if (valid_status === 'true') {
                            $("#otp").removeClass("is-invalid");
                            $('#otp-feedback').remove();
                            $("#vrfyEmailBttn").hide();
                            var email_feedback = "<div id='email-feedback' class='valid-feedback'>Verified</div>";
                            $('#email_container').append(email_feedback);
                            $('#email-feedback').remove();
                            $("#email").addClass("is-valid");
                            $('#onetimepass').hide();
                            document.cookie = 'email_verified=true';
                            console.log('tick');
                        } else {
                            // WHEN THE OTP IS INVALID
                            $("#otp").addClass("is-invalid");
                            var otp_feedback = "<div id='otp-feedback' class='invalid-feedback'>Incorrect OTP Entered</div>";
                             $('#onetimepass').append(otp_feedback);
                        }
                    },
                    error: function () {
                        console.log("Email Validation Error");
                    }
                });

            });

        </script>
    </body>

</html>