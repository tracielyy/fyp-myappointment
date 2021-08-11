<?php
/*
 *  @author: tracieqwynn
 */
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';

require_once FACILITY_MOD . '/Medical_Facility.php';

/*
 *  VIEW FACILITY ADMIN PROFILE DETAILS
 */
if (!isset($_SESSION['user'])):
    header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
else:
    $user = unserialize($_SESSION["user"]);
    $user_type = $user->get_usertype();
    $user_email = $user->get_email();

// Check If User Is Super Admin
    if (!User_Type::check_user_type(User_Type::FACIILITY_ADMIN, $user_type)):
        header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
    else:
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>View My Profile</title>
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
                <!-- bootstrap data table -->
                <link
                    rel="stylesheet"
                    href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css"
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
                <?php require_once TEMPLATES_PATH . "/fadmin-navbar.php"; ?>

                <!-- main section starts here -->
                <main class="mt-5 pt-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card ms-auto me-auto outerCard" style="max-width: 55rem;">
                                <div class="card-body">
                                    <div class="card text-dark innerCard mb-3">
                                        <div class="card-title ms-2 mt-2">
                                            <h4 class="text-muted" style="font-weight: 600; font-size: 1.5rem;">Profile</h4>
                                        </div>
                                        <hr class="ms-2" style="max-width: 60%;">
                                        <div class="card-body">
                                            <div class="mb-3 row">
                                                <label for="SuperadminID" class="col-sm-2 col-form-label">Admin ID: </label>
                                                <div class="col-sm-10">
                                                    <input type="text" readonly class="form-control-plaintext" value="<?php echo $user->get_adminid(); ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="adminname" class="col-sm-2 col-form-label">Admin Name: </label>
                                                <div class="col-sm-10">
                                                    <div class="d-grid gap-1 d-md-flex justify-content" >
                                                        <div id="adminName" class="py-1 me-1"><?php echo $user->get_adminname(); ?></div>
                                                        <a href="<?php echo FADMIN_WEB . "/edit/admin-name.php"; ?>" class="me-md-2 mr-2 editAdm" data-bs-toggle="tooltip" data-bs-placement="right" title="Edit Admin">
                                                            <span><i class="fas fa-pen-square fa-2x"></i></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="email" class="col-sm-2 col-form-label">Email: </label>
                                                <div class="col-sm-10">
                                                    <input type="email" class="form-control-plaintext" value="<?php echo $user->get_email(); ?>" id="email">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="password" class="col-sm-2 col-form-label">Password: </label>
                                                <div class="col-sm-10">
                                                    <a href="<?php echo FADMIN_WEB . "/edit/admin-password.php"; ?>"  class="btn btn-dark me-md-2 mr-2">
                                                        <span><i class="fas fa-user-edit"></i></span>
                                                        <span>Change Password</span>
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
                <script>
                    $('#nav-edit-profile').addClass('active');
                </script>
                <!-- bootstrap js link -->
                <script
                    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
                    crossorigin="anonymous"
                ></script>
            </script>
        </body>
        </html>
    <?php
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>