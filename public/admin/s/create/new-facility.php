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
require_once UTIL_MOD . '/ArrayCreation.php';

require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';
require_once DB_MOD . '/DbStorage.php';

require_once EMAIL_MOD . '/EmailTemplate.php';

require_once FACILITY_MOD . '/Medical_Facility.php';
/*
 * CREATE FACILITY
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
        $fadmin_exist = null;
        $facility_exist = null;

        function save_facility_icon(array $icon_info, string $file_name) {
            $file_ext = explode(".", $icon_info["name"]);
            $size = ($icon_info["size"] / 1024); # In kb
            $type = $icon_info["type"];
            $tmp_path = $icon_info["tmp_name"];

            //            echo "Upload: " . $file_name . "<br />";
            //            echo "Type: " . $type . "<br />";
            //            echo "Size: " . $size . " Kb<br />";
            //            echo "Stored in: " . $tmp_path;
            //            echo "<img src='{$_FILES["facility_icon"]["tmp_name"]}' />";
            $db_storage = new DbStorage();
            $db_storage->store_data($type, (int) $size, $tmp_path, 'facility/facilityicon/' . $file_name . "." . $file_ext[1]);
        }

        function facility_account_creation(array $icon_info, array $facility_info, array $fadmin_info, bool|null &$fadmin_exist, bool|null &$facility_exist): bool {
            $fadmin_exist = Account_User::check_email_exist($fadmin_info['credentials']['email']);
            $facility_exist = Medical_Facility::check_facility_exist($facility_info);

            if (!($fadmin_exist) && !($facility_exist)) {

                # STEP 1: Get The Facility From The Database After Insert (Medical_Facility Creation)
                $mf = Medical_Facility::create_medical_facility($facility_info);

                # STEP 2 Insert Facility (save image & create new facility record in database)
                save_facility_icon($icon_info, $mf->get_facilityid());

                # Include More Information In Attained Array
                $fadmin_info['profile']['facilityid'] = $mf->get_facilityid();

                # STEP 3: Insert Facility Admin (Facility_Admin Creation)
                $admin_pw = Facility_Admin::create_facility_admin($fadmin_info);

                # STEP 4: Send Email To Facility Admin
                EmailTemplate::template_createfacility($fadmin_info['credentials']['email'], $mf->get_facilityname(), $admin_pw);
                return true;
            }
            return false;
        }

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

        if ($_SERVER["REQUEST_METHOD"] == "POST"):

            // User Add Facility 
            if (isset($_POST['add_facility'])):
                /* Load Data to Array */
                store_info($_POST, $facility_details, $validArr);
                store_info($_POST, $admin_details, $validArr);

                // Icon Check
                if (isset($_FILES['facility_icon'])):

                    if ($_FILES["facility_icon"]["error"] > 0):
                        echo "Error: " . $_FILES["facility_icon"]["error"] . "<br />";
                    else :
                        # If the icon is set 
                        $icon = $_FILES['facility_icon'];

                    endif; # -- END OF FACILITY ICON ERROR CHECK

                endif; # -- FACILTIY ICON 

            endif; # -- END OF FACILITY ICON
            // VALIDATION
            // END VALIDATION
            // Make Sure Valid (Add)
            if ($facility_details['operatinghours']['is24hours']):
                $facility_details['operatinghours']['openinghour'] = $facility_details['operatinghours']['closinghour'] = "";

            endif;

        endif;
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>Admin HomePage</title>
                <!-- fontawesome -->
                <script src="https://kit.fontawesome.com/dcfd5ba5e7.js" crossorigin="anonymous" ></script>
                <!-- google fonts -->
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap" rel="stylesheet" />
                <!-- bootstrap cdn link -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"  />
                <!-- bootstrap data table -->
                <link  rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css"/>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
                <!-- Prevent Form Resubmission -->
                <script>
                    if (window.history.replaceState) {
                        window.history.replaceState(null, null, window.location.href);
                    }
                </script>
                <!-- jQuery -->
                <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
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
                <!-- main section starts here -->
                <main class="mt-5 pt-3">
                    <h2 class="text-center my-2">Add Medical Facility</h2>
                    <hr class="bg-dark w-75 ms-auto me-auto">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card ms-auto me-auto outerCard" style="max-width: 55rem;">
                                <div class="card-body" id="new-facility-card">
                                    <!-- Spinner -->
                                    <div class="text-center" id="spinner-container">
                                        <div class="spinner-border text-secondary" role="status" style="width: 10rem; height: 10em; border-width:2em;"></div>
                                    </div>
                                    <form id="facility_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">

                                        <div class="card text-dark innerCard">
                                            <!-- Facility Admin Section -->
                                            <div class="card-title ms-3 mt-3" id="admin-section">
                                                <h5 class="text-muted small">Admin Details</h5>
                                                <!-- Admin Name -->
                                                <div class="mb-3 row">
                                                    <label for="adminname" class="col-sm-2 col-form-label">Admin Name: </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" style="max-width:20rem;" id="adminname" name="profile[adminname]" value="<?php echo $admin_details['profile']['adminname']; ?>">
                                                    </div>
                                                </div>
                                                <!-- Email -->
                                                <div class="mb-3 row">
                                                    <label for="email" class="col-sm-2 col-form-label">Email: </label>
                                                    <div class="col-sm-10">
                                                        <input type="email" class="form-control" style="max-width:20rem;" id="email" name="credentials[email]" value="<?php echo $admin_details['credentials']['email']; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Facility Section -->
                                            <hr class="ms-3" style="max-width: 90%;">
                                            <div class="card-body" id="facility-section">
                                                <h5 class="text-muted small mb-3">Facility Details</h5>
                                                <!-- Facility Icon -->
                                                <div class="mb-3 row">
                                                    <label for="facility-icon" class="col-sm-2 col-form-label">Facility Icon: </label>
                                                    <div class="col-sm-10">
                                                        <input class="form-control form-control-sm" id="facility-icon" type="file" name="facility_icon" accept=".png" value="<?php echo $icon; ?>"/>
                                                        <small class="text-muted">Only .png images are allowed.</small>
                                                    </div>

                                                </div>
                                                <!-- Facility Name -->
                                                <div class="mb-3 row">
                                                    <label for="facilityname" class="col-sm-2 col-form-label">Facility Name: </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="facilityname" name="facilityname" id="facilityname" value="<?php echo $facility_details['facilityname']; ?>">
                                                    </div>
                                                </div>
                                                <!-- Address -->
                                                <div class="mb-3 row">
                                                    <label for="address" class="col-sm-2 col-form-label">Address: </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="address" name="address" id="address" value="<?php echo $facility_details['address']; ?>">
                                                    </div>
                                                </div>
                                                <!-- Contact Number -->
                                                <div class="mb-3 row">
                                                    <label for="contactnumber" class="col-sm-2 col-form-label">Contact: </label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="contactnumber" name="contactnumber" value="<?php echo $facility_details['contactnumber']; ?>">
                                                    </div>
                                                </div>

                                                <div class="mb-3 row">
                                                    <label for="is24hours" class="col-sm-2 col-form-label">Is 24 Hours:</label>
                                                    <div class="col-sm-10 pt-2">
                                                        <input type="checkbox" value="true" onclick="is24hour_check()" name="operatinghours[is24hours]"  class="form-check-input " id="is24hours" <?php
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

                                                <!-- Buttons -->
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end" style=" margin-top: 10px;">
                                                    <button type="submit" class="btn btn-success me-md-2 mr-2" name="add_facility" id="add-facility">
                                                        <span><i class="fas fa-save"></i></span>
                                                        <span>Save</span>
                                                    </button>
                                                    <a href="<?php echo SADMIN_WEB; ?>"  class="btn btn-danger" id="delBtn">
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


                <!-- js code for the bootstrap tooltip -->
                <script>
                    $('#spinner-container').hide();
                    $('#nav-facility').addClass('active');
                    is24hour_check(); // Check Upon Loading Page
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
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
                                    required: true,
                                    extensionRegex: true
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
                                },
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
                <!-- bootstrap js link -->
                <script
                    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
                    crossorigin="anonymous"
                ></script>
            </body>
        </html>
        <?php
        // When The Button Is Triggered
        if (isset($_POST['add_facility'])):
            ?>
            <script>
                        console.log("succes call");
                        $('#facility_form').hide();
                        $('#spinner-container').show();
            </script>
            <?php
            $facility_creation = facility_account_creation($icon, $facility_details, $admin_details, $fadmin_exist, $facility_exist);
            if ($facility_creation):
                ?>
                <script>
                    window.location.replace(window.location.origin + '<?php echo SADMIN_WEB; ?>');
                </script>
                <?php
            endif;
        endif;
        if ($facility_exist === true):
            ?>
            <script>
                console.log('facility exist');
                $('#facility_form').show();
                $('#spinner-container').hide();
                var facility_exist = "<div id='facility-feedback' class='alert alert-danger'>Facility Already Exist In System</div>";
                $('#facility-section').prepend(facility_exist);
            </script>
            <?php
            $facility_exist = null;
        endif;
        if ($fadmin_exist === true):
            ?>
            <script>
                console.log("user exist");
                $('#facility_form').show();
                $('#spinner-container').hide();
                var admin_exist = "<div id='admin-feedback' class='alert alert-danger'>Email Already Exist</div>";
                $('#admin-section').prepend(admin_exist);
            </script>
            <?php
            // Reset Bool
            $fadmin_exist = null;
        endif;

    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>
