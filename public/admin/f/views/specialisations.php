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
 *  VIEW FACILITY SPECIALISATION 
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

        // ------ FUNCTIONS ------------- // 
        function retrieve_specialisations(string $facilityid): array {
            $facility = Medical_Facility::retrieve_facility_by_id($facilityid);
            $specialisations = $facility->get_specialisations();
            sort($specialisations);
            return $specialisations;
        }

        $facility = $user->get_facility();
        $facilityid = $facility->get_facilityid();
        $specialisations = retrieve_specialisations($facilityid);

        if ($_SERVER['REQUEST_METHOD'] == "POST"):

            // DELETE BUTTON    
            if (isset($_POST["ajax_delete"]) && isset($_POST['specialisation'])):
                Medical_Facility::delete_specialisation($facilityid, $_POST["specialisation"]);
            endif;

        endif;
        ?><!DOCTYPE html>
        <html lang="en">
            <head>

                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>Add Specialization</title>
                <!-- fontawesome -->
                <script src="https://kit.fontawesome.com/dcfd5ba5e7.js"  crossorigin="anonymous"></script>
                <!-- google fonts -->
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap"  rel="stylesheet" />
                <!-- bootstrap cdn link -->
                <link  href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"  integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
                <!-- bootstrap data table -->
                <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" />
                <link  rel="stylesheet"  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"  />
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
                    @media (max-width:992px){
                        .col-lg-4{
                            margin-bottom: 20px;
                        }
                    }
                    .d-flex{
                        border-radius: 15px;
                    }
                    .AD{
                        margin-left: 10px;
                    }
                </style>
            </head>
            <body>
                <!-- NavBar  -->
                <?php require_once TEMPLATES_PATH . "/fadmin-navbar.php"; ?>
                <!-- Current Page (View Facility Specialisations) -->
                <main class="mt-5 pt-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 text-center fw-700 fs-1">
                                Add Specialization
                            </div>
                            <div class="col-md-12 text-muted text-center fw-700">
                                Facility admin @ <span id="facilityName"><?php echo StringUtils::get_acronym($facility->get_facilityname()); ?></span>
                            </div>
                        </div>
                        <div class="row mt-4 mb-2 mb-lg-0">

                            <!-- Add New Specialisation -->
                            <div class="col-md-12 text-center me-auto my-2 p-3">
                                <a  href="<?php echo FADMIN_WEB . "/create/new-specialisation.php"; ?>" class="btn btn-dark btn-lg me-3" >
                                    <span><i class="bi bi-plus-lg"></i></span>
                                    <span>Add Specialisation</span>
                                </a>
                            </div>
                            <div class="row mt-4 mb-2 mb-lg-0 ms-auto me-auto">
                                <?php
                                $count = 0;
                                foreach ($specialisations as $spec):
                                    $count++;
                                    ?>
                                    <!-- ONE SPECIALISATION -->
                                    <div class="col-md-4 mt-2"  id="<?php echo $count; ?>">
                                        <div class="shadow d-flex justify-content-center align-items-center p-3 bg-dark rounded-lg flex-column">
                                            <div class="info my-1">
                                                <h6 class="text-white">
                                                    <?php echo $spec; ?>
                                                </h6>
                                            </div>
                                            <!-- Delete Button -->
                                            <button value="<?php echo htmlspecialchars($spec); ?>" onclick="delete_specialisation(this.value, <?php echo $count; ?>)" class="btn btn-outline-danger btn-md" data-bs-toggle="modal" data-bs-target="#deleteSpec">
                                                <span><i class="bi bi-x-lg"></i></span>
                                                <span id="specialisationDelete">Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- END OF ONE SPECIALISATION CARD -->
                                    <?php
                                endforeach;
                                ?>

                            </div>
                        </div>

                </main>
                <br />
                <br />
                <!-- main ends here -->
                <section>
                    <div class="modal fade" id="deleteSpec" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Delete Specialisation: <br/><b><span id="spec"></span></b></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="delete-modal-content">
                                    <div class="alert alert-danger" role="alert" id="delete-alert">
                                        <span>
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            This action is irrevocable.
                                        </span>
                                    </div>
                                </div>
                                <!-- Triggering Delete (Specialisation) -->
                                <div class="modal-footer">
                                    <input type="hidden" id="counter-identifier"/>
                                    <button id="delete-specialisation-btn" type="button" class="btn btn-danger" name="delete"> <i class="fas fa-trash"></i> Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <script>
                    $('#nav-facility').addClass('active');
                    $('#nav-specialisation').addClass('active');
                    var req = null;
                    // -- Pass Information To Modal
                    function delete_specialisation(specialisation, count_identifier) {
                        // -- Testing
                        console.log(specialisation);

                        // jQuery Calls To Set The Modal Information 
                        $("#delete-specialisation-btn").val(specialisation); // Set Val To Btn
                        $("#counter-identifier").val(count_identifier); // Set Val To Modal

                        $("#spec").html(specialisation); // Set Val To Modal
                    }


                    $("#delete-specialisation-btn").on("click", function () {
                        var specialisation = $('#delete-specialisation-btn').val();
                        $("#delete-alert").hide();
                        $("#delete-specialisation-btn").hide();
                        var spinner_container = '<div class="text-center" id="spinner-container"></div>';
                        var spinner = '<div class="spinner-border text-secondary" role="status" style="width: 10rem; height: 10em; border-width:2em;"></div>';
                        $('#delete-modal-content').append(spinner_container);
                        $('#spinner-container').append(spinner);

                        if (req) {
                            req.abort();
                        }

                        req = $.ajax({
                            type: "POST",
                            url: "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>",
                            data: {
                                ajax_delete: true,
                                specialisation: specialisation
                            },
                            success: function () {
                                $('#spinner-container').remove();
                                $("#deleteSpec").modal('hide');
                                var identifier = $("#counter-identifier").val(); // Set Val To Modal

                                console.log($(`#${identifier.replaceAll(" ", "-")}`).val());
                                $(`#${identifier.replaceAll(" ", "-")}`).remove();
                                $("#delete-alert").show();
                                $("#delete-specialisation-btn").show();
                                console.log("delete sucessfully");
                            },
                            error: function () {
                                $("#delete-specialisation-btn").show();
                                console.log("Error");
                            }
                        });


                    });
                </script>
                <!-- bootstrap js link -->
            </body>
        </html>
    <?php
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>
