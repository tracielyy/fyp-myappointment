<?php
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';

require_once ENUMS_PATH . '/Health_Info_Type.php';
require_once HINFO_MOD . '/Health_Info.php';
require_once UTIL_MOD . '/StringUtils.php';
require_once UTIL_MOD . '/ArrayCreation.php';

require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Super_Admin.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Patient.php';

/*
 *  FACILITY ADMIN LANDING PAGE --- (Dashboard)
 */
if (!isset($_SESSION['user'])):
    header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
else:
    $user = unserialize($_SESSION["user"]);
    $user_type = $user->get_usertype();
    $user_email = $user->get_email();
    // Check If User Is Facility Admin
    if (!User_Type::check_user_type(User_Type::FACIILITY_ADMIN, $user_type)):
        header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
    else:
        $facility = $user->get_facility();
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>Admin HomePage</title>
                <!-- fontawesome -->
                <script src="https://kit.fontawesome.com/dcfd5ba5e7.js" crossorigin="anonymous"></script>
                <!-- google fonts -->
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap" rel="stylesheet" />
                <!-- bootstrap cdn link -->
                <link
                    href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
                    rel="stylesheet"
                    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
                    crossorigin="anonymous"
                    />
                <!-- bootstrap data table -->
                <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css"/>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
                <!-- Prevent Form Resubmission -->
                <script>
                    if (window.history.replaceState) {
                        window.history.replaceState(null, null, window.location.href);
                    }
                </script>
                <!-- jQuery -->
                <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
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
                    }
                    @media (max-width: 992px) {
                        /* active doctor */
                        .AP {
                            margin-right: auto;
                        }
                        /* active patients */
                        .AD {
                            margin-left: auto;
                        }
                    }
                </style>
            </head>
            <body>
                <!-- NavBar  -->
                <?php require_once TEMPLATES_PATH . "/fadmin-navbar.php"; ?>
                <!-- Current Page Contents -->
                <main class="mt-5 pt-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 text-center fw-700 fs-1">Dashboard</div>
                            <div class="col-md-12 text-muted text-center fw-700">
                                Facility admin @ <span id="facilityName"><?php echo StringUtils::get_acronym($facility->get_facilityname()); ?></span>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-6 mb-2 ms-auto me-auto AP" style="max-width: 60%; max-height: fit-content">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span><i class="fas fa-procedures fa-3x my-2"></i></span>
                                        <p class="card-title fw-900 large">Active Patients</p>
                                        <h6 class="card-text large fw-900 fs-1">2</h6>
                                        <a href="#PatientList" class="btn btn-outline-dark">View Patients</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2 ml-3 me-auto AD"  style="max-width: 60%; max-height: fit-content">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span><i class="fas fa-hospital-user fa-3x my-2"></i></span>
                                        <p class="card-title fw-900 large">Active Doctors</p>
                                        <h6 class="card-text large fw-900 fs-1">6</h6>
                                        <a href="<?php echo FADMIN_WEB . "/views/doctors.php" ?>" class="btn btn-outline-dark">View Doctors</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 mb-2 ml-3 ms-auto">
                                <div class="card text-center">
                                    <div class="card-header"><span id="patientChart">Patients</span></div>
                                    <div class="card-body">
                                        <canvas id="myChart" width="200" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2 ml-3 me-auto">
                                <div class="card text-center">
                                    <div class="card-header"><span id="docChart">Doctors</span></div>
                                    <div class="card-body">
                                        <canvas id="myChart1" width="200" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="PatientList">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        Patient List
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <td>First Name</td>
                                                        <td>Last Name</td>
                                                        <td>NRIC</td>
                                                        <td>DOB</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Leo</td>
                                                        <td>Jones</td>
                                                        <td>S9497618I</td>
                                                        <td>09-02-2005</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <!-- main ends here -->
                <script>
                    $('#nav-dashboard').addClass('active');
                </script>
            </body>
        </html>
    <?php
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>