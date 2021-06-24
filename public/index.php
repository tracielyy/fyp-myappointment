<html lang="en">
    <?php
    session_start();
    require_once '../resources/config.php';
    require_once USER_MOD . '/Account_User.php';
    require_once USER_MOD . '/Patient.php';
    require_once APPT_MOD . '/Appointment_Record.php';
    require_once ENUMS_PATH . '/User_Type.php';

//require_once FUNCTIONS_PATH . '/PatientFunctions.php';
//require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';

    include TEMPLATES_PATH . '/bootstrap.php';
    ?>
    <?php $pageName = "homepage"; ?>

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
        <!-- BOOTSTRAP CDN -->
        <link rel="stylesheet" href="./css/homepage.css">

    <title>MyAppointment HomePage</title>
</head>

<body>
    <?php
    if (isset($_SESSION["user"])):
        $user = unserialize($_SESSION["user"]);
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
        <a href="./createappointment.php" class="btn btn-secondary" type="button"> Create an appointment </a>
    </section>
    <section id="health-snippets">
        <div class="row">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <!-- CARD START -->
                    <div class="card  mb-3 h-30" style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/175" class="img-fluid" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body ms-2">
                                    <h5 class="card-title">Why You Should Take Care of Your Body and Health</h5>
                                    <p class="card-text line-clamp">Health problems, even minor ones, can interfere with
                                        or even
                                        overshadow other aspects of your life. Even relatively minor health issues such
                                        as aches, pains, lethargy, and indigestion take a toll on your happiness and
                                        stress levels. One way to improve your ability to cope with stress and feel
                                        better is</p>
                                    <a href="./HealthPromotionDemo.php" class="stretched-link"><small class="text-muted">Click here to read more...</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- CARD END -->

                <div class="col">
                    <!-- CARD START -->
                    <div class="card mb-3 h-30 " style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/175" class="img-fluid" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body ms-2">
                                    <h5 class="card-title">Fast Remedies When Having Headache</h5>
                                    <p class="card-text line-clamp">Even relatively minor health issues such
                                        as aches, pains, lethargy, and indigestion take a toll on your happiness and
                                        stress levels. One way to improve your ability to cope with stress and feel
                                        better is</p>
                                    <p class="card-text"><small class="text-muted">Click here to read more...</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- CARD END -->

                <div class="col">
                    <!-- CARD START -->
                    <div class="card mb-3 h-30 " style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/175" class="img-fluid" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body ms-2">
                                    <h5 class="card-title">Reasons Why Sleep is the Most Important Part of the Day</h5>
                                    <p class="card-text line-clamp">Sleep can have a serious impact on your overall
                                        health and well-being. Make a commitment to get enough sleep at night. If you
                                        haven't gotten adequate sleep, you may be less productive, less mentally sharp,
                                        and otherwise more prone to the effects of stress.</p>
                                    <p class="card-text"><small class="text-muted">Click here to read more...</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- CARD END -->

                <div class="col">
                    <!-- CARD START -->
                    <div class="card mb-3 h-30 " style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/175" class="img-fluid" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body ms-2">
                                    <h5 class="card-title">Your Posture is Affecting Your Health</h5>
                                    <p class="card-text line-clamp">We've all heard the advice to eat right and
                                        exercise, but it can be difficult to fit in workouts around a busy schedule,
                                        particularly when you're feeling exhausted from stress. One effective strategy
                                        for making fitness a regular part of your life is to build an exercise habit
                                        around your other habits—either attach a workout to your morning routine, your
                                        lunchtime habits, or make it a regular part of your evening—you get the idea</p>
                                    <p class="card-text"><small class="text-muted">Click here to read more...</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- CARD END -->

                <div class="col">
                    <!-- CARD START -->
                    <div class="card mb-3 h-30 " style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/175" class="img-fluid" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body ms-2">
                                    <h5 class="card-title">Eat This Everyday, and You Will Feel Better</h5>
                                    <p class="card-text line-clamp">Indigestion take a toll on your happiness and
                                        stress levels. One way to improve your ability to cope with stress and feel
                                        better is</p>
                                    <p class="card-text"><small class="text-muted">Click here to read more...</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- CARD END -->

                <div class="col">
                    <!-- CARD START -->
                    <div class="card mb-3 h-30 " style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/175" class="img-fluid" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body ms-2">
                                    <h5 class="card-title">What To do When You are Bloated</h5>
                                    <p class="card-text line-clamp">Rather than eating right solely for the promise of
                                        looking better in your jeans, you should also make a commitment to eating foods
                                        that will boost your energy levels and keep your system running smoothly. This
                                        is because what you eat can not only impact your short-term and long-term
                                        health, it can affect your stress levels.</p>
                                    <p class="card-text"><small class="text-muted">Click here to read more...</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- CARD END -->

            </div>
        </div>
        <div class="text-center mt-3">
            <button class="btn btn-outline-primary btn-lg" type="button">More Health Information and Tips </button>
        </div>

    </section>
    <section id="footer">
        <hr class="mb-4">
        <div class="card-group shadow">
            <div class="card mb-3 " style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <i class="fas fa-user fa-4x opt-icon"></i>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">About Us</h5>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3 " style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <i class="fas fa-phone-alt fa-4x opt-icon"></i>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Contact Us</h5>
                            <p class="card-text">Show the number to contact us.<br><br> +65 5016 5775 <br> +65 5016 5775
                                <br> +65 5016 5775
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3 " style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <i class="fas fa-clock fa-4x opt-icon"></i>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Time Availability</h5>
                            <p class="card-text">Shows the time of most medical facilities</p>
                            <div class="row">
                                <div class="col">
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