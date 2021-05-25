<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
/* Load Config File */
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once ENUMS_PATH . '/User_Type.php';
?>

<html>
    <head>
        <!-- Title -->
        <title>FYP-21-S2-24</title>
        <!-- Styling -->
        <?php require COMPONENT_PATH . '/bootstrap.php' ?>

    </head>
    <body>
        <!-- Logic Validation -->
        <?php
        $search = array(
            "searchfacility" => ""
        );
        if ($_SERVER["REQUEST_METHOD"] == "POST") {



            /* Load Data to Array */
            foreach ($_POST as $key => $value) {
                if (isset($search[$key])) {
                    $search[$key] = htmlspecialchars($value); // Containing Any Values To Reset Password
                    $validArr[$key] = False; // Set All Field Validation Check As False
                }
            }
            echo $search['searchfacility'];
        }
        ?>
        <!-- Facility Search Form -->
        <form method="post"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <p>Enter Facility Name</p>
            <input type="text" name="searchfacility" required placeholder="Search Facility"  value="<?php echo $search['searchfacility']; ?>"/>
            <button type="submit" name="search" value="reset">Search</button>
        </form>
    </body>
</html>