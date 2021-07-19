<!DOCTYPE html>
<html lang="en">

<head>

    <?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require '../vendor/autoload.php';
require_once EMAIL_MOD . '/Email.php';
require_once UTIL_MOD . '/StringUtils.php';
require_once UTIL_MOD . '/Regex.php';
require_once AUTH_MOD . '/Authentication.php';
require_once USER_MOD . '/Account_User.php';


//require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';
    ?>
    <style>
    <?php include './css/medicaldashboard.css';
    ?>
    </style>

    <?php
    include TEMPLATES_PATH . '/bootstrap.php';
   
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- font awesome cdn -->
    <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
</head>

<body onload="openTab(event, 'Account')">

    <?php

// if (isset($_SESSION["user"])):
//     $user = unserialize($_SESSION["user"]);
    
//     if (User_Type::check_user_type(User_Type::MEDICAL_PERSONNEL, $user_type)):
//         $email['credentials']['email'] = $user_email;
    
        
//         include COMPONENTS_PATH . '/navbar-loggedin.php';

// -- Check If User Is Signed In (When Redirect or Load The Page)
if (isset($_SESSION["user"])):

    # "Unboxin" User Information
    $user = unserialize($_SESSION["user"]);
    $user_email = $user->get_email();
    $user_type = $user->get_usertype();
    $email['credentials']['email'] = $user_email;

if (User_Type::check_user_type(User_Type::PATIENT, $user_type)):
    include_once TEMPLATES_PATH . '/navbar-loggedin.php';
?>



    <div class="row bg-light py-4">
        <div class="row bg-light">
            <div class="col-xs-3 col-md-2 mx-md-1 mx-lg-0" style="padding-left: 140px;">
                <img src="https://via.placeholder.com/100" class="rounded shadow float-start" alt="...">
            </div> <!-- col -->

            <div class="col-xs-8 col-md-6">
                <h1 class="display-6"> <?php echo $user->get_fullname(); ?> </h1>
                <h1 class="lead"> Gender: <?php echo $user->get_gender(); ?> </h1>
                <h1 class="lead"> Date of Birth: <?php echo $user->get_dob(); ?> </h1>
            </div> <!-- col -->

        </div>
    </div>

    <div class="container-fluid mt-3">
        <div class="tab">
            <button class="tablinks top" onclick="openTab(event, 'Account')" id="defaultOpen"><i
                    class="fas fa-user-cog tab-icon"></i>Account </button>
            <button class="tablinks" onclick="openTab(event, 'Profile')"><i
                    class="fas fa-user-circle tab-icon"></i>Profile </button>
            <button class="tablinks" onclick="openTab(event, 'Help')"><i
                    class="fas fa-question-circle tab-icon"></i>Help </button>
        </div>

        <div id="Account" class="tabcontent shadow rounded">
            <div class="container chart-container mt-3">
                <h3 class="text-center mb-4">Account</h3>

                <form class="form-inline">
                <input type="text" class="form-control w-25" id="staticEmail2" value="email@example.com">
                <button type="submit" class="btn btn-primary mb-2">Confirm identity</button> 
                </form>
            </div>
        </div>

        <div id="Profile" class="tabcontent shadow rounded">
            <div class="container mt-3">
                <h3 class="text-center">Profile</h3>
                <div id="wrapper"></div>
            </div>
        </div>

        <div id="Help" class="tabcontent shadow rounded">
            <div class="container mt-3">
                <h3 class="text-center">Help</h3>
                <div>
                    <canvas id="myChart" width="400" height="120"></canvas>
                </div>
            </div>
        </div>



        <div id="Settings" class="tabcontent shadow rounded">
            <div class="container mt-5">
                <h3 class="text-center">Shift</h3>
                <p>Shows to set the timing of work</p>
            </div>
        </div>

    </div>


    <?php endif; 
endif;?>
</body>
<script>
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>

</html>