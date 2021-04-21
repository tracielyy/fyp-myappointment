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
            $loginArr = array(
                'email' => '',
                'password' => '',
            );

            // Upon clicking "Login" Button
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                /* Load Data to Array */
                foreach ($_POST as $key => $value) {
                    if (isset($loginArr[$key])) {
                        $loginArr[$key] = htmlspecialchars($value);
                    }
                }
                
               // Possible Validation of Email Before Firestore Query
                /* ------------ Start Validation ------------ */


                /* ------------ End Validation ------------ */

                // Start Authenticating User
                $auth_user = Account_User::authenticate_user($loginArr['email'], $loginArr['password']);
                if ($auth_user != NULL) {
                    echo $auth_user;  // Debug Printing
                    $_SESSION['user'] = serialize($auth_user); // Store User Data In Session
                    header("Location:debugreceive.php"); // Redirect Upon Success Authenticate
                } else {
                    echo "Invalid Credentials!";
                    
                    // Clear Fields
                    $loginArr = array(
                        'email' => '',
                        'password' => '',
                    );
                }
            }
            ?>
            
            <!-- Login Form -->
            <form method="post"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="email" name="email" required placeholder="Email"  value="<?php echo $loginArr['email']; ?>"/>
                <input type="password" name="password" placeholder="Password" value="<?php echo $loginArr['password']; ?>"/>
                <button type="submit">Login</button>
            </form>

        </div>

    </body>
</html>