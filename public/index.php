<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';

require_once APPT_MOD . '/Appointment_Record.php';
require_once ENUMS_PATH . '/User_Type.php';

require_once ENUMS_PATH . '/Health_Info_Type.php';
require_once HINFO_MOD . '/Health_Info.php';
require_once UTIL_MOD . '/StringUtils.php';

/*
 * HOME PAGE (LANDING PAGE)
 */

include TEMPLATES_PATH . '/bootstrap.php';
$pageName = "homepage";
$usertype = "Guest";
$all_health_articles = Health_Info::retrieve_all_healthinfo();
// echo "<pre>";
// echo var_dump($all_health_articles);
// echo "</pre>";
$healtharticles_count = count($all_health_articles);

$healtharticles_count = $healtharticles_count % 6;

if ($healtharticles_count > 3 && $healtharticles_count < 6)
{
    $index = 3;
} else if($healtharticles_count >= 6)
{
    $index = 6;
} else 
{
    $index = $healtharticles_count;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
    <!-- BOOTSTRAP CDN -->
    <link rel="stylesheet" href="./css/homepage.css">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <title>MyAppointment HomePage</title>
</head>

<body>
    <?php
        if (isset($_SESSION["user"])):
            $user = unserialize($_SESSION["user"]);
            $usertype = $user->get_usertype();
            include TEMPLATES_PATH . '/navbar-loggedin.php';
        else:
            include TEMPLATES_PATH . '/navbar.php';
        endif;
        ?>
    <!-- <img src="https://png.pngtree.com/template/20190316/ourmid/pngtree-medical-health-logo-image_79595.jpg"
                                    class="health-img" alt="..."> -->
    <section id="Find-Clinic">
        <h2 class="find-clinic-title display-5"><b>Your one stop solution <br> for your medical appointment.</b></h2>
        <h3 class="find-clinic-title display-6" style="margin-top: 0px;">Book an appointment now.</h3>
        <?php if ($usertype == "Patient"): ?>
        <a href="appointment/create.php" class="btn btn-secondary" type="button"> Create an appointment </a>
        <?php endif;?>
        <?php if ($usertype == "Guest"): ?>
        <a href="login" class="btn btn-secondary" type="button"> Create an appointment </a>
        <?php endif;?>
    </section>
    <section id="health-snippets">
        <div class="row">
            <div class="row row-cols-1 row-cols-md-3 g-4">
               <?php 
               
               for($i = 0; $i < $index; $i++)
               {
                $desc = substr($all_health_articles[$i]->get_descriptions(),0,100);
                $desc = trim(preg_replace('/\s+/', ' ', $desc));
                $type = $all_health_articles[$i]->get_type();
                if ($type == 'World Health Notice')
                {
                    $badge = '<span class="badge bg-warning">World Health Notice</span>';
                    $icon = '<i class="fas fa-shield-virus fa-7x card-icon"></i>';
                } else if ($type == "Doctor's Advice")
                {
                    $badge = '<span class="badge bg-primary">Doctor\'s Advice</span>';
                    $icon = '<i class="fas fa-user-md fa-7x card-icon"></i>';
                }   
                else if($type == 'Health Tips')
                {
                    $badge = '<span class="badge bg-success">Health Tips</span>';
                    $icon = '<i class="fas fa-plus-square fa-7x card-icon"></i>';
                }
                echo '<div class="col">';
                echo '<div class="card shadow rounded1 mb-3 h-30" style="max-width: 540px; ">';
                echo '<div class="row g-0">';
                echo '<div class="col-xl-4 col-lg-12 col-md-12 col-xs-12">';
                echo $icon.'</div>';
                echo '<div class="col-xl-8 col-lg-0 col-md-0 col-xs-0">';
                echo '<div id="#cardcontainer" class="card-body ms-2" style="min-height:153px;">';
                echo '<h5 class="card-title">'.$all_health_articles[$i]->get_title().'</h5>';
                echo $badge;
                echo '<p class="card-text line-clamp mt-3">'.$desc.'</p>';
                echo '</div>';
                echo '<div class="card-body ms-2">';
                echo '<a href="./healthlist.php" class="card-link stretched-link"><small class="text-muted">Click here to read more...</small></a>'; //NEED TO CHANGE TO REAL URL
                echo '</div></div></div></div></div>';
               };?>

            </div>
        </div>
        <div class="text-center mt-3">
            <a class="btn btn-outline-primary btn-lg" href="healthlist.php" type="button">More Health Information and Tips </a>
        </div>

    </section>
    <section id="footer">
        <hr class="mb-4">
        <div class="container-fluid">

            <div class="card-group mb-3 shadow" >

                <div class="card" style="min-height:205px">
                    <div class="row g-0">

                        <div class="col-md-4">
                            <i class="fas fa-user fa-4x opt-icon"></i>
                        </div>

                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">About Us</h5>
                                <p class="card-text">We are an appointment booking service, to help you book appointment
                                    the easiest and fastest possible to your desired registered clinic! </p>
                                <a href="">FAQ</a> <br />
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card" style="min-height:205px">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <i class="fas fa-phone-alt fa-4x opt-icon"></i>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Contact Us</h5>
                                <p class="card-text">Show the number to contact us.<br><br> +65 5016 5775 <br> +65 5016
                                    5775
                                    <br> +65 5016 5775
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" style="min-height:205px">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <i class="fas fa-clock fa-4x opt-icon"></i>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Time Availability</h5>
                                <p class="card-text">Shows the time of most medical facilities</p>
                                <div class="row">
                                    <div class="col-auto">
                                        <p class="card-text">Monday - Tuesday</p>
                                        <p class="card-text">Saturday - Sunday</p>
                                    </div>
                                    <div class="col">
                                        <p class="card-text">6:00 AM - 11:00 PM</p>
                                        <p class="card-text">6:30 AM - 12:00 PM</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    </section>
</body>

</html>

<!--
<div class="container-md">
            <div class="row row-cols-lg-4">
                <div class="col mb-4">
                    <div class="card">
                        <i class="fas fa-user fa-4x"></i>
                        <div class="card-body">
                            <h5 class="card-title">user information</h5>
                            <p class="card-text">some information for the end-users.</p>
                        </div>
                    </div>
                </div>
                <div class="col mb-4">
                    <div class="card">
                        <i class="fas fa-phone-alt fa-4x"></i>
                        <div class="card-body">
                            <h5 class="card-title">Contact information</h5>
                            <p class="card-text">some contact details for the end users.</p>
                        </div>
                    </div>
                </div>
                <div class="col mb-4">
                    <div class="card">
                        <i class="fas fa-clock fa-4x"></i>
                        <div class="card-body">
                            <h5 class="card-title">Working Hours</h5>
                            <p class="card-text">some information about the working hours</p>
                        </div>
                    </div>
                </div>
            </div> -->