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
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';

require_once FACILITY_MOD . '/Medical_Facility.php';

/*
 *  VIEW INDIVIDUAL FACILITY DETAILS
 */

if (!isset($_SESSION['user'])):
    header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
else:
    $user = unserialize($_SESSION["user"]);
    $user_type = $user->get_usertype();
    $user_email = $user->get_email();

    // Check If User Is Super Admin
    if (!User_Type::check_user_type(User_Type::SUPER_ADMIN, $user_type)):
        header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
    else:
        if ($_SERVER["REQUEST_METHOD"] == "GET"):

            $validArr = array();

            function valid_vars(string $id): bool|Medical_Facility {
                $facility_obj = Medical_Facility::retrieve_facility_by_id($id);
                if ($facility_obj === null):
                    return false;
                endif;
                return $facility_obj;
            }
            ?><!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <title>View Facility</title>
                    <!-- fontawesome -->
                    <script
                        src="https://kit.fontawesome.com/dcfd5ba5e7.js"
                        crossorigin="anonymous"
                    ></script>
                    <!-- google fonts -->
                    <link rel="preconnect" href="https://fonts.googleapis.com" />
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap" rel="stylesheet"  />
                    <!-- bootstrap cdn link -->
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"  />
                    <!-- bootstrap data table -->
                    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" />
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
                        .innerCard{
                            background-color: #eeeded;
                        }
                        .editAdm{
                            color: #198754;
                        }
                        .editAdm:hover{
                            color: #292b2c;
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
                            .navbar-brand{
                                margin-left: auto;
                                margin-right: auto;
                            }
                        }
                    </style>
                </head>
                <body>
                    <?php require_once TEMPLATES_PATH . "/sadmin-navbar.php"; ?>
                    <?php
                    if (!isset($_GET['fid'])):
                        ?>
                        <!-- SHOW INVALID PAGE -->
                        <main class="mt-5 pt-3">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="alert alert-danger" role="alert">
                                        Invalid URL
                                    </div>
                                </div>
                            </div>
                        </main>
                        <?php
                    else:
                        $fid = $_GET['fid'];
                        # Check if the id exist in database for edit
                        $facility_info = valid_vars($fid);
                        if (!$facility_info):
                            ?>
                            <main class="mt-5 pt-3">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="alert alert-danger" role="alert">
                                            The facility you are looking for does not exist.
                                        </div>
                                    </div>
                                </div>
                            </main>
                            <?php
                        else: /* If The Variables Exists In The Database */
                            $facility_admin = Facility_Admin::retrieve_admin_by_facilityid($facility_info->get_facilityid());
                            ?>
                            <!-- main section starts here -->
                            <main class="mt-5 pt-3">
                                <h2 class="text-center my-2"> Medical Facility Details </h2>
                                <hr class="bg-dark w-75 ms-auto me-auto">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card ms-auto me-auto outerCard" style="max-width: 55rem;">
                                            <div class="card-body">
                                                <div class="card text-dark innerCard mb-3">
                                                    <div class="card-title ms-2 mt-2">
                                                        <!-- Facility Name & 24 Hours -->
                                                        <h4 style="font-weight: 600; font-size: 1.5rem;">
                                                            <span class="align-middle">
                                                                <?php echo $facility_info->get_facilityname(); ?>
                                                            </span>
                                                            <?php if ($facility_info->get_operatinghours()->get_is24hours()): ?>
                                                                <span class="badge bg-success fw-normal">24 Hours</span>
                                                            <?php endif; ?>
                                                        </h4>
                                                        <!-- Facility Admin Name -->
                                                        <div class="d-grid gap-2 d-md-flex justify-content-md-start" style=" margin-top: 10px;">
                                                            <input type="text" readonly class="form-control-plaintext text-muted" name="adminName" value="<?php echo ($facility_admin == null) ? " - " : $facility_admin->get_adminname(); ?>" style="font-weight: 600;">
                                                        </div>
                                                    </div>
                                                    <hr class="ms-2" style="max-width: 60%;">
                                                    <div class="card-body">
                                                        <div class="mb-3 row">
                                                            <label for="address" class="col-sm-2 col-form-label">Address: </label>
                                                            <div class="col-sm-10">
                                                                <input type="text" readonly class="form-control-plaintext" id="staticAddress" placeholder="<?php echo $facility_info->get_address(); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label for="contact" class="col-sm-2 col-form-label">Contact: </label>
                                                            <div class="col-sm-10">
                                                                <input type="text" readonly class="form-control-plaintext" id="staticContact" placeholder="<?php echo $facility_info->get_contactnumber(); ?>">
                                                            </div>
                                                        </div>
                                                        $facility_info
                                                        <?php
                                                        // Check The Operating Hours (If It Is 24 hours) // 
                                                        if (!$facility_info->get_operatinghours()->get_is24hours()):
                                                            ?>
                                                            <div class="mb-3 row">
                                                                <label for="openinghour" class="col-sm-2 col-form-label">Opening Hour: </label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" placeholder="<?php echo $facility_info->get_operatinghours()->get_openinghour(); ?>" onfocus="(this.type = 'time')" style="margin-top: 5px; border: 1px solid #eeeded; background-color: #eeeded;">
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 row">
                                                                <label for="closinghour" class="col-sm-2 col-form-label">Closing Hour: </label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" placeholder="<?php echo $facility_info->get_operatinghours()->get_closinghour(); ?>" onfocus="(this.type = 'time')" style="margin-top: 5px; border: 1px solid #eeeded; background-color: #eeeded;">
                                                                </div>
                                                            </div>
                                                            <?php
                                                        endif;
                                                        ?>
                                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end" style=" margin-top: 10px;">
                                                            <a href="<?php echo SADMIN_WEB . "/edit/facility.php?fid=" . $facility_info->get_facilityid(); ?>" class="btn btn-success me-md-2 mr-2">
                                                                <span><i class="fas fa-pen-square"></i></span>
                                                                <span>Edit</span>
                                                            </a>
                                                            <a href="<?php echo SADMIN_WEB; ?>" class="btn btn-danger" id="delBtn">
                                                                <span><i class="fas fa-times"></i></span>
                                                                <span>Cancel</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </main>
                            <!-- main ends here -->

                            <!-- bootstrap js link -->
                            <script
                                src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                                integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
                                crossorigin="anonymous"
                            ></script>
                            <!-- js code for the bootstrap tooltip -->
                            <script>
                                                                        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                                                                        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                                                                            return new bootstrap.Tooltip(tooltipTriggerEl);
                                                                        });
                            </script>
                        </body>
                    </html>
                <?php
                endif; # -- END FOR VALID VARS
            endif; # -- END GET ID CHECK ISSET
        endif; # -- END GET 
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>