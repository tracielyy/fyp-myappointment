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
require_once FAQ_MOD . '/Faq.php';

/*
 *  VIEW ALL FAQS 
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
        $all_faqs = Faq::retrieve_all_faqs();
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>Admin HomePage</title>
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

                <!-- main section starts here -->
                <main class="mt-5 pt-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card ms-auto me-auto outerCard" style="max-width: 55rem;">
                                <div class="card-body">
                                    <h4 class="text-muted text-center small">View FAQ's</h4>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end my-2">
                                        <a href="<?php echo SADMIN_WEB . "/create/new-faq.php"; ?>" class="btn btn-warning me-2" style="border-radius: 18px;">
                                            <i class="fas fa-plus-circle"></i>
                                            <span>Add FAQ</span>
                                        </a>
                                    </div>
                                    <?php
                                    foreach($all_faqs as $faq):
                                    ?>
                                    <div class="card text-dark innerCard mb-3">
                                        <div class="card-title ms-2 mt-2">
                                            <h4 style="font-weight: 600; font-size: 1.5rem;"><?php echo $faq->get_question();?></h4>
                                        </div>
                                        <hr class="ms-2" style="max-width: 60%;">
                                        <div class="card-body">
                                            <div class="mb-3 row">
                                                <label for="answer" class="col-sm-2 col-form-label">Answer: </label>
                                                <div class="col-sm-10">
                                                    <textarea name="answer" readonly class="form-control" cols="30" rows="8" style="background-color: #eeeded;"><?php echo $faq->get_answer();?></textarea>
                                                </div>
                                            </div>
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end" style=" margin-top: 10px;">
                                                <a href="<?php echo SADMIN_WEB . "/edit/faq.php?id=". $faq->get_id(); ?>" class="btn btn-success me-md-2 mr-2">
                                                    <span><i class="fas fa-edit"></i></span>
                                                    <span>Edit</span>
                                                </a>
                                                <button class="btn btn-danger" id="delBtn" type="button" data-bs-toggle="modal" data-bs-target="#delModal">
                                                    <span><i class="fas fa-trash"></i></span>
                                                    <span>Delete</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <!-- main ends here -->
                <!-- modal starts here -->
                <div class="modal fade" id="delModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" style="margin-left: 35%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete FAQ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger" role="alert">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Warning this action is irrevocable
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Confirm</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal ends here -->

                <!-- bootstrap js link -->
                <script
                    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
                    crossorigin="anonymous"
                ></script>
                <!-- js for the modal to work -->
                <script>
                    $('#nav-faq').addClass('active');
                    var myModal = document.getElementById('myModal');
                    var myInput = document.getElementById('myInput');

                    myModal.addEventListener('shown.bs.modal', function () {
                        myInput.focus();
                    });
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                </script>
            </body>
        </html>
    <?php
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>