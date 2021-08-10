<?php
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';

require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';

require_once FACILITY_MOD . '/Medical_Facility.php';

require_once UTIL_MOD . '/Regex.php';
require_once UTIL_MOD . '/StringUtils.php';

require_once EMAIL_MOD . '/EmailTemplate.php';
require_once EMAIL_MOD . '/EmailVerify.php';

require_once SECURE_MOD . '/Security.php';
require_once SECURE_MOD . '/ValidateIC.php';

/*
 * VIEW LIST OF ALL THE DOCTORS THAT IS UNDER THE FACILITY (FACILITY ADMIN)
 */

if (!isset($_SESSION['user'])):
    header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
else:
    $user = unserialize((string) $_SESSION["user"]);
    $user_type = $user->get_usertype();
    $user_email = $user->get_email();
    $user_facility = $user->get_facility();
    if (!User_Type::check_user_type(User_Type::FACIILITY_ADMIN, $user_type)):
        header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
    else:
        $facility = $user->get_facility();
        $all_practitioners = Medical_Personnel::retrieve_personnel_by_facility_spec($user_facility->get_facilityid(), true);

        $specialisations = $facility->get_specialisations();
        sort($specialisations);

        function delete_doctor(string $email): void {
            $doc_id = Account_User::retrieve_user_doc_id($email);
            Medical_Personnel::delete_medical_personnel($doc_id);
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST"):

            // DELETE BUTTON    
            if (isset($_POST["ajax_delete"]) && isset($_POST['email'])):
                delete_doctor($_POST['email']);
            endif;

        endif;
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Manage Doctors</title>
                <!-- fontawesome -->
                <script src="https://kit.fontawesome.com/dcfd5ba5e7.js"  crossorigin="anonymous"></script>
                <!-- google fonts -->
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap" rel="stylesheet"  />
                <!-- bootstrap cdn link -->
                <link
                    href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
                    rel="stylesheet"
                    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
                    crossorigin="anonymous"
                    />
                <!-- bootstrap data table -->
                <link rel="stylesheet"  href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css"  />
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"   />
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
                        font-family: 'Poppins', sans-serif;
                    }
                    /* defining some variables for the offcanvas */
                    :root{
                        --offcanvas-width: 270px;
                        --topNavBarHeight: 56px;
                    }
                    .sidebar-nav{
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
                    img{
                        height: 80px;
                        width: 100px;
                    }
                    /* make the offcanvas visible on the large screens */
                    @media (min-width: 992px) {
                        body{
                            overflow: auto !important;
                        }

                        .Offcanvas-backdrop::before{
                            display: none;
                        }
                        .sidebar-nav{
                            transform: none;
                            visibility: visible !important;
                            top: var(--topNavBarHeight);
                            height: calc(100% - var(--topNavBarHeight));
                        }
                        main{
                            margin-left: var(--offcanvas-width);
                        }
                    }
                    @media (max-width:992px){
                        .col-lg-4{
                            margin-bottom: 20px;
                        }
                    }
                    .d-flex{
                        border-radius: 30px;
                    }
                    .AD{
                        margin-left: 10px;
                    }
                </style>
            </head>
            <body>
                <!-- NavBar  -->
                <?php require_once TEMPLATES_PATH . "/fadmin-navbar.php"; ?>
                <!-- Current Page (View Doctors) -->
                <main class="mt-5 pt-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 text-center fw-700 fs-1">
                                Manage Doctors
                            </div>
                            <div class="col-md-12 text-muted text-center fw-700">
                                Facility admin @ <span id="facilityName"><?php echo StringUtils::get_acronym($facility->get_facilityname()); ?></span>
                            </div>
                        </div>
                        <div class="row mt-4 mb-2 mb-lg-0">
                            <div class="col-md-12 text-center me-auto my-2 p-3">
                                <span>Medical Facility: </span>
                                <!-- id for the backend facility name -->
                                <span id="facilityName"><?php echo $user->get_facility()->get_facilityname(); ?></span>
                            </div>
                            <!-- add doctor button -->
                            <div class="col-md-12 text-center me-auto my-2 p-3">
                                <a href="<?php echo FADMIN_WEB . "/create/new-doctor.php"; ?>" class="btn btn-dark btn-lg me-3">
                                    <i class="fas fa-user-plus"></i><span class="AD">Add Doctor</span>
                                </a>
                            </div>

                            <!-- add doctor button ends -->
                            <div class="row mt-4 mb-2 mb-lg-0">
                                <!-- doctor profile card -->
                                <?php
                                $count = 0;
                                ksort($all_practitioners); # -- Sort Specialisation ASC
                                foreach ($all_practitioners as $spec => $practitioners):
                                    usort($practitioners, array("Medical_Personnel", "cmp_obj")); # -- Sort By firstname then lastname
                                    foreach ($practitioners as $p):
                                        $count++;
                                        ?>
                                        <!-- ONE DOCTOR -->
                                        <div class="col-md-4 mt-2" id="<?php echo $count; ?>">
                                            <div class="shadow d-flex justify-content-center align-items-center p-3 bg-dark rounded-lg flex-column">
                                                <div class="dr-name my-1">
                                                    <h3 class="text-white" id="drName">Dr. <?php echo $p->get_fullname(); ?></h3>
                                                </div>
                                                <div class="info mb-2">
                                                    <h6 class="dr-title text-white"><?php echo $p->get_specialisation(); ?></h6>
                                                </div>
                                                <!-- Delete Button -->
                                                <button value="<?php echo $p->get_email(); ?>" onclick="delete_doctor(this.value, '<?php echo $p->get_fullname(); ?>', '<?php echo $count; ?>')" class="btn btn-outline-danger btn-md" data-bs-toggle="modal" data-bs-target="#deleteDoctor">
                                                    <span><i class="bi bi-x-lg"></i></span>
                                                    <span id="articleDelete">Delete</span>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- END OF ONE DOCTOR CARD -->
                                        <?php
                                    endforeach;
                                endforeach;
                                ?>
                                <!-- doctor profile cards end -->
                            </div>
                        </div>
                </main>
                <!-- modal starts here -->
                <section>
                    <!-- DELETE MODAL -->
                    <div class="modal fade" id="deleteDoctor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-fullscreen-md" style="margin-left: 30rem;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Delete Dr. <span id="doc-name"></span></h5>
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
                                <!-- Triggering Delete (Doctor) -->
                                <div class="modal-footer">
                                    <input type="hidden" id="counter-identifier"/>
                                    <button id="delete-doctor-btn" type="button" class="btn btn-danger" name="delete"> <i class="fas fa-trash"></i> Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section><!-- END DELETE MODAL -->
                <br>
                <script>
                    $('#nav-doctors').addClass('active');

                    var req = null;

                    // -- Pass Information To Modal
                    function delete_doctor(email, name, count_identifier) {
                        // -- Testing
                        console.log(email + " " + name);

                        // jQuery Calls To Set The Modal Information 
                        $("#delete-doctor-btn").val(email); // Set ID To Btn
                        $("#counter-identifier").val(count_identifier); // Set Val To Modal

                        $("#doc-name").html(name); // Set IName

                    }


                    $("#delete-doctor-btn").on("click", function () {
                        var email = $('#delete-doctor-btn').val();
                        $("#delete-alert").hide();
                        $("#delete-doctor-btn").show();
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
                                email: email
                            },
                            success: function () {
                                $('#spinner-container').remove();
                                $("#deleteDoctor").modal('hide');
                                var identifier = $("#counter-identifier").val(); // Set Val To Modal

                                $(`#${identifier.replaceAll(" ", "-")}`).remove();
                                $("#delete-doctor-btn").show();

                                $("#delete-alert").show();
                                console.log("delete sucessfully");
                            },
                            error: function () {
                                console.log("Error");
                            }
                        });


                    });
                </script>
                <!-- bootstrap js link -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
            </body>
        </html>
    <?php
    endif; # -- END USER CHECK
endif; # -- END SESSION CHECK
?>