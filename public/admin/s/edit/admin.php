
<?php
/*
 *  @author: tracieqwynn
 */
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once ENUMS_PATH . '/Health_Info_Type.php';
require_once HINFO_MOD . '/Health_Info.php';

require_once UTIL_MOD . '/StringUtils.php';
require_once UTIL_MOD . '/Regex.php';
require_once UTIL_MOD . '/ArrayCreation.php';

require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';
require_once DB_MOD . '/DbStorage.php';

require_once EMAIL_MOD . '/EmailTemplate.php';

require_once FACILITY_MOD . '/Medical_Facility.php';

/*
 *      EDIT FACILITY ADMIN (via facility)
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
        if ($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER['REQUEST_METHOD'] == "POST"):

            $validArr = array();

            function valid_vars(string $id): bool|Facility_Admin {
                $facility_admin = Facility_Admin::retrieve_admin_by_facilityid($id);

                if ($facility_admin === null):
                    return false;
                endif;
                return $facility_admin;
            }

            $admin = array(
                'profile' => array(
                    'adminname' => ''
                ),
                'credentials' => array(
                    'email' => ''
                )
            );
            ?><!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <title>Edit Facility Admin</title>
                    <!-- fontawesome -->
                    <script src="https://kit.fontawesome.com/dcfd5ba5e7.js"  crossorigin="anonymous"></script>
                    <!-- google fonts -->
                    <link rel="preconnect" href="https://fonts.googleapis.com" />
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap" rel="stylesheet"/>
                    <!-- bootstrap cdn link -->
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"  crossorigin="anonymous"/>
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
                    <!-- Nav Bar -->
                    <?php require_once TEMPLATES_PATH . "/sadmin-navbar.php"; ?>
                    <?php
                    if (!isset($_GET['fid'])):
                        ?>
                        <!-- SHOW INVALID PAGE -->
                        <main class="mt-5 pt-2">
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
                        $facility_admin = valid_vars($fid);
                        if (!$facility_admin):
                            ?>
                            <main class="mt-5 pt-2">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="alert alert-danger" role="alert">
                                            This facility does not have a facility admin
                                        </div>
                                    </div>
                                </div>
                            </main>
                            <?php
                        else: /* If The Variables Exists In The Database */
                            $facility = $facility_admin->get_facility();

                            if ($_SERVER["REQUEST_METHOD"] == "GET"):
                                $admin_details = array(
                                    'credentials' => array(
                                        'email' => $facility_admin->get_email(),
                                    ),
                                    'profile' => array(
                                        'adminname' => $facility_admin->get_adminname()
                                    )
                                );

                                $validArr = array();
                            else: # -- POST 

                                $admin_details = array(
                                    'credentials' => array(
                                        'email' => '',
                                    ),
                                    'profile' => array(
                                        'adminname' => ''
                                    )
                                );

                                // loop and store all the information into an array
                                function store_info(array &$post, array &$details, array &$validArr): void {
                                    foreach ($post as $key => $value) :
                                        if (isset($details[$key])) :

                                            // calls itself if it is an array
                                            if (is_array($value)):

                                                store_info($post[$key], $details[$key], $validArr);
                                            else:
                                                $details[$key] = htmlspecialchars($value);
                                                $validArr[$key] = False; // Set All Field Validation Check As False
                                            endif;
                                        endif;
                                    endforeach;
                                }

                                // sets the error message to the fields


                                $errMsg = array();
                                if (isset($_POST['edit_admin'])):

                                    /* Load Data To Arr */
                                    store_info($_POST, $admin_details, $validArr);

                                endif;

                            endif;
                            ?>
                            <!-- main section starts here -->
                            <main class="mt-5 pt-3">
                                <h2 class="text-center my-2"> Modify Admin</h2>
                                <hr class="bg-dark w-75 ms-auto me-auto">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card ms-auto me-auto outerCard" style="max-width: 55rem;">
                                            <div class="card-body">
                                                <!-- Inner Card -->
                                                <div class="card text-dark innerCard mb-3">
                                                    <!-- Spinner -->
                                                    <div class="text-center" id="spinner-container">
                                                        <div class="spinner-border text-secondary m-5" role="status" style="width: 20rem; height: 20em; border-width:2em;"></div>
                                                    </div>
                                                    <!-- Form -->
                                                    <form id="admin_form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?' . http_build_query($_GET); ?>">
                                                        <div class="card-title m-2 p-1">
                                                            <h4 style="font-weight: 600; font-size: 1.5rem;"><?php echo $facility->get_facilityname(); ?></h4>
                                                            <div class="d-grid gap-1 d-md-flex justify-content-md-start" style=" margin-top: 10px;">
                                                                <div class="form-control-plaintext text-muted" name="adminID" id="adminName"  style="font-weight: 600; max-width: 35rem;">
                                                                    Admin ID: <?php echo ($facility_admin !== null) ? $facility_admin->get_adminid() : "-"; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr class="ms-2" style="max-width: 60%;">
                                                        <div class="card-body">
                                                            <!-- Admin Name -->
                                                            <div class="mb-3 row" >
                                                                <label for="adminName" class="col-sm-2 col-form-label">Admin Name: </label>
                                                                <div class="col-sm-10" id="adminname-container">
                                                                    <input type="text" class="form-control" name="profile[adminname]" id="adminname" value="<?php echo $admin_details['profile']['adminname']; ?>">
                                                                    <small class="text-muted px-2">Note: Only "-" (dash) is allow as a separator.</small>

                                                                </div>
                                                            </div>
                                                            <!-- Admin Email -->
                                                            <div class="mb-3 row" >
                                                                <label for="email" class="col-sm-2 col-form-label">Email: </label>
                                                                <div class="col-sm-10" id="email-container">
                                                                    <input type="email" class="form-control" name="credentials[email]" id="email" value="<?php echo $admin_details['credentials']['email']; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end" style=" margin-top: 10px;">
                                                                <button type="submit" class="btn btn-success me-md-2 mr-2" name="edit_admin" id="edit-admin">
                                                                    <span><i class="fas fa-save"></i></span>
                                                                    <span>Save</span>
                                                                </button>
                                                                <a href="<?php echo SADMIN_WEB . "/edit/facility.php?fid=" . $fid; ?>" class="btn btn-danger" id="delBtn">
                                                                    <span><i class="fas fa-times"></i></span>
                                                                    <span>Cancel</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div><!-- END INNER CARD -->
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
                        $('#nav-facility').addClass('active');

                        $('#spinner-container').hide();

                        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                        /*------------------------------------------------
                         CLIENT SIDE VALIDATION FOR EMAIL
                         -------------------------------------------------*/

                        $(document).ready(function () {
                            $("#admin_form").validate({
                                rules: {
                                    "profile[adminname]": {
                                        required: true
                                    },
                                    "credentials[email]": {
                                        required: true,
                                        emailRegex: true
                                    }

                                },
                                messages: {
                                    "profile[adminname]": {
                                        required: "Required"
                                    },
                                    "credentials[email]": {
                                        required: "Required",
                                        emailRegex: "Email format is incorrect."
                                    }
                                },
                                errorElement: "em",
                                errorPlacement: function (error, element) {
                                    // This is the default behavior 

                                    error.insertAfter(element);
                                    error.addClass("help-block invalid-feedback");
                                },
                                success: function (label, element) {

                                    $(element).addClass("is-valid");
                                },
                                highlight: function (element, errorClass, validClass) {
                                    $(element).addClass("is-invalid").removeClass("is-valid");
                                },
                                unhighlight: function (element, errorClass, validClass) {
                                    $(element).addClass("is-valid").removeClass("is-invalid");
                                }
                            });
                        });
                        $.validator.addMethod("emailRegex", function (value, element) {
                            return this.optional(element) ||
                                    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                                    .test(value);
                        }, "Email format is incorrect.");
                            </script>
                        </body>
                    </html>
                    <?php
                    /* ERROR MESSAGES */

                    function remove_err_msg(array $validArr): void {
                        foreach ($validArr as $k => $v):
                            ?>
                            <script>
                                $('#' + '<?php echo $k; ?>' + '-feedback').remove();
                            </script>
                            <?php
                        endforeach;
                    }

                    function set_err_msg(string $key, string $err_msg): void {
                        ?>
                        <script>
                            var key = '<?php echo $key; ?>';
                            var err_msg = '<?php echo $err_msg; ?>';
                            $(`#${key}`).addClass('is-invalid');
                            var feedback = `<div id='${key}'-feedback' class='invalid-feedback'>${err_msg}</div>`;
                            $(`#${key}-container`).append(feedback);
                        </script>
                        <?php
                    }

                    // validate and set the error message
                    function validation_loop(array &$doc_info, array &$validArr, string $admin_email): void {
                        foreach ($doc_info as $k => $v):
                            if (is_array($v)):
                                validation_loop($doc_info[$k], $validArr, $admin_email);
                            else:
                                validation($k, $v, $validArr, $admin_email);
                            endif;
                        endforeach;
                    }

                    function validation(string $k, string $v, array &$validArr, string $admin_email): void {

                        // check if empty
                        if (empty($v)):
                            $validArr[$k] = false;

                            set_err_msg($k, "Field Cannot Be Empty");

                        // --  name
                        elseif ($k == "adminname"):

                            if (!Regex::validate_adminname($v)):
                                set_err_msg($k, "Invalid Name Format");

                            else:
                                $validArr[$k] = true;

                            endif;

                        // -- email
                        elseif ($k == "email"):

                            if (!Regex::validate_email($v)):
                                set_err_msg($k, "Invalid Email Format");

                            elseif (Account_User::check_email_exist($v) && $v !== $admin_email):
                                set_err_msg($k, "Email Already Exist");
                            else:
                                $validArr[$k] = true;
                            endif;

                        else:
                            $validArr[$k] = true;
                        endif;
                    }

                    if (isset($_POST['edit_admin'])):
                        validation_loop($admin_details, $validArr, $facility_admin->get_email());
                        // -- After Validation 
                        if (!in_array(False, $validArr)):
                            ?>
                            <script>
                                $('#admin_form').hide();
                                $('#spinner-container').show();
                            </script>
                            <?php
                            $status = Facility_Admin::update_facility_admin($facility_admin->get_adminid(), $admin_details['profile']['adminname'], $admin_details['credentials']['email']);

                            if ($status !== true):
                                # Inform Old Email Of The New Changes 
                                EmailTemplate::template_emailchanged($facility_admin->get_email(), $admin_details['credentials']['email']);

                                # Inform New Email Of The New Password 
                                EmailTemplate::template_fadmin_mail_change($admin_details['credentials']['email'], $facility->get_facilityname(), $status);
                            endif;
                            ?>
                            <script>
                                window.location.replace(window.location.origin + '<?php echo SADMIN_WEB . "/edit/facility.php?fid=" . $fid; ?>');
                            </script>

                            <?php
                        endif;

                    endif; # -- END BUTTON 

                endif; # -- END FOR VALID VARS
            endif; # -- END GET ID CHECK ISSET
        endif; # -- END GET & POST REQUEST
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>