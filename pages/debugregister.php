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

            // Upon clicking "Login" Button
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                /* Load Data to Array */
                foreach ($_POST as $key => $value) {
                    if (isset($registerArr[$key])) {
                        $registerArr[$key] = htmlspecialchars($value);
                    }
                }

                /* ------------ Start Validation ------------ */
                
                
                /* ------------ End Validation ------------ */
                
                // If Valid User Information (After Validation)
                // > Check If User Already Exist (Email & Contact Number)
                


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
                <input type="text" name="password" placeholder="Password" value="<?php echo $registerArr['password']; ?>"/><br/>

                <!-- Confirmation Password -->
                <input type="text" name="confirmpassword" placeholder="Confirm Password" value="<?php echo $registerArr['confirmpassword']; ?>"/><br/>

                <!-- Registration Submission -->
                <button type="submit">Register</button><br/>
            </form>

        </div>

    </body>
</html>