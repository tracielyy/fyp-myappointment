<!-- This File Is Solely Used For Debugging -->
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require_once ENUMS_PATH . '/User_Type.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once ENTITIES_PATH . '/Patient.php';
require_once UTILS_PATH . '/Regex.php';
require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';
require_once FUNCTIONS_PATH . '/AdminFunctions.php';

$valid_user = false;

// -- Filter Away Invalid Users -- //
if (isset($_SESSION["user"])) {

    # -- Get The LoggedIn User -- #
    $user = unserialize($_SESSION["user"]);
    $user_email = $user->get_email();
    $user_type = $user->get_usertype();

    // -- Make Sure The User Is Admin -- //
    if (User_Type::check_user_type(User_Type::ADMIN, $user_type)) {

        # -- Turn The `Switch` On -- #
        $valid_user = true;

        # -- Retrieve All Information For Viewing -- #
        $facility_list = AdminFunctions::display_all_facilities();
        $patient_list = AdminFunctions::display_all_patients($user_email);
        $practitioner_list = AdminFunctions::display_all_practitioner($user_email);
    }
}

// -- Check If Is Admin (VALID USER) -- //    
if (!$valid_user) {
    echo '<script>alert("ACCESS DENIED"); window.location.href = "Index.php";</script>';
}
?>

<html>
    <head>
        <title>FYP-21-S2-24</title>
        <!-- Styling -->

    </head>
    <body>
        <!-- PHP Script -->
        <?php
        /*
         *  This File Contains Functions To Be Used By Admin For Record Maintainence
         */
        ?>

        <!-- HTML Page Design -->





        <?php
        // -- Arrays For Field Displays -- //
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

        $facility = array(
            'facilityname' => '',
            'address' => '',
            'contactnumber' => '',
            'operatinghours' => array('opening' => '', 'closing' => '', 'is24hours' => false)
        );

        // -- Storage Array -- //
        $patient_register = array(
            'profile' => array(
                'firstname' => '',
                'lastname' => '',
                'contactnumber' => '',
                'address' => '',
                'dob' => '',
                'gender' => ''
            ),
            'credentials' => array(
                'email' => '',
                'password' => ''
            )
        );


        // Some Variables
        $err_firstname = $err_lastname = $err_gender = $err_contactnumber = $err_address = $err_dob = $err_password = $err_confirmpassword = $err_email = "";

        //==============================
        //                          Functions
        //==============================
        // Upon clicking "Login" Button 
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            # ============================= #
            ### ----  Add Facility Button Is Triggered ---- ###
            # ============================= #
            if (isset($_POST["add-facility-btn"])) {

                //==============================
                //              Load Data Into Array
                //==============================
                foreach ($_POST as $key => $value) {

                    # -- Check If The Key Is Set -- #
                    if (isset($facility[$key])) {

                        # -- Check If There Is An Inner Array -- #
                        if (is_array($value)) {
                            echo $key;
                            foreach ($_POST[$key] as $k => $v) {

                                # --- Load To Facility Array -- #
                                if ($k == 'is24hours') {
                                    $v = (bool) json_decode($v);
                                    $facility[$key][$k] = $v;
                                } else {
                                    $facility[$key][$k] = htmlspecialchars($v);
                                }
                            }
                        } else {
                            # --- Load To Facility Array -- #
                            $facility[$key] = htmlspecialchars($value);
                            $validArr[$key] = False; // Set All Field Validation Check As False
                            echo $facility[$key];
                        }
                    }
                }
                //==============================
                //           Validate & Check All The Fields
                //==============================
                //==============================
                //         Add The Medical Facility To Database
                //==============================
//                if (!in_array(False, $validArr)) {
//                    
//                }
                Medical_Facility::create_medical_facility($facility);
            }
            # ==================================== #
            ### ----  Add Medical Personnel Button Is Triggered ---- ###
            # ==================================== #
