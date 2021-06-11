<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once ENTITIES_PATH . '/Patient.php';
require_once UTILS_PATH . '/Regex.php';
require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';
require_once FUNCTIONS_PATH . '/PatientFunctions.php';
?>
<html>
    <head>
        <title>FYP-21-S2-24</title>
        <!-- Styling -->

    </head>
    <body>
        <!-- PHP Script -->
        <?php
        // Code here
        ?>

        <!-- HTML Page Design -->
        <div>
            <!-- Navigation -->



            <!-- Debug Test For Users -->
            <!-- Hint: Explode & Implode For Date Of Birth (DD-MM-YYYY) If there is other preferred string format (e.g. '/') -->
            <?php
            // Used to store correct data
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
                    $exist = AccountUserFunctions::check_user_exist($registerArr['email'], $registerArr['contactnumber']);
                    if (!$exist) {

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
                        PatientFunctions::create_patient($patient_register);  // -- Need To Monitor & Change If Database Info Change -- //
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

            <!-- Form -->
            <form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <!-- First Name -->
                <input type="text" id="firstname" name="firstname" placeholder="First Name" value="<?php echo $registerArr['firstname']; ?>"/><br/>

                <!-- Last Name -->
                <input type="text" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo $registerArr['lastname']; ?>"/><br/>

                <!-- Contact Number -->
                <input type="text" name="contactnumber" placeholder="Contact Number" value="<?php echo $registerArr['contactnumber']; ?>"/><br/>

                <!-- Gender -->
                <label for="gender">Select Gender: </label>
                <input type="radio" id="Female" name="gender" value="F"<?php
            if ($registerArr['gender'] == "F") {
                echo "checked";
            }
            ?>/><label for="Female" class="btnLabel">Female</label>

                <input type="radio" name="gender" id="Male" value="M" <?php
                if ($registerArr['gender'] == "M") {
                    echo "checked";
                }
            ?> /><label for="Male">Male</label>
                </select><br/>


                <!-- Date Of Birth (Do We Use Calendar?) --> 
                <input type="text" name="dob" placeholder="Date Of Birth" value="<?php echo htmlspecialchars($registerArr['dob']); ?>"/><br/>

                <!-- Address -->
                <input type="text" name="address" placeholder="Address" value="<?php echo htmlspecialchars($registerArr['address']); ?>"/><br/>

                <!-- Email -->
                <input type="text" name="email" placeholder="Email" value="<?php echo htmlspecialchars($registerArr['email']); ?>"/><br/>

                <!-- Password -->
                <input type="password" name="password" placeholder="Password" value="<?php echo htmlspecialchars($registerArr['password']); ?>"/><br/>

                <!-- Confirmation Password -->
                <input type="password" name="confirmpassword" placeholder="Confirm Password" value="<?php echo htmlspecialchars($registerArr['confirmpassword']); ?>"/><br/>

                <!-- Registration Submission -->
                <button type="submit">Register</button><br/>
            </form>

        </div>

    </body>
</html>