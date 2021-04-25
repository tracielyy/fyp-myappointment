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
            $profileArr = array(
                'email' => '',
                'password' => '',
            );

            // -- Regex

            // Upon clicking "Login" Button
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                /* Load Data to Array */
                foreach ($_POST as $key => $value) {
                    if (isset($loginArr[$key])) {
                        $loginArr[$key] = htmlspecialchars($value);
                    }
                }


                /* ------------ Start Validation ------------ */




                /* ------------ End Validation ------------ */


            }
            ?>

            <!-- Login Form -->
            <form method="post"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="password" name="email" required placeholder="Email"  value="<?php echo $profileArr['currentpassword']; ?>"/>
                <button type="submit" name="changepassword" value="changepassword">Change Password</button>
            </form>
            <!-- Logout -->
            <!--<button type ="submit" name="logout" value="logout">Logout</button>-->
            <a href="debuglogout.php">Logout</a>




        </div>

    </body>
</html>