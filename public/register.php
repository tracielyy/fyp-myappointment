<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require '../vendor/autoload.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once UTIL_MOD . '/Regex.php';
require_once UTIL_MOD . '/StringUtils.php';


// Used to store correct data
$registerArr = array(
    'firstname' => '',
    'lastname' => '',
    'contactnumber' => '',
    'address' => '',
    'dob' => Time::date_format_change(Time::get_current_date(), Time::CALENDAR_FORMAT_DEFAULT),
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
        'gender' => ''
    ),
    'credentials' => array('email' => '', 'password' => '')
);


// Some Variables
$err_firstname = $err_lastname = $err_gender = $err_contactnumber = $err_address = $err_dob = $err_password = $err_confirmpassword = $err_email = "";

// Upon clicking "Login" Button 
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    /* Load Data to Array */
    foreach ($_POST as $key => $value) {
        if (isset($registerArr[$key])) {
            $registerArr[$key] = htmlspecialchars($value);
            $validArr[$key] = False; // Set All Field Validation Check As False
        }
    }

    // 



    /* ------------ Start Validation ------------ */

    // -- First Name
    if (empty($registerArr['firstname'])) {
        $err_firstname = "Field Cannot Be Empty";
        echo "<style type='text/css'> #firstname{border:1.5px solid red;}</style>";
    } else if (!Regex::validate_name($registerArr['firstname'])) {
        $err_firstname = "Invalid";
        echo "<style type='text/css'> #firstname{border:1.5px solid red;}</style>";
    } else {
        $validArr['firstname'] = True; // Pass Validation
    }

    // -- Last Name
    if (empty($registerArr['lastname'])) {
        $err_lastname = "Field Cannot Be Empty";
    } else if (!Regex::validate_name($registerArr['lastname'])) {
        $err_lastname = "Invalid";
    } else {
        $validArr['lastname'] = True; // Pass Validation
    }

    // -- Contact Number Validation
    if (empty($registerArr['contactnumber'])) {
        $err_contactnumber = "Field Cannot Be Empty";
    } else if (!Regex::validate_phone($registerArr['contactnumber'])) {
        $err_contactnumber = "Invalid";
    } else {
        $validArr['contactnumber'] = True; // Pass Validation
    }

    // -- Gender Validation (Just Make Sure Either Male Or Female Is 'Checked')
    if (empty($registerArr['gender'])) {
        // Store Some Error Message
        $err_gender = "Not Selected";
    } else if (!($registerArr['gender'] == 'F' || $registerArr['gender'] == 'M')) {
        // Store Some Error Message
        $err_gender = "Invalid";
    } else {
        $validArr['gender'] = True; // Pass Validation
    }

    // -- Date Of Birth (DOB) Validation
    if (empty($registerArr['dob'])) {
        $err_dob = "Field Cannot Be Empty";
    } else {
        $validArr['dob'] = True; // Pass Validation
    }
    /*
      -- DOB (Data Accuracy) --
      > Check Leap Year For 29th Feb
      > Check Months (01-12)
     */



    // -- Address Validation (Unsure Of What Further Validation To Be Done)
    if (empty($registerArr['address'])) {
        $err_address = "Field Cannot Be Empty";
    } else {
        $validArr['address'] = True; // Pass Validation
    }


    // -- Email Validation
    if (empty($registerArr['email'])) {
        // Store Some Error Message
        $err_email = "Field Cannot Be Empty";
    } else if (!Regex::validate_email($registerArr['email'])) {
        // Store Some Error Message
        $err_email = "Invalid";
    } else {
        $registerArr['email'] = StringUtils::clean_input($registerArr['email']);
        $validArr['email'] = True; // Pass Validation
    }

    // -- Password Validation
    if (empty($registerArr['password'])) {
        // Store Some Error Message
        $err_password = "Field Cannot Be Empty";
    } else if (!Regex::validate_password($registerArr['password'])) {
        // Store Some Error Message
        $err_password = "Invalid";
    } else {
        $validArr['password'] = True; // Pass Validation
    }

    // -- Confirm Password Validation (Check if it is the same as 'Password')
    if (empty($registerArr['confirmpassword'])) {
        // Store Some Error Message
        $err_confirmpassword = "Field Cannot Be Empty";
    } else if ($registerArr['confirmpassword'] !== $registerArr['password']) {
        // Store Some Error Message
        $err_confirmpassword = "Password Does Not Match";
    } else {
        $validArr['confirmpassword'] = True; // Pass Validation
    }


    /* ------------ End Validation ------------ */

    // If Valid User Information (After Validation)
    if (!in_array(False, $validArr)) {
        // > Check If User Already Exist (Email & Contact Number)
        $exist = Account_User::check_user_exist($registerArr['email'], $registerArr['contactnumber']);
        if (!$exist) {

            # Change The Date Back To Database Default
            $registerArr['dob'] = Time::date_format_default($registerArr['dob']);

            /* Load To Patient Registration Array */
            foreach ($registerArr as $key => $value) {

                # Loading Of Basic Profile Information
                if (isset($patient_register['profile'][$key])) {
                    $patient_register['profile'][$key] = htmlspecialchars($value);
                } else if (isset($patient_register['credentials'][$key])) {
                    $patient_register['credentials'][$key] = htmlspecialchars($value);
                } else if (isset($patient_register['profile']['name'][$key])) {
                    $patient_register['profile']['name'][$key] = htmlspecialchars($value);
                }
            }


            // > Salt Generation (?)
            // > Need To Encrypt The Password Then Store In Database
            Patient::create_patient($patient_register);  // -- Need To Monitor & Change If Database Info Change -- //
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
            echo "<br/> Success Registration <br/>";
            // -- Need To Send A Email To Ask Patient To Verify Email -- //
        } else {
            echo "User already exist";
        }
    } else {
        // Any Actions Or Displays For Errors
        echo "<div style='color:red;'>Register Fail!</div>";
    }
}
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
    <?php require TEMPLATES_PATH . '/navbar.php' ?>
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
                                <div class="row pb-3">
                                    <div class="col">
                                        <!-- First Name -->
                                        <input id="firstname" class="form-control" type="text" name="firstname"
                                            placeholder="First Name" value="<?php echo $registerArr['firstname']; ?>" />
                                    </div>
                                    <div class="col">
                                        <!-- Last Name -->
                                        <input id="lastname" class="form-control" type="text" name="lastname"
                                            placeholder="Last Name" value="<?php echo $registerArr['lastname']; ?>" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col d-none d-lg-block">Date of Birth</div>
                                    <div class="col d-none d-lg-block">Select Gender:</div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <!-- Date Of Birth -->
                                        <input id="dob" class="form-control" type="date" name="dob"
                                            value="<?php echo htmlspecialchars($registerArr['dob']); ?>" /><br />
                                    </div>

                                    <div class="col py-2">
                                        <!-- Gender -->
                                        <input class="form-check-input" type="radio" id="Female" name="gender" value="F" <?php
                                            if ($registerArr['gender'] == "F") {
                                                echo "checked";
                                            }
                                            ?> /><label for="Female" class="btnLabel">Female</label>

                                        <input class="form-check-input" type="radio" name="gender" id="Male" value="M" <?php
                                            if ($registerArr['gender'] == "M") {
                                                echo "checked";
                                            }
                                            ?> />
                                        <label for="Male">Male</label>
                                        </select><br />

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col d-none d-lg-block">Email</div>
                                    <div class="col d-none d-lg-block">Contact Number</div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <!-- Email -->
                                        <div class="input-group">
                                            <input id="email" type="text" class="form-control" placeholder="Email"
                                                aria-label="Email" aria-describedby="button-addon2"
                                                value="<?php echo htmlspecialchars($registerArr['email']); ?>">
                                            <button class="btn btn-outline-secondary" type="button"
                                                id="vrfyEmailBttn">Verify</button>
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
                                    <div class="col">
                                        <!-- Contact Number -->
                                        <input id="contactnumber" class="form-control" type="text" name="contactnumber"
                                            placeholder="Contact Number"
                                            value="<?php echo htmlspecialchars($registerArr['contactnumber']); ?>" /><br />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col d-none d-lg-block">Address</div>
                                    <div class="col">NRIC</div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <!-- Address -->
                                        <input id="address" class="form-control" type="text" name="address"
                                            placeholder="Address"
                                            value="<?php echo htmlspecialchars($registerArr['address']); ?>" /><br />
                                    </div>
                                    <div class="col">
                                        <!-- NRIC -->
                                        <input id="nric" class="form-control" type="text" name="nric" placeholder="NRIC"
                                            value="<?php //echo htmlspecialchars($registerArr['address']); ?>" /><br />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col d-none d-lg-block">Password</div>
                                    <div class="col d-none d-lg-block">Confirm Password</div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <!-- Password -->
                                        <input id="password" class="form-control" type="password" name="password"
                                            placeholder="Password"
                                            value="<?php echo htmlspecialchars($registerArr['password']); ?>" /><br />
                                    </div>
                                    <div class="col">
                                        <!-- Confirmation Password -->
                                        <input id="confirmpassword" class="form-control" type="password"
                                            name="confirmpassword" placeholder="Confirm Password"
                                            value="<?php echo htmlspecialchars($registerArr['confirmpassword']); ?>" /><br />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col d-none d-lg-block">
                                    </div>
                                    <!-- Registration Submission -->
                                    <div class="col py-3"><button class="btn btn-primary" type="submit"
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

<script>
    $('#onetimepass').children().hide();
     $("#vrfyEmailBttn").on('click',function clickedVerify()
  {
    $('#onetimepass').children().show();
  });
    </script>
</body>

</html>