//                /* Load Data to Array */
//                foreach ($_POST as $key => $value) {
//                    if (isset($registerArr[$key])) {
//                        $registerArr[$key] = htmlspecialchars($value);
//                        $validArr[$key] = False; // Set All Field Validation Check As False
//                    }
//                }
//
//                // 
//
//
//
//                /* ------------ Start Validation ------------ */
//
//                // -- First Name
//                if (empty($registerArr['firstname'])) {
//                    $err_firstname = "Field Cannot Be Empty";
//                    echo "<style type='text/css'> #firstname{border:1.5px solid red;}</style>";
//                } else if (!Regex::validate_name($registerArr['firstname'])) {
//                    $err_firstname = "Invalid";
//                    echo "<style type='text/css'> #firstname{border:1.5px solid red;}</style>";
//                } else {
//                    $validArr['firstname'] = True; // Pass Validation
//                }
//
//                // -- Last Name
//                if (empty($registerArr['lastname'])) {
//                    $err_lastname = "Field Cannot Be Empty";
//                } else if (!Regex::validate_name($registerArr['lastname'])) {
//                    $err_lastname = "Invalid";
//                } else {
//                    $validArr['lastname'] = True; // Pass Validation
//                }
//
//                // -- Contact Number Validation
//                if (empty($registerArr['contactnumber'])) {
//                    $err_contactnumber = "Field Cannot Be Empty";
//                } else if (!Regex::validate_phone($registerArr['contactnumber'])) {
//                    $err_contactnumber = "Invalid";
//                } else {
//                    $validArr['contactnumber'] = True; // Pass Validation
//                }
//
//                // -- Gender Validation (Just Make Sure Either Male Or Female Is 'Checked')
//                if (empty($registerArr['gender'])) {
//                    // Store Some Error Message
//                    $err_gender = "Not Selected";
//                } else if (!($registerArr['gender'] == 'F' || $registerArr['gender'] == 'M')) {
//                    // Store Some Error Message
//                    $err_gender = "Invalid";
//                } else {
//                    $validArr['gender'] = True; // Pass Validation
//                }
//
//                // -- Date Of Birth (DOB) Validation
//                if (empty($registerArr['dob'])) {
//                    $err_dob = "Field Cannot Be Empty";
//                } else {
//                    $validArr['dob'] = True; // Pass Validation
//                }
//                /*
//                  -- DOB (Data Accuracy) --
//                  > Check Leap Year For 29th Feb
//                  > Check Months (01-12)
//                 */
//
//
//
//                // -- Address Validation (Unsure Of What Further Validation To Be Done)
//                if (empty($registerArr['address'])) {
//                    $err_address = "Field Cannot Be Empty";
//                } else {
//                    $validArr['address'] = True; // Pass Validation
//                }
//
//
//                // -- Email Validation
//                if (empty($registerArr['email'])) {
//                    // Store Some Error Message
//                    $err_email = "Field Cannot Be Empty";
//                } else if (!Regex::validate_email($registerArr['email'])) {
//                    // Store Some Error Message
//                    $err_email = "Invalid";
//                } else {
//                    $validArr['email'] = True; // Pass Validation
//                }
//
//                // -- Password Validation
//                if (empty($registerArr['password'])) {
//                    // Store Some Error Message
//                    $err_password = "Field Cannot Be Empty";
//                } else if (!Regex::validate_password($registerArr['password'])) {
//                    // Store Some Error Message
//                    $err_password = "Invalid";
//                } else {
//                    $validArr['password'] = True; // Pass Validation
//                }
//
//                // -- Confirm Password Validation (Check if it is the same as 'Password')
//                if (empty($registerArr['confirmpassword'])) {
//                    // Store Some Error Message
//                    $err_confirmpassword = "Field Cannot Be Empty";
//                } else if ($registerArr['confirmpassword'] !== $registerArr['password']) {
//                    // Store Some Error Message
//                    $err_confirmpassword = "Password Does Not Match";
//                } else {
//                    $validArr['confirmpassword'] = True; // Pass Validation
//                }
//
//
//                /* ------------ End Validation ------------ */
//
//                // If Valid User Information (After Validation)
//                if (!in_array(False, $validArr)) {
//                    // > Check If User Already Exist (Email & Contact Number)
//                    $exist = Account_User::check_user_exist($registerArr['email'], $registerArr['contactnumber']);
//                    if (!$exist) {
//
//                        /* Load To Patient Registration Array */
//                        foreach ($registerArr as $key => $value) {
//
//                            # Loading Of Basic Profile Information
//                            if (isset($patient_register['profile'][$key])) {
//                                $patient_register['profile'][$key] = htmlspecialchars($value);
//                            } else if (isset($patient_register['credentials'][$key])) {
//                                $patient_register['credentials'][$key] = htmlspecialchars($value);
//                            }
//                        }
//
//                        // > Salt Generation (?)
//                        // > Need To Encrypt The Password Then Store In Database
//                        Patient::create_patient($patient_register);  // -- Need To Monitor & Change If Database Info Change -- //
//                        // Reset Information
//                        $registerArr = array(
//                            'firstname' => '',
//                            'lastname' => '',
//                            'contactnumber' => '',
//                            'address' => '',
//                            'dob' => '',
//                            'gender' => '',
//                            'email' => '',
//                            'password' => '',
//                            'confirmpassword' => ''
//                        );
//                        echo "<br/> Success Registration <br/>";
//                    } else {
//                        echo "User already exist";
//                    }
//                } else {
//                    // Any Actions Or Displays For Errors
//                    echo "<div style='color:red;'>Register Fail!</div>";
//                }
        }
        ?>
        <div>
            <p>Add </p>
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
                <button type="submit" name="register-medicalpersonnel" value ="register-medicalpersonnel-btn">Register Medical Personnel</button><br/>
                <button type="submit" name="register-admin" value ="register-admin-btn">Register Admin</button><br/>
            </form>
        </div>

        <!-- Add Admin -->

        <!-- Add Medical Personnel -->

        <!-- 
                    >>> Search & Delete Medical Personnel <<<
        -->

        <!-- 
                    >>> Add Medical Facility <<<
        -->
        <div>

            <h3> Add Medical Facility</h3>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <!-- Facility Name --> 
                <input type="text" name="facilityname" placeholder="Facility Name" value="<?php echo htmlspecialchars($facility['facilityname']); ?>"/><br/>

                <!-- Facility Address --> 
                <input type="text" name="address" placeholder="Address" value="<?php echo htmlspecialchars($facility['address']); ?>"/><br/>

                <!-- 24 Hours?? (Slider) ??--> 
                Non-stop?
                <input type="checkbox" name="operatinghours[is24hours]" value="1" 
                <?php
                if ($facility['operatinghours']['is24hours']) {
                    echo "checked = 'checked'";
                }
                ?>/><br/>

                <!-- Operating Hours (Opening) --> 
                <input type="text" name="operatinghours[opening]" placeholder="Opening Hour" value="<?php echo htmlspecialchars($facility['operatinghours']['opening']); ?>"/><br/>

                <!-- Operating Hours (Closing) --> 
                <input type="text" name="operatinghours[closing]" placeholder="Closing Hour" value="<?php echo htmlspecialchars($facility['operatinghours']['closing']); ?>"/><br/>

                <!-- Facility Contact Number --> 
                <input type="text" name="contactnumber" placeholder="Contact Number" value="<?php echo htmlspecialchars($facility['contactnumber']); ?>"/><br/>

                <!-- Add Facility Button -->
                <p><button type="submit" name="add-facility-btn" value ="add-facility">Add Medical Facility</button></p>
            </form>
        </div>


        <h3>Display All Medical Personnel</h3>
        <div>
            <?php
            # -- Display All The Medical Personnel -- #
            if (!empty($practitioner_list)) {

                // -- Count All Medical_Personnel Records
                $practitioner_count = count($practitioner_list);
                $cols = 1;
                $rows = $practitioner_count;

                // -- Create Table
                echo "<form method ='post' action='";
                echo htmlspecialchars($_SERVER['PHP_SELF']);
                echo "'>";

                echo "<table border='1'>";

                // -- Headers
                echo "<tr>";
                echo "
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>User Type</th>";
                echo "</tr>";
                foreach ($practitioner_list as $practitioner) {

                    echo "<tr>";
                    echo "<td >{$practitioner->get_firstname()}</td>";
                    echo "<td >{$practitioner->get_lastname()}</td>";
                    echo "<td >{$practitioner->get_email()}</td>";
                    echo "<td>{$practitioner->get_usertype()}</td>";
                    echo "</tr>";
                }

                echo "</table>";
                echo "</form>";
            } else {
                echo "<p>NO RECORD(S) FOUND</p>";
            }
            ?>
        </div>


        <h3>Display All Patients</h3>
        <div>
            <?php
            # -- Display All The  Patients -- #
            if (!empty($patient_list)) {

                // -- Count All Medical_Personnel Records
                $patient_count = count($patient_list);
                $cols = 1;
                $rows = $patient_count;

                // -- Create Table
                echo "<form method ='post' action='";
                echo htmlspecialchars($_SERVER['PHP_SELF']);
                echo "'>";

                echo "<table border='1'>";

                // -- Headers
                echo "<tr>";
                echo "
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>User Type</th>";
                echo "</tr>";
                foreach ($patient_list as $patient) {

                    echo "<tr>";
                    echo "<td >{$patient->get_firstname()}</td>";
                    echo "<td >{$patient->get_lastname()}</td>";
                    echo "<td >{$patient->get_email()}</td>";
                    echo "<td>{$patient->get_usertype()}</td>";
                    echo "</tr>";
                }

                echo "</table>";
                echo "</form>";
            } else {
                echo "<p>NO RECORD(S) FOUND</p>";
            }
            ?>
        </div>

        <h3>Display All Facilities</h3>
        <!--        <p>Last Facility ID: <?php //echo Medical_Facility::get_last_medical_id();              ?></p>-->
        <div>
            <?php
            # -- Display All The Medical Facilities -- #
            if (!empty($facility_list)) {

                // -- Count All Facility Records
                $facility_count = count($facility_list);
                $cols = 1;
                $rows = $facility_count;

                // -- Create Table
                echo "<form method ='post' action='";
                echo htmlspecialchars($_SERVER['PHP_SELF']);
                echo "'>";

                echo "<table border='1'>";

                // -- Headers
                echo "<tr>";
                echo "
                    <th>Facility Id</th>
                    <th>Facility Name</th>
                    <th>Address</th>
                    <th>Contact Number</th>";
                echo "</tr>";
                foreach ($facility_list as $facility) {

                    echo "<tr>";
                    echo "<td >{$facility->get_facilityid()}</td>";
                    echo "<td >{$facility->get_facilityname()}</td>";
                    echo "<td >{$facility->get_address()}</td>";
                    echo "<td>{$facility->get_contactnumber()}</td>";
                    echo "</tr>";
                }

                echo "</table>";
                echo "</form>";
            } else {
                echo "<p>NO RECORD(S) FOUND</p>";
            }
            ?>
        </div>



    </body>
</html>