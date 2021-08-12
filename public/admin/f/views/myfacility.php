<?php
/*
 *  @author: tracieqwynn
 */

session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once TIME_MOD . '/Time.php';
require_once UTIL_MOD . '/Regex.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';
require_once DB_MOD . '/DbStorage.php';

require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';

require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';

require_once FACILITY_MOD . '/Medical_Facility.php';
require_once SECURE_MOD . '/ValidateIC.php';

/*
 *      VIEW FACILITY INFORMATION
 */
if (!isset($_SESSION['user'])):
    header("Location:/"); # -- REDIRECT BACK TO THE HOME PAGE
else:
    $user = unserialize($_SESSION["user"]);
    if ($user->get_usertype() !== User_Type::FACIILITY_ADMIN):
        header("Location:/"); # -- REDIRECT BACK TO THE HOME PAGE
    else: # -- ONLY ALLOW FACILITY ADMIN

        $facility = $user->get_facility();
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>Manage Hospital</title>
                <!-- fontawesome -->
                <script
                    src="https://kit.fontawesome.com/dcfd5ba5e7.js"
                    crossorigin="anonymous"
                ></script>
                <!-- google fonts -->
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                <link
                    href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap"
                    rel="stylesheet"
                    />
                <!-- bootstrap cdn link -->
                <link
                    href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
                    rel="stylesheet"
                    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
                    crossorigin="anonymous"
                    />
                <link
                    rel="stylesheet"
                    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"
                    />
                <!-- Prevent Form Resubmission -->
                <script>
                    if (window.history.replaceState) {
                        window.history.replaceState(null, null, window.location.href);
                    }
                </script>
                <!-- jQuery -->
                <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
                <?php include TEMPLATES_PATH . '/bootstrap.php'; ?>

                <style>
                    body {
                        margin: 0;
                        padding: 0;
                        background-color: #eeeded;
                        font-family: "Poppins", sans-serif;
                    }
                    /* defining some variables for the offcanvas */
                    :root {
                        --offcanvas-width: 270px;
                        --topNavBarHeight: 56px;
                    }
                    .sidebar-nav {
                        width: var(--offcanvas-width);
                    }
                    .sidebar-link{
                        display: flex;
                        align-items: center;
                    }
                    .sidebar-link .right-icon{
                        display: inline-flex;
                    }
                    .sidebar-link[aria-expanded="true"] .right-icon{
                        transform: rotate(180deg);
                        transition: all ease 0.25s;
                    }
                    /* make the offcanvas visible on the large screens */
                    @media (min-width: 992px) {
                        body {
                            overflow: auto !important;
                        }

                        .Offcanvas-backdrop::before {
                            display: none;
                        }
                        .sidebar-nav {
                            transform: none;
                            visibility: visible !important;
                            top: var(--topNavBarHeight);
                            height: calc(100% - var(--topNavBarHeight));
                        }
                        main {
                            margin-left: var(--offcanvas-width);
                        }
                        #modalBox {
                            margin-left: var(--offcanvas-width);
                        }
                    }
                </style>
            </head>
            <body>
                <!-- NavBar  -->
                <?php require_once TEMPLATES_PATH . "/fadmin-navbar.php"; ?>
                <!-- Current Page (View Medical Facility) -->
                <main class="mt-5 p-3">
                    <!-- page title -->
                    <div class="container-fluid mb-4">
                        <div class="row">
                            <div class="col-md-12 text-center fw-700 fs-1">View Facility Details</div>
                            <div class="col-md-12 text-muted text-center fw-700">
                                Facility admin @ <span id="facilityName"><?php echo StringUtils::get_acronym($facility->get_facilityname()); ?> </span>
                            </div>
                        </div>
                    </div>
                    <!-- page title ends here -->
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-body mx-5 my-2">
                                <!-- Facility Name -->
                                <h4 style="font-weight: 600; font-size: 1.5rem;">
                                    <span class="align-middle">
                                        <?php echo $facility->get_facilityname(); ?>
                                    </span>
                                    <?php if ($facility->get_operatinghours()->get_is24hours()): ?>
                                        <span class="badge bg-success fw-normal">24 Hours</span>
                                    <?php endif; ?>
                                </h4>
                                <hr class="ms-2" style="max-width: 60%;">
                                <div class="mb-3 row">
                                    <label for="address" class="col-sm-2 col-form-label">Address: </label>
                                    <div class="col-sm-10">
                                        <input type="text" readonly class="form-control-plaintext" placeholder="<?php echo $facility->get_address(); ?>">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="contact" class="col-sm-2 col-form-label">Contact: </label>
                                    <div class="col-sm-10">
                                        <input type="text" readonly class="form-control-plaintext"  placeholder="<?php echo $facility->get_contactnumber(); ?>">
                                    </div>
                                </div>
                                <?php
                                // Check The Operating Hours (If It Is 24 hours) // 
                                if (!$facility->get_operatinghours()->get_is24hours()):
                                    ?>
                                    <div class="mb-3 row">
                                        <label for="openinghour" class="col-sm-2 col-form-label">Opening Hour: </label>
                                        <div class="col-sm-10">
                                            <input type="text" placeholder="<?php echo $facility->get_operatinghours()->get_openinghour(); ?>" onfocus="(this.type = 'time')" style="margin-top: 5px; border: 1px solid #eeeded; background-color: #eeeded;">
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="closinghour" class="col-sm-2 col-form-label">Closing Hour: </label>
                                        <div class="col-sm-10">
                                            <input type="text" placeholder="<?php echo $facility->get_operatinghours()->get_closinghour(); ?>" onfocus="(this.type = 'time')" style="margin-top: 5px; border: 1px solid #eeeded; background-color: #eeeded;">
                                        </div>
                                    </div>
                                    <?php
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- main body ends here -->
            <script>
                $('#nav-facility').addClass('active');
                $('#nav-facility-details').addClass('active');
            </script>
        </body>
        </html>
    <?php
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>
