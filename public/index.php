<!--
   Developed By FYP-21-S2-24
-->
<!-- This is web app's home page -->
<?php
/* Load Config File */
require_once '../resources/config.php';
?>
<html>
    <head>
        <!-- This Is The Home Page -->
        <title>FYP-21-S2-24: Home</title>


        <!-- Styling -->
        <?php include COMPONENT_PATH . '/bootstrap.php' ?>

    </head>
    <body>
        <!-- Logic & Validation -->
        <?php ?>

        <!-- HTML Page Design -->
        <div>
            <!-- Navigation -->  
            <?php include_once COMPONENT_PATH . '/navbar.php' ?>

            <!-- Test Echo -->
            <?php echo "index.php displayed correctly"; ?>
        </div>

        <div>
            <?php //include './components/test.php'  ?>
        </div>

        <?php // include './components/scripts.php'  ?>
        <!-- Footer -->
    </body>
</html>