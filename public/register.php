<!--
   Developed By FYP-21-S2-24
-->
<!DOCTYPE html>
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
?>
<html lang="en">
    <head>
        <?php require COMPONENT_PATH . '/bootstrap.php' ?>
        <?php require COMPONENT_PATH . '/javascript.php' ?>
        
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./css/loginRegister.css"/> 
        <title>Register</title>
        

    </head>

    <!-- Logic & Validation -->
    <?php
    require_once ENTITIES_PATH . '/Account_User.php';
    require_once ENTITIES_PATH . '/Patient.php';


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
    $err_firstname = $err_lastname = $err_gender = $err_contactnumber = $err_address = $err_dob = $err_password = $err_confirmpassword = $err_email = "";
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
            "$/";    // Pattern Match To End Of String
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
        } else if (!preg_match($name_pattern, $registerArr['firstname'])) {
            $err_firstname = "Invalid";
            echo "<style type='text/css'> #firstname{border:1.5px solid red;}</style>";
        } else {
            $validArr['firstname'] = True; // Pass Validation
        }

        // -- Last Name
        if (empty($registerArr['lastname'])) {
            $err_lastname = "Field Cannot Be Empty";
            echo "<style type='text/css'> #lastname{border:1.5px solid red;}</style>";
        } else if (!preg_match($name_pattern, $registerArr['lastname'])) {
            $err_lastname = "Invalid";
            echo "<style type='text/css'> #lastname{border:1.5px solid red;}</style>";
        } else {
            $validArr['lastname'] = True; // Pass Validation
        }

        // -- Contact Number Validation
        if (empty($registerArr['contactnumber'])) {
            $err_contactnumber = "Field Cannot Be Empty";
            echo "<style type='text/css'> #contactnumber{border:1.5px solid red;}</style>";
        } else if (!preg_match($contact_number_pattern, $registerArr['contactnumber'])) {
            $err_contactnumber = "Invalid";
            echo "<style type='text/css'> #contactnumber{border:1.5px solid red;}</style>";
        } else {
            $validArr['contactnumber'] = True; // Pass Validation
        }

        // -- Gender Validation (Just Make Sure Either Male Or Female Is 'Checked')
        if (empty($registerArr['gender'])) {
            $err_gender = "Not Selected";
        } else if (!($registerArr['gender'] == 'F' || $registerArr['gender'] == 'M')) {
            $err_gender = "Invalid";
        } else {
            $validArr['gender'] = True; // Pass Validation
        }

        // -- Date Of Birth (DOB) Validation
        if (empty($registerArr['dob'])) {
            $err_dob = "Field Cannot Be Empty";
            echo "<style type='text/css'> #dob{border:1.5px solid red;}</style>";
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
            echo "<style type='text/css'> #address{border:1.5px solid red;}</style>";
        } else {
            $validArr['address'] = True; // Pass Validation
        }


        // -- Email Validation
        if (empty($registerArr['email'])) {
            $err_email = "Field Cannot Be Empty";
            echo "<style type='text/css'> #email{border:1.5px solid red;}</style>";
        } else if (!preg_match($email_pattern, $registerArr['email'])) {
            $err_email = "Invalid";
            echo "<style type='text/css'> #email{border:1.5px solid red;}</style>";
        } else {
            $validArr['email'] = True; // Pass Validation
        }

        // -- Password Validation
        if (empty($registerArr['password'])) {
            // Store Some Error Message
            $err_password = "Field Cannot Be Empty";
            echo "<style type='text/css'> #password{border:1.5px solid red;}</style>";
        } else if (!preg_match($password_pattern, $registerArr['password'])) {
            // Store Some Error Message
            $err_password = "Invalid";
            echo "<style type='text/css'> #password{border:1.5px solid red;}</style>";
        } else {
            $validArr['password'] = True; // Pass Validation
        }

        // -- Confirm Password Validation (Check if it is the same as 'Password')
        if (empty($registerArr['confirmpassword'])) {
            $err_confirmpassword = "Field Cannot Be Empty";
            echo "<style type='text/css'> #confirmpassword{border:1.5px solid red;}</style>";
        } else if ($registerArr['confirmpassword'] !== $registerArr['password']) {
            $err_confirmpassword = "Password Does Not Match";
            echo "<style type='text/css'> #confirmpassword{border:1.5px solid red;}</style>";
        } else {
            $validArr['confirmpassword'] = True; // Pass Validation
        }


        /* ------------ End Validation ------------ */

        // If Valid User Information (After Validation)
        if (!in_array(False, $validArr)) {
            // > Check If User Already Exist (Email & Contact Number)
            $exist = Account_User::check_user_exist($registerArr['email'], $registerArr['contactnumber']);
            if (!$exist) {
                // > Salt is in built in the hash php std library function
                // > Hashing the password to be stored in the database
                
                //  From php doc: The used algorithm, cost and salt are returned as part of the hash. Therefore, 
                //all information that's needed to verify the hash is included in it. This allows the password_verify() function to verify 
                //the hash without needing separate storage for the salt or algorithm information.
                password_hash('sha256',$registerArr['password']);
                // > Need To Encrypt The Password Then Store In Database
                unset($registerArr["confirmpassword"]); // We do not need to store 'confirmpassword'
                Patient::create_patient($registerArr);

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
        }
    }
    ?>

    <body>
        <!-- Navigation -->
        <?php require COMPONENT_PATH . '/navbar.php' ?>

        <!-- Registration -->
        <div class="row m-4"></div>
        <div class="container col-md-10 col-lg-6">
            <div class="col-auto">
                <div class="shadow card p-2 rounded1">
                    <div class="card-body m-2">
                        <h1 class="card-title px-5 py-3">Register</h1>
                        <div class="px-5">
                            <!-- Form -->
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <div class="row">
                                    <div class="col">First Name</div>
                                    <div class="col">Last Name</div>
                                </div>
                                <div class="row pb-3">
                                    <div class="col">
                                        <!-- First Name -->
                                        <input id="firstname" class="form-control" type="text" name="firstname" placeholder="First Name"
                                               value="<?php echo htmlspecialchars($registerArr['firstname']); ?>" />
                                    </div>
                                    <div class="col">
                                        <!-- Last Name -->
                                        <input id="lastname" class="form-control" type="text" name="lastname" placeholder="Last Name"
                                               value="<?php echo htmlspecialchars($registerArr['lastname']); ?>" />

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">Date of Birth</div>
                                    <div class="col">Select Gender:</div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <!-- Date Of Birth -->
                                        <input id="dob" class="form-control" type="text" name="dob" placeholder="Date Of Birth"
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
                                    <div class="col">Email</div>
                                    <div class="col">Contact Number</div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <!-- Email -->
                                        <input id="email" class="form-control" type="text" name="email" placeholder="Email"
                                               value="<?php echo htmlspecialchars($registerArr['email']); ?>" /><br />
                                    </div>
                                    <div class="col">
                                        <!-- Contact Number -->
                                        <input id="contactnumber" class="form-control" type="text" name="contactnumber" placeholder="Contact Number"
                                               value="<?php echo htmlspecialchars($registerArr['contactnumber']); ?>" /><br />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">Address</div>
                                    <div class="col"></div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <!-- Address -->
                                        <input id="address" class="form-control" type="text" name="address" placeholder="Address"
                                               value="<?php echo htmlspecialchars($registerArr['address']); ?>" /><br />
                                    </div>
                                    <div class="col"></div>
                                </div>

                                <div class="row">
                                    <div class="col">Password</div>
                                    <div class="col">Confirm Password</div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <!-- Password -->
                                        <input id="password" class="form-control" type="password" name="password" placeholder="Password"
                                               value="<?php echo htmlspecialchars($registerArr['password']); ?>" /><br />
                                    </div>
                                    <div class="col">
                                        <!-- Confirmation Password -->
                                        <input id="confirmpassword" class="form-control" type="password" name="confirmpassword" placeholder="Confirm Password"
                                               value="<?php echo htmlspecialchars($registerArr['confirmpassword']); ?>" /><br /></div>
                                </div>

                                <div class="row">
                                    <div class="col ">
                                    </div>
                                    <!-- Registration Submission -->
                                    <div class="col py-3"><button class="btn btn-primary" type="submit" style="float: right"
                                                                  ;>Register</button><br /></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Should Insert ("Already have an account? Sign In")  [Hyperlink to login.php] -->
                </div>
            </div>
        </div>


    </body>

</html>