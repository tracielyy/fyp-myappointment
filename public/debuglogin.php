<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
?>
<html>
    <head>
        <!-- Title -->
        <title>FYP-21-S2-24</title>
        <!-- Styling -->
        <?php require COMPONENT_PATH . '/bootstrap.php' ?>

    </head>
    <body>
        <!-- PHP Script -->
        <?php
        require_once ENTITIES_PATH . '/Account_User.php';
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

            // -- Msg Variables
            $msg = "";

            // -- Regex
            $email_pattern = '/^[a-zA-Z0-9]+(.[_a-z0-9-]+)(?!.*[~@\%\/\\\&\?\,\'\;\:\!\-]{2}).*@[a-z0-9-]+(.[a-z0-9-]+)(.[a-z]{2,3})$/';


            // -- When Redirect or Load The Page
            if ($_SERVER['REQUEST_METHOD'] == "GET") {
                if (isset($_SESSION["user"])) {
                    echo unserialize($_SESSION["user"]);
                }
            }



            // Upon clicking "Login" Button
            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                /* Load Data to Array */
                foreach ($_POST as $key => $value) {
                    if (isset($loginArr[$key])) {
                        $loginArr[$key] = htmlspecialchars($value);
                        $validArr[$key] = False; // Set All Field Validation Check As False
                    }
                }

                // Possible Validation of Email Before Firestore Query
                /* ------------ Start Validation ------------ */


                // -- Email Validation
                if (empty($loginArr['email'])) {
                    // Store Some Error Message
                } else if (!preg_match($email_pattern, $loginArr['email'])) {
                    // Store Some Error Message
                } else {
                    $validArr['email'] = True; // Pass Validation
                }
                
                // Password
                $validArr["password"] = True;

                /* ------------ End Validation ------------ */

                // Start Authenticating User (boolean)
                $auth = Account_User::authenticate_user($loginArr);

                // Check If There Is Any "token" generated
                if (!isset($_SESSION['token'])) {
                    $token_length = 10;
                    $_SESSION['token'] = Account_User::get_token($token_length);
                }

                // Check If There Are Any Other Login Session (Terminate Other Session?)
                $auth_user = Account_User::load_user_data($loginArr);
                $session_logon_allowed = Account_User::check_session($auth_user->get_session(), session_id(), $_SESSION['token']);

                // User Authenticated
                if ($auth) {
                    if ($session_logon_allowed) {
                        Account_User::login($auth_user->get_email(), session_id(), $_SESSION['token']);
                        $auth_user = Account_User::load_user_data($loginArr); // Reload After Login Session Update
                        $_SESSION['user'] = serialize($auth_user); // Store User Data In Session
                        //header("Location:debugreceive.php"); // Redirect Upon Success Authenticate
                        echo nl2br(PHP_EOL . "Success" . PHP_EOL);

                        // Clear Fields
                        $loginArr = array(
                            'email' => '',
                            'password' => '',
                        );
                    } else {
                        $msg = "Account is logged in at another location";
                    }
                } else {
                    $msg = "Invalid Credentials!";
                }
            }
            ?>
            <!-- Msg -->
            <div>
                <?php echo $msg; ?>
            </div>

            <!-- Login Form -->
            <form method="post"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="email" name="email" required placeholder="Email"  value="<?php echo $loginArr['email']; ?>"/>
                <input type="password" name="password" placeholder="Password" value="<?php echo $loginArr['password']; ?>"/>
                <button type="submit" name="login" value="login">Login</button>
            </form>
            <!-- Logout -->
            <!--<button type ="submit" name="logout" value="logout">Logout</button>-->
            <a href="debuglogout.php">Logout</a>

        </div>

    </body>
</html>