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
 *      ADD NEW SPECIALISATION
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
        $new_spec = "";
        $valid = false;
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>Add Specialization</title>
                <!-- fontawesome -->
                <script src="https://kit.fontawesome.com/dcfd5ba5e7.js" crossorigin="anonymous" ></script>
                <!-- google fonts -->
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap" rel="stylesheet" />
                <!-- bootstrap cdn link -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
                <!-- bootstrap data table -->
                <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" />
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"/>
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
                        height: 1px; 
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

                <!-- Current Page (Add New Specialisation -->
                <main class="mt-5 pt-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 text-center fw-700 fs-1">
                                Add Specialisation
                            </div>
                            <div class="col-md-12 text-muted text-center fw-700">
                                Facility admin @ <span id="facilityName"><?php echo StringUtils::get_acronym($facility->get_facilityname()); ?> </span>
                            </div>
                        </div>

                        <div class="row mt-4 ms-auto me-auto">
                            <div class="col-lg-12">
                                <div class="card text-start text-white bg-dark" style="max-width: 60rem;">
                                    <div class="card-body text-white">
                                        <form id="add_doctor_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

                                            <!-- Specialisation -->
                                            <div class="mb-3" id="specialisation-container">
                                                <label for="specialisation" class="form-label">Specialization: </label>
                                                <input type="text" id="specialisation" name="specialisation" class="form-control" style="width: 60%;">
                                            </div>
                                            <hr>

                                            <!-- Buttons -->    
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <button  class="btn btn-outline-light me-md-2 mr-2" type="submit" name="add_specialisation">
                                                    <span><i class="bi bi-plus-lg"></i></span>
                                                    <span>Add</span>
                                                </button>
                                                <button class="btn btn-outline-danger" id="resetBtn" type="button">
                                                    <span><i class="bi bi-x-lg"></i></span>
                                                    <span>Reset</span>
                                                </button>
                                            </div><!-- end of buttons -->
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <br>
                <br>
                <!-- main ends here -->
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
                <!-- bootstrap js link -->
            </body>
        </html>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") :
            if (isset($_POST['add_specialisation'])):
                ?>
                <!-- Remove The Feedback -->
                <script>
                    $("#specialisation").removeClass("is-invalid");
                    $("#specialisation-feedback").remove();
                </script>
                <?php
                $new_spec = ucwords(StringUtils::clean_input($_POST['specialisation']));
                // Check empty
                if (empty($new_spec)):
                    ?>
                    <script>
                        $("#specialisation").addClass("is-invalid");
                        var feedback = "<div id='specialisation-feedback' class='invalid-feedback'>Field Cannot Be Left Blank</div>";
                        $("#specialisation-container").append(feedback);
                        $("#specialisation").val('<?php echo $new_spec; ?>');
                    </script>
                    <?php
                // The Specialisation Field Is Not Empty 
                else:
                    // Add Specialisation To Database 
                    $success = Medical_Facility::insert_specialisation($facility->get_facilityid(), $new_spec);
                    if ($success):
                        ?>
                        <!-- Redirect Back To View All Specialisations -->
                        <script>
                            window.location.replace(window.location.origin + '<?php echo FADMIN_WEB . "/views/specialisations.php"; ?>');
                        </script>
                        <?php
                    else:
                        ?>
                        <script>
                            $("#specialisation").addClass("is-invalid");
                            var feedback = "<div id='specialisation-feedback' class='invalid-feedback'>Specialisation Already Exist.</div>";
                            $("#specialisation-container").append(feedback);
                            $("#specialisation").val('<?php echo $new_spec; ?>');
                        </script>
                    <?php
                    endif;

                endif;
            endif;
        endif;

    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>