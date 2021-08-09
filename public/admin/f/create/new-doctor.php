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

require_once EMAIL_MOD . "/EmailTemplate.php";

/*
 * CREATE NEW MEDICAL PERSONNEL
 */
if (!isset($_SESSION['user'])):
    header("Location:/"); # -- REDIRECT BACK TO THE HOME PAGE
else:
    $user = unserialize($_SESSION["user"]);
    if ($user->get_usertype() !== User_Type::FACIILITY_ADMIN):
        header("Location:/"); # -- REDIRECT BACK TO THE HOME PAGE
    else: # -- ONLY ALLOW FACILITY ADMIN
        $facility = $user->get_facility();
        // get specialisations from facility
        $specialisations = $facility->get_specialisations();
        sort($specialisations);

        $doc_info = array(
            'profile' => array(
                'address' => '',
                'contactnumber' => '',
                'dob' => '',
                'gender' => '',
                'name' => array(
                    'firstname' => '',
                    'lastname' => ''
                ),
                'nric' => ''
            ),
            'credentials' => array(
                'email' => ''
            ),
            'practitionerinfo' => array(
                'facilityids' => array($facility->get_facilityid()),
                'licensenumber' => '',
                'specialisation' => ''
            )
        );

        $validArr = array();
        $err_msg = array();
        
        $current_date = Time::get_current_date(Time::CALENDAR_FORMAT_DEFAULT);
        $years = 18;
        $max_date = Time::get_startdate_by_years($current_date, $years, Time::CALENDAR_FORMAT_DEFAULT);

        // loop and store all the information into an array
        function store_info(array &$post, array &$doc_info, array &$validArr, array &$err_msg): void {
            foreach ($post as $key => $value) :
                if (isset($doc_info[$key])) :

                    // calls itself if it is an array
                    if (is_array($value)):
                        $err_msg[$key] = array();
                        store_info($post[$key], $doc_info[$key], $validArr, $err_msg[$key]);
                    else:
                        $doc_info[$key] = htmlspecialchars($value);
                        $validArr[$key] = False; // Set All Field Validation Check As False
                        $err_msg[$key] = "";
                    endif;
                endif;
            endforeach;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") :

            if (isset($_POST['add-doctor'])):
                // loop information to an array (store and echo)
                store_info($_POST, $doc_info, $validArr, $err_msg);
            endif;

        endif;
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>Add New Doctor</title>
                <!-- fontawesome -->
                <script src="https://kit.fontawesome.com/dcfd5ba5e7.js"  crossorigin="anonymous"></script>
                <!-- google fonts -->
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap" rel="stylesheet" />
                <!-- bootstrap cdn link -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"  crossorigin="anonymous"  />
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
                    .sidebar-link {
                        display: flex;
                        align-items: center;
                    }
                    .sidebar-link .right-icon {
                        display: inline-flex;
                    }
                    .sidebar-link[aria-expanded="true"] .right-icon {
                        transform: rotate(180deg);
                        transition: all ease 0.25s;
                    }
                    .card hr{
                        border: 0; 
                        height: 2px; 
                        max-width: 80%;
                        margin: 15px auto 7px;
                        background-image: linear-gradient(to right, #f0f0f0, #00b9ff, #59d941, #f0f0f0);
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
                </style>
            </head>
            <body>
                <!-- NavBar  (TOP) -->
                <?php require_once TEMPLATES_PATH . "/fadmin-navbar.php"; ?>
                <!-- Canvas (SIDE) -->
                <?php require_once TEMPLATES_PATH . "/fadmin-canvas.php"; ?>
                <!-- Current Page (Add A New Doctor) -->
                <main class="mt-5 pt-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 text-center fw-700 fs-1">
                                Add Doctor
                            </div>
                            <div class="col-md-12 text-muted text-center fw-700">
                                Facility admin @ <span id="facilityName"><?php echo StringUtils::get_acronym($facility->get_facilityname()); ?> </span>
                            </div>
                        </div>
                    </div>
                    <!-- add doctor form starts here -->

                    <div class="row mt-4 ms-auto me-auto" id="add-doctor-card">
                        <div class="col-lg-12">
                            <div class="card text-start bg-dark ms-auto me-auto" style="max-width: 60rem; border-radius: 18px;">
                                <div class="card-body text-white">

                                    <!-- FORM -->
                                    <form id="add_doctor_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                        <!-- row 1 -->
                                        <div class="row">
                                            <!-- first name -->
                                            <div class="col">
                                                <div class="form-group" id="firstname-container">
                                                    <label for="firstname">First Name</label>
                                                    <input type="text" name="profile[name][firstname]" id="firstname" class="form-control" value='<?php echo $doc_info['profile']['name']['firstname']; ?>'>
                                                </div>
                                            </div>
                                            <!-- last name -->
                                            <div class="col">
                                                <div class="form-group" id="lastname-container">
                                                    <label for="lastname">Last Name</label>
                                                    <input type="text" name="profile[name][lastname]" id="lastname" class="form-control" value='<?php echo $doc_info['profile']['name']['lastname']; ?>'>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- row 2 -->
                                        <div class="row">
                                            <!-- address -->
                                            <div class="col">
                                                <div class="form-group" id="address-container">
                                                    <label for="address">Address</label>
                                                    <input type="text" name="profile[address]" id="address" class="form-control" value='<?php echo $doc_info['profile']['address']; ?>'>
                                                </div>
                                            </div>
                                            <!-- contact number -->
                                            <div class="col">
                                                <div class="form-group" id="contactnumber-container">
                                                    <label for="contact">Contact Number</label>
                                                    <input type="text" name="profile[contactnumber]" id="contactnumber" class="form-control" value='<?php echo $doc_info['profile']['contactnumber']; ?>' placeholder="+65">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- row 3 -->
                                        <div class="row">
                                            <!-- dob -->
                                            <div class="col">
                                                <div class="form-group" id="dob-container">
                                                    <label for="dob">Date of birth <span>(18 and above)</span></label>
                                                    <input type="date" name="profile[dob]" id="dob" class="form-control" value='<?php echo $doc_info['profile']['dob']; ?>' max="<?php echo $max_date;?>">
                                                </div>
                                            </div>
                                            <!-- gender -->
                                            <div class="col">
                                                <div class="form-group" id="gender-container">
                                                    <label for="gender">Gender</label>
                                                    <select name="profile[gender]" id="gender" class="form-control">
                                                        <option value=" " disabled selected hidden>Select gender</option>
                                                        <option id="gender_f" value="F" <?php
                                                        if ($doc_info['profile']['gender'] == "F") :
                                                            echo "selected";
                                                        endif;
                                                        ?>>Female</option>
                                                        <option id="gender_m" value="M" <?php
                                                        if ($doc_info['profile']['gender'] == "M") :
                                                            echo "selected";
                                                        endif;
                                                        ?>>Male</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <!-- row 4 -->
                                        <div class="row">
                                            <!-- license number -->
                                            <div class="col">
                                                <div class="form-group" id="licensenumber-container">
                                                    <label for="licensenumber">License Number</label>
                                                    <input type="text" name="practitionerinfo[licensenumber]" id="licensenumber" class="form-control" value='<?php echo $doc_info['practitionerinfo']['licensenumber']; ?>'>
                                                </div>
                                            </div>
                                            <!-- specialisation -->
                                            <div class="col">
                                                <div class="form-group" id="specialisation-container">
                                                    <label for="specialisation">Specialisation</label>
                                                    <select name="practitionerinfo[specialisation]" id="specialisation" class="form-control">
                                                        <option value=" " disabled selected hidden>Select Specialisation</option>
                                                        <?php foreach ($specialisations as $spec): ?>
                                                            <option value="<?php echo $spec; ?>" <?php
                                                            if ($doc_info['practitionerinfo']['specialisation'] == $spec):
                                                                echo 'selected';
                                                            endif;
                                                            ?>><?php echo $spec; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <hr/>
                                        <!-- row 5 -->
                                        <div class="row">
                                            <!-- nric -->
                                            <div class="col-6">
                                                <div class="form-group" id="nric-container">
                                                    <label for="nric">NRIC</label>
                                                    <input type="text" name="profile[nric]" id="nric" class="form-control" value='<?php echo $doc_info['profile']['nric']; ?>' placeholder="NRIC/FIN">
                                                </div>
                                            </div>
                                            <!-- email -->
                                            <div class="col-6">
                                                <div class="form-group" id="email-container">
                                                    <label for="email">Email</label>
                                                    <input type="text" name="credentials[email]" id="email" class="form-control" value='<?php echo $doc_info['credentials']['email']; ?>' placeholder="e.g. example@mail.com">
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        <!-- Buttons -->
                                        <div class="card-footer">
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                                                <button class="btn btn-outline-light me-md-2 mr-2" type='submit' name="add-doctor">
                                                    <span><i class="bi bi-plus-lg"></i></span>
                                                    <span>Add</span>
                                                </button>
                                                <button class="btn btn-outline-danger" id="resetBtn" type="reset">
                                                    <span><i class="bi bi-x-lg"></i></span>
                                                    <span>Reset</span>
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- add doctor form ends here -->
                </main>
                <br>
                <br>
                <!-- main ends here -->
                <!-- bootstrap js link -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
                <!-- for testing the input boxes and the reset button -->
                <script>
                    var checkBoxes = document.querySelectorAll("input[type = 'checkbox']");
                    var btnReset = document.getElementById('resetBtn');
                    var inputs = document.querySelectorAll('input');
                    var ta = document.querySelectorAll('textarea');
                    btnReset.addEventListener('click', () => {
                        inputs.forEach(input => input.value = '');
                        ta.forEach(textarea => textarea.value = '');
                        checkBoxes.forEach(checkbox => checkbox.checked = false);
                    });
                    function checkAll(myCheckBox) {
                        if (myCheckBox.checked === true) {
                            checkBoxes.forEach(function (checkbox) {
                                checkbox.checked = true;
                            });
                        } else {
                            checkBoxes.forEach(function (checkbox) {
                                checkbox.checked = false;
                            });
                        }
                    }
                </script>
            </body>
        </html>
        <?php

        // sets the error message to the fields
        /* ERROR MESSAGES */

        function set_err_msg(string $key, string $err_msg): void {

            echo "
                    <script>
                        $('#{$key}').addClass('is-invalid');
                        var feedback = \"<div id='{$key}-feedback' class='invalid-feedback'>{$err_msg}</div>\";
                        $('#{$key}-container').append(feedback);

                    </script>
                ";
        }

        function remove_err_msg(array $validArr): void {
            foreach ($validArr as $k => $v):
                echo "
                <script>
                    $('#{$k}-feedback').remove();
                </script>
                ";
            endforeach;
        }

        // validate and set the error message
        function validation_loop(array &$doc_info, array &$validArr): void {
            foreach ($doc_info as $k => $v):
                if (is_array($v)):
                    validation_loop($doc_info[$k], $validArr);
                else:
                    validation($k, $v, $validArr);
                endif;
            endforeach;
        }

        function validation(string $k, string $v, array &$validArr): void {


            // check if empty
            if (empty($v)):
                $validArr[$k] = false;
                if ($k == "gender" || $k == "specialisation"):
                    set_err_msg($k, ucwords($k) . " Not Selected");
                else:
                    set_err_msg($k, "Field Cannot Be Empty");
                endif;

            // --  name
            elseif ($k == "firstname" || $k == "lastname"):

                if (!Regex::validate_name($v)):
                    set_err_msg($k, "Invalid Name Format");

                else:
                    $validArr[$k] = true;

                endif;

            // -- email
            elseif ($k == "email"):

                if (!Regex::validate_email($v)):
                    set_err_msg($k, "Invalid Email Format");
                elseif (Account_User::check_email_exist($v)):
                    set_err_msg($k, "Email Already Exist");
                else:
                    $validArr[$k] = true;

                endif;

            // -- contactnumber
            elseif ($k == "contactnumber"):

                if (!Regex::validate_phone($v)):
                    set_err_msg($k, "Invalid Phone Format (Singapore)");
                else:
                    $validArr[$k] = true;
                endif;

            // nric
            elseif ($k == "nric"):

                $v = strtoupper($v);
                $validate_nric = new ValidateIC($v);
                if (!(Regex::validate_nric($v)) || !($validate_nric->validate_nric())):

                    set_err_msg($k, "Invalid NRIC Format");
                elseif (Account_User::check_nric_exist($v)):
                    set_err_msg($k, "NRIC already exist");
                else:
                    $validArr[$k] = true;

                endif;

            else:
                $validArr[$k] = true;
            endif;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") :
            #_________CROSS CHECK FOR EMPTY, REGEX AND DUPLICATES _________#
            validation_loop($doc_info, $validArr);

            # __________________________ END VALIDATION ___________________________#
            // If Valid User Information (After Validation)
            if (!in_array(False, $validArr)) :
                ?>
                <script>
                    $("#add-doctor-card").html("");
                    var spinner_container = '<div class="text-center" id="spinner-container"></div>';
                    var spinner = '<div class="spinner-border" role="status" style="width: 20rem; height: 20rem; border-width:4em;"></div>';
                    $('#add-doctor-card').append(spinner_container);
                    $('#spinner-container').append(spinner);
                </script>
                <?php
                # Change The Date Back To Database Default
                $doc_info['profile']['dob'] = Time::date_format_default($doc_info['profile']['dob']);

                # Insert To Database
                $credentials = Medical_Personnel::create_medical_personnel($doc_info);

                # Send Email To The User 
                EmailTemplate::template_createmedicalpersonnel($credentials['email'], $credentials['password']);

                # Redirect To View Doctor Page
                ?>
                <script>
                    window.location.replace(window.location.origin + '<?php echo FADMIN_WEB . "/views/doctors.php"; ?>');
                </script>
                <?php
            endif; # -- END VALIDATE CHECK

        endif; # -- END POST REQUEST
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>
