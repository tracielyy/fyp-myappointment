<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
session_start();
?>
<html>
    <head>
        <title>FYP-21-S2-24</title>
        <!-- Styling -->

    </head>
    <body>
        <!-- PHP Script -->
        <?php
        require_once '../entities/Account_User.php';
        require_once '../entities/Patient.php';
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


            // Some Variables
            // -- Regex
            $contact_number_pattern = "/^[689]{1}[0-9]{7}$/"; // Singapore phone number length
            $email_pattern = '/^[a-zA-Z0-9]+(.[_a-z0-9-]+)(?!.*[~@\%\/\\\&\?\,\'\;\:\!\-]{2}).*@[a-z0-9-]+(.[a-z0-9-]+)(.[a-z]{2,3})$/';
            $name_pattern = "/^(?![ .]+$)[a-zA-Z ,]*$/";
            $password_pattern = "/^" . // Pattern Match From Start Of String
                    "(?=.*[0-9])" . // At Least 1 Digit
                    "(?=.*[a-z])" . // At Least 1 Lower Case Char
                    "(?=.*[A-Z])" . // At Least 1 Upper Case Char
                    "(?=.*[\*\.!@\$%^&\(\)\{\}\[\]:;<>,.\?\/\~_\+-=\|])" . // At Least 1 Special Chars
                    ".{8,32}" . // 8 To 32 Chars In Total
                    "$/";                                                             // Pattern Match To End Of String
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
                    
                } else if (!preg_match($name_pattern, $registerArr['firstname'])) {
                    
                } else {
                    $validArr['firstname'] = True; // Pass Validation
                }

                // -- Last Name
                if (empty($registerArr['lastname'])) {
                    
                } else if (!preg_match($name_pattern, $registerArr['lastname'])) {
                    
                } else {
                    $validArr['lastname'] = True; // Pass Validation
                }

                // -- Contact Number Validation
                if (empty($registerArr['contactnumber'])) {
                    // Store Some Error Message
                } else if (!preg_match($contact_number_pattern, $registerArr['contactnumber'])) {
                    // Store Some Error Message
                } else {
                    $validArr['contactnumber'] = True; // Pass Validation
                }

                // -- Gender Validation (Just Make Sure Either Male Or Female Is 'Checked')
                if (empty($registerArr['gender'])) {
                    // Store Some Error Message
                } else if (!($registerArr['gender'] == 'F' || $registerArr['gender'] == 'M')) {
                    // Store Some Error Message
                } else {
                    $validArr['gender'] = True; // Pass Validation
                }

                // -- Date Of Birth (DOB) Validation
                if (empty($registerArr['dob'])) {
                    
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
                    
                } else {
                    $validArr['address'] = True; // Pass Validation
                }


                // -- Email Validation
                if (empty($registerArr['email'])) {
                    // Store Some Error Message
                } else if (!preg_match($email_pattern, $registerArr['email'])) {
                    // Store Some Error Message
                } else {
                    $validArr['email'] = True; // Pass Validation
                }

                // -- Password Validation
                if (empty($registerArr['password'])) {
                    // Store Some Error Message
                } else if (!preg_match($password_pattern, $registerArr['password'])) {
                    // Store Some Error Message
                    echo 'Password invalid';
                } else {
                    $validArr['password'] = True; // Pass Validation
                }

                // -- Confirm Password Validation (Check if it is the same as 'Password')
                if (empty($registerArr['confirmpassword'])) {
                    // Store Some Error Message
                } else if ($registerArr['confirmpassword'] !== $registerArr['password']) {
                    // Store Some Error Message
                } else {
                    $validArr['confirmpassword'] = True; // Pass Validation
                }


                /* ------------ End Validation ------------ */

                // If Valid User Information (After Validation)
                if (!in_array(False, $validArr)) {
                    // > Check If User Already Exist (Email & Contact Number)
                    $exist = Account_User::check_user_exist($registerArr['email'], $registerArr['contactnumber']);
                    if (!$exist) {
                        // > Salt Generation (?)
                        // > Need To Encrypt The Password Then Store In Database
                        unset($registerArr["confirmpassword"]); // We do not need to store 'confirmpassword'
                        Patient::add_patient($registerArr);

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
                <input type="text" name="firstname" placeholder="First Name" value="<?php echo $registerArr['firstname']; ?>"/><br/>

                <!-- Last Name -->
                <input type="text" name="lastname" placeholder="Last Name" value="<?php echo $registerArr['lastname']; ?>"/><br/>

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


                <!-- Date Of Birth -->
                <input type="text" name="dob" placeholder="Date Of Birth" value="<?php echo $registerArr['dob']; ?>"/><br/>

                <!-- Address -->
                <input type="text" name="address" placeholder="Address" value="<?php echo $registerArr['address']; ?>"/><br/>

                <!-- Email -->
                <input type="text" name="email" placeholder="Email" value="<?php echo $registerArr['email']; ?>"/><br/>

                <!-- Password -->
                <input type="password" name="password" placeholder="Password" value="<?php echo $registerArr['password']; ?>"/><br/>

                <!-- Confirmation Password -->
                <input type="password" name="confirmpassword" placeholder="Confirm Password" value="<?php echo $registerArr['confirmpassword']; ?>"/><br/>

                <!-- Registration Submission -->
                <button type="submit">Register</button><br/>
            </form>

        </div>

    </body>
</html>