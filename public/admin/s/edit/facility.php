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

require_once DB_MOD . '/DbStorage.php';

require_once FACILITY_MOD . '/Medical_Facility.php';

/*
 *  EDIT INDIVIDUAL FACILITY DETAILS --------- (EDIT)
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
                    <title>Admin HomePage</title>
                    <!-- fontawesome -->
                    <script src="https://kit.fontawesome.com/dcfd5ba5e7.js"  crossorigin="anonymous" ></script>
                    <!-- google fonts -->
                    <link rel="preconnect" href="https://fonts.googleapis.com" />
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap" rel="stylesheet" />
                    <!-- bootstrap cdn link -->
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
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

                            if ($_SERVER["REQUEST_METHOD"] == "GET"):

                                $facility_details = array(
                                    'facilityname' => $facility_info->get_facilityname(),
                                    'contactnumber' => $facility_info->get_contactnumber(),
                                    'address' => $facility_info->get_address(),
                                    'operatinghours' => array(
                                        'is24hours' => $facility_info->get_operatinghours()->get_is24hours(),
                                        'openinghour' => $facility_info->get_operatinghours()->get_openinghour(),
                                        'closinghour' => $facility_info->get_operatinghours()->get_closinghour()
                                    )
                                );
                                $admin_details = array(
                                    'profile' => array("adminname" => ($facility_admin !== null) ? $facility_admin->get_adminname() : ""),
                                    'credentials' => array("email" => ($facility_admin !== null) ? $facility_admin->get_email() : "")
                                );
                                $icon = array(
                                    'tmp_name' => '',
                                    'size' => '',
                                    'type' => '',
                                    'name' => '',
                                    'error' => ''
                                );
                                $validArr = array();
                            else: # -- POST 

                                $facility_details = array(
                                    'facilityname' => '',
                                    'contactnumber' => '',
                                    'address' => '',
                                    'operatinghours' => array(
                                        'is24hours' => false,
                                        'openinghour' => '',
                                        'closinghour' => ''
                                    )
                                );
                                $admin_details = array(
                                    'profile' => array("adminname" => ''),
                                    'credentials' => array("email" => '')
                                );
                                $icon = array(
                                    'tmp_name' => '',
                                    'size' => '',
                                    'type' => '',
                                    'name' => '',
                                    'error' => ''
                                );
                                $validArr = array();

                                // loop and store all the information into an array
                                function store_info(array &$post, array &$facility_details, array &$validArr): void {
                                    foreach ($post as $key => $value) :
                                        if (isset($facility_details[$key])) :

                                            // calls itself if it is an array
                                            if (is_array($value)):

                                                store_info($post[$key], $facility_details[$key], $validArr);
                                            else:
                                                $facility_details[$key] = htmlspecialchars($value);
                                                $validArr[$key] = False; // Set All Field Validation Check As False
                                            endif;
                                        endif;
                                    endforeach;
                                }

                                function save_facility_icon(array $icon_info, string $file_name) {
                                    $file_ext = explode(".", $icon_info["name"]);
                                    $size = ($icon_info["size"] / 1024); # In kb
                                    $type = $icon_info["type"];
                                    $tmp_path = $icon_info["tmp_name"];
                                    $db_storage = new DbStorage();
                                    $db_storage->store_data($type, (int) $size, $tmp_path, 'facility/facilityicon/' . $file_name . "." . $file_ext[1]);
                                }

                                if (isset($_POST['edit_facility'])):
                                    /* Load Data To Arr */
                                    store_info($_POST, $facility_details, $validArr);

                                    // Icon Check
                                    if (isset($_FILES['facility_icon'])):

                                        if ($_FILES["facility_icon"]["error"] > 0):
                                            echo "Error: " . $_FILES["facility_icon"]["error"] . "<br />";
                                        else :
                                            # If the icon is set 
                                            $icon = $_FILES['facility_icon'];

                                        endif; # -- END OF FACILITY ICON ERROR CHECK

                                    endif; # -- FACILTIY ICON 
                                    // -- After Validation 
                                    if (!in_array(False, $facility_details)):
                                        if ($_FILES['facility_icon']['size'] != 0):
                                            save_facility_icon($_FILES['facility_icon'], $facility_info->get_facilityid());
                                        endif;
                                        // Update The Facility Information
                                        Medical_Facility::update_facility($facility_info->get_facilityid(), $facility_details);
                                    endif;

                                endif;

                            endif;
                            ?>
                            <!-- main section starts here -->
                            <main class="mt-5 pt-3">
                                <h2 class="text-center my-2">Modify Medical Facility</h2>
                                <hr class="bg-dark w-75 ms-auto me-auto">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card ms-auto me-auto outerCard" style="max-width: 55rem;">
                                            <div class="card-body">
                                                <form id="facility_form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?' . http_build_query($_GET); ?>" enctype="multipart/form-data">

                                                    <div class="card text-dark innerCard mb-3">
                                                        <div class="card-title ms-2 mt-2">
                                                            <div class="mb-3 row">
                                                                <div class="col-sm-11">
                                                                    <input style="font-weight: 600; font-size: 1.4rem;" type="text" class="form-control" id="facilityname" name="facilityname" value="<?php echo $facility_details['facilityname']; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="d-grid gap-1 d-md-flex justify-content-md-start" style=" margin-top: 10px;">
                                                                <input type="text" readonly class="form-control-plaintext text-muted"  id="adminName" value="<?php echo ($facility_admin !== null) ? $facility_admin->get_adminname() : "  -"; ?>" style="font-weight: 600; max-width: 10rem;">
                                                                <a href="<?php echo SADMIN_WEB . "/edit/admin.php"; ?>" class="me-md-2 mr-2 editAdm" data-bs-toggle="tooltip" data-bs-placement="right" title="Edit Admin">
                                                                    <span><i class="fas fa-pen-square fa-2x"></i></span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <hr class="ms-2" style="max-width: 60%;">
                                                        <div class="card-body">
                                                            <!-- Facility Icon -->
                                                            <div class="mb-3 row">
                                                                <label for="facility-icon" class="col-sm-2 col-form-label">Facility Icon: </label>
                                                                <div class="col-sm-10">
                                                                    <input class="form-control form-control-sm" id="facility-icon" type="file" name="facility_icon" accept=".png" value="<?php echo $icon; ?>"/>
                                                                    <small class="text-muted">Only .png images are allowed.</small>
                                                                </div>
                                                            </div>
                                                            <!-- Address -->
                                                            <div class="mb-3 row">
                                                                <label for="address" class="col-sm-2 col-form-label">Address: </label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $facility_details['address']; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 row">
                                                                <label for="contactnumber" class="col-sm-2 col-form-label">Contact: </label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" id="contactnumber" name="contactnumber" value="<?php echo $facility_details['contactnumber']; ?>">
                                                                </div>
                                                            </div>

                                                            <div class="mb-3 row">
                                                                <label for="is24hours" name="is24hrs" class="col-sm-2 col-form-label">Is 24 Hours:</label>
                                                                <div class="col-sm-10 pt-2">
                                                                    <input type="checkbox" value="true" onclick="is24hour_check()"  name="operatinghours[is24hours]"  class="form-check-input " id="is24hours" <?php
                                                                    if ($facility_details['operatinghours']['is24hours']): echo "checked";
                                                                    endif;
                                                                    ?>>
                                                                </div>
                                                            </div>

                                                            <div class="mb-3" id="time-start-end">
                                                                <div class="row">
                                                                    <!-- Opening Hour -->
                                                                    <div class="col-md-12">
                                                                        <label for="openinghour" class="col-sm-2 col-form-label">Opening Hour: </label>
                                                                        <input id="starthour" type="time" style="margin-top: 5px; border: 1px solid #eeeded;" class="rounded p-1" name='operatinghours[openinghour]' value="<?php echo $facility_details['operatinghours']['openinghour']; ?>">
                                                                    </div>
                                                                    <!-- Closing Hour -->
                                                                    <div class="col-md-12">
                                                                        <label for="closinghour" class="col-sm-2 col-form-label">Closing Hour: </label>
                                                                        <input id="endhour" type="time" style="margin-top: 5px; border: 1px solid #eeeded;" class="rounded p-1" name="operatinghours[closinghour]" value="<?php echo $facility_details['operatinghours']['closinghour']; ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end" style=" margin-top: 10px;">
                                                                <button type="submit" class="btn btn-success me-md-2 mr-2" name="edit_facility" id="add-facility">
                                                                    <span><i class="fas fa-save"></i></span>
                                                                    <span>Save</span>
                                                                </button>
                                                                <a href="<?php echo SADMIN_WEB."/views/facility.php?fid=".$facility_info->get_facilityid();?>" class="btn btn-danger" id="delBtn">
                                                                    <span><i class="fas fa-times"></i></span>
                                                                    <span>Cancel</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>                   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </main>
                            <!-- main ends here -->
                            <!--bootstrap js link--> 
                            <script
                                src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                                integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
                                crossorigin="anonymous"
                            ></script>

                            <!-- js code for the bootstrap tooltip -->
                            <script>
                                $('#nav-facility').addClass('active');
                                is24hour_check(); // Check Upon Loading Page
                                function is24hour_check() {

                                    if ($('#is24hours').is(":checked")) {
                                        $('#time-start-end').hide();
                                        console.log("Checked");
                                    } else {
                                        $('#time-start-end').show();
                                        console.log("not checked");
                                    }
                                    return;
                                }
                                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                                    return new bootstrap.Tooltip(tooltipTriggerEl);
                                });
                                /*------------------------------------------------
                                 CLIENT SIDE VALIDATION FOR EMAIL
                                 -------------------------------------------------*/

                                $(document).ready(function () {
                                    $("#facility_form").validate({
                                        rules: {
                                            "profile[adminname]": {
                                                required: true
                                            },
                                            "credentials[email]": {
                                                required: true,
                                                emailRegex: true
                                            },
                                            "facility_icon": {
                                                required: false
                                            },
                                            "facilityname": {
                                                required: true
                                            },
                                            "address": {
                                                required: true
                                            },
                                            "contactnumber": {
                                                required: true,
                                                phoneRegex: true
                                            },
                                            "operatinghours[openinghour]": {
                                                required: true
                                            },
                                            "operatinghours[closinghour]": {
                                                required: true
                                            },
                                        },
                                        messages: {
                                            "profile[adminname]": {
                                                required: "Required"
                                            },
                                            "credentials[email]": {
                                                required: "Required",
                                                emailRegex: "Email format is incorrect."
                                            },
                                            "facility_icon": {
                                                required: "Required",
                                                extensionRegex: "Wrong file format, only .png extension."
                                            },
                                            "facilityname": {
                                                required: "Required"
                                            },
                                            "address": {
                                                required: "Required"
                                            },
                                            "contactnumber": {
                                                required: "Required",
                                                phoneRegex: "Contact number format is incorrect"
                                            },
                                            "operatinghours[openinghour]": {
                                                required: "Required"
                                            },
                                            "operatinghours[closinghour]": {
                                                required: "Required"
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

                                $.validator.addMethod("extensionRegex", function (value, element) {
                                    return this.optional(element) ||
                                            /^.*\.(png|PNG)$/
                                            .test(value);
                                }, "Wrong file format, only .png extension.");


                                $.validator.addMethod("phoneRegex", function (value, element) {
                                    return this.optional(element) || /^[689]{1}[0-9]{7}$/.test(value);
                                }, "Contact number format is incorrect");

                                $.validator.addMethod("emailRegex", function (value, element) {
                                    return this.optional(element) ||
                                            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                                            .test(value);
                                }, "Email format is incorrect.");

                            </script>

                        </body>
                    </html>
                <?php
                endif; # -- END FOR VALID VARS
            endif; # -- END GET ID CHECK ISSET
        endif; # -- END GET & POST REQUEST
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>