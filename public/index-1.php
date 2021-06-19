<!-- This is web app's home page -->
<!DOCTYPE html>
<?php
/* Load Config File */
require_once '../resources/config.php';
?>
<html>
    <head>
        <!-- This Is The Home Page -->
        <title>FYP-21-S2-24: Home</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Styling -->
        <?php include COMPONENTS_PATH . '/bootstrap.php' ?>

    </head>
    <body>
        <!-- Logic & Validation -->
        <?php ?>

        <!-- HTML Page Design -->
        <div>
            <!-- Navigation -->  
            <?php include_once COMPONENTS_PATH . '/navbar.php' ?>

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