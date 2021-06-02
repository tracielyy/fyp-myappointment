<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
//# Debug Start Session (Do Not Copy) #
//session_start();
//$_SESSION = array();
//session_destroy();
//# Debug Start Session (Do Not Copy) #

session_start();
/* Load Config File */
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once UTILS_PATH . '/Email.php';
require_once UTILS_PATH . '/Regex.php';
require_once UTILS_PATH . '/Time.php';
require_once UTILS_PATH . '/StringUtils.php';
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

            $validArr = array();

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
                } else if (!Regex::validate_email($loginArr['email'])) {
                    // Store Some Error Message
                } else {
                    $validArr['email'] = True; // Pass Validation
                }

                // Password
                $validArr["password"] = True;

                /* ------------ End Validation ------------ */
                if (!in_array(FALSE, $validArr)) {

                    # -- Start Authenticating User (boolean)
                    $auth = Account_User::authenticate_user($loginArr);

                    # -- Check If There Is Any "token" generated ---
                    if (!isset($_SESSION['token'])) {

                        // Default Session Token Length
                        $token_length = 15;
                        $_SESSION['token'] = StringUtils::generate_token($token_length);
                    }

                    # -- User Authenticated ----
                    if ($auth) {

                        # -- Check If There Are Any Other Login Session (Terminate Other Session?)
                        $auth_user = Account_User::load_user_data($loginArr);
                        $session_logon_allowed = Account_User::check_session($auth_user->get_session(), session_id(), $_SESSION['token']);

                        # -- Get IP Address ---
                        // whether ip is from share internet
                        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
                        }
                        //whether ip is from proxy
                        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                        }
                        //whether ip is from remote address
                        else {
                            $ipaddress = $_SERVER['REMOTE_ADDR'];
                        }

                        if ($session_logon_allowed) {
                            $login_status = Account_User::login($auth_user->get_email(), session_id(), $_SESSION['token'], $ipaddress); # Error
                            $auth_user = Account_User::load_user_data($loginArr); // Reload After Login Session Update
                            $_SESSION['user'] = serialize($auth_user); // Store User Data In Session
                            //header("Location:debugreceive.php"); // Redirect Upon Success Authenticate
                            echo nl2br(PHP_EOL . "Success" . PHP_EOL);
                            echo $auth_user . "<br/>";
                            echo (int) $login_status;

                            # -- Clear Fields
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
                } else {
                    // When Validation Fails
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