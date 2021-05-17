<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
/* Load Config File */
require_once '../resources/config.php';
?>
<html>
    <head>
        <!-- Title -->
        <title>FYP-21-S2-24: Password Reset</title>
        <!-- Styling -->
        <?php require COMPONENT_PATH . '/bootstrap.php' ?>

    </head>
    <body>
        <!-- PHP Script -->
        <?php
        require_once ENTITIES_PATH . '/Account_User.php';
        require_once UTILS_PATH . '/Email.php';


        // -- Check If User Is Signed In (When Redirect or Load The Page) -- //
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if(isset($_GET['token']) && isset($_GET['email'])){
                $token = $_GET['token'];
                $email = $_GET['email'];
                // Cross Check email & token With Database
                # TBD
            }
        }
        ?>
        <!-- Display Message Info -->
        <div><?php echo $msg; ?></div>
        <!-- Reset Form (Ask For Email To Reset) -->
        <form method="post"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <p>Password Reset For <?php echo "" ?></p>
            <input type="password" name="password" required placeholder="Password"  value="<?php echo $resetArr['password']; ?>"/>
            <input type="password" name="confirmpassword" required placeholder="Confirm Password"  value="<?php echo $resetArr['confirmpassword']; ?>"/>
            <button type="submit" name="resetpassword" value="reset">Reset</button>
        </form>

    </body>
</html>






