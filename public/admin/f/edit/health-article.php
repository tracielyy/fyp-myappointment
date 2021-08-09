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
 *  EDIT HEALTH INFO --- (Health Articles) 
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
        if ($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER['REQUEST_METHOD'] == "POST"):

            $validArr = array();

            function valid_vars(string $id): bool|Health_Info {
                $health_info_obj = Health_Info::retrieve_health_info_by_id($id);
                if ($health_info_obj === null):
                    return false;
                endif;
                return $health_info_obj;
            }
            ?><!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <title>Edit Health Article</title>
                    <!-- fontawesome -->
                    <script
                        src="https://kit.fontawesome.com/dcfd5ba5e7.js"
                        crossorigin="anonymous"
                    ></script>
                    <!-- google fonts -->
                    <link rel="preconnect" href="https://fonts.googleapis.com" />
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap" rel="stylesheet"/>
                    <!-- bootstrap cdn link -->
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
                    <!-- bootstrap data table -->
                    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css"/>
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
                    <?php
                    if (!isset($_GET['id'])):
                        ?>
                        <!-- SHOW INVALID PAGE -->
                        <main class="mt-5 pt-1">
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
                        $id = $_GET['id'];
                        # Check if the id exist in database for edit
                        $health_info = valid_vars($id);
                        if (!$health_info):
                            ?>
                            <main class="mt-5 pt-1">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="alert alert-danger" role="alert">
                                            The article you are looking for does not exist.
                                        </div>
                                    </div>
                                </div>
                            </main>
                            <?php
                        else: /* If The Variables Exists In The Database */
                            if ($_SERVER["REQUEST_METHOD"] == "GET"):
                                $health_article = array(
                                    'title' => $health_info->get_title(),
                                    'descriptions' => $health_info->get_descriptions(),
                                );
                            else:
                                $health_article = array(
                                    'title' => '',
                                    'descriptions' => '',
                                );
                            endif;
                            ?>
                            <!-- Current Page (Add Health Article) -->
                            <main class="mt-5 pt-3">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12 text-center fw-700 fs-1">
                                            Edit Health Article
                                        </div>
                                        <div class="col-md-12 text-muted text-center fw-700">
                                            Facility admin @ <span id="facilityName">NUH</span>
                                        </div>
                                    </div>
                                    <div class="row mt-4 ms-auto me-auto" id="new-health-article">
                                        <div class="col-lg-12">
                                            <!-- Black Card Body-->
                                            <div class="card text-start bg-dark" style="max-width: 60rem;">
                                                <div class="card-body text-white">

                                                    <!-- FORM -->
                                                    <form id="add_health_article" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?' . http_build_query($_GET); ?>">
                                                        <!-- Title -->
                                                        <div class="mb-3" id="title-container">
                                                            <label for="specialization" class="form-label">Title: </label>
                                                            <input name ="title" type="text" id="title" class="form-control" style="width: 60%;" value="<?php echo $health_article['title']; ?>"/>
                                                        </div>
                                                        <hr>
                                                        <!-- Descriptions -->
                                                        <div class="mb-3" id="descriptions-container">
                                                            <label for="descriptions" class="form-label">Descriptions: </label>
                                                            <textarea name="descriptions" id="descriptions" rows="5" class="form-control" style="resize:none;" ><?php echo $health_article['descriptions']; ?></textarea>
                                                        </div>
                                                        <hr>
                                                        <!-- Type -->
                                                        <div class="mb-3" id="type-container">
                                                            <label for="type" class="form-label">Type: </label>
                                                            <input name ="type" type="text" id="type" class="form-control shadow-none" style="width: 25%; border:none; cursor:not-allowed;" placeholder="<?php echo $health_info->get_type(); ?>" readonly/>
                                                        </div>
                                                        <!-- Buttons -->
                                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                            <button type="submit" class="btn btn-outline-light me-md-2 mr-2" name="save" id="save-article"> 
                                                                <span><i class="fas fa-save"></i></span>
                                                                <span>Save</span>
                                                            </button>
                                                            <button class="btn btn-outline-danger" id="resetBtn" type="reset">
                                                                <span><i class="bi bi-x-lg"></i></span>
                                                                <span>Reset</span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div><!-- Black Card Body -->
                                        </div>
                                    </div> 
                                </div>
                            </main>
                            <br>
                            <br>
                            <!-- main ends here -->
                            <!-- bootstrap js link -->
                            <script
                                src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                                integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
                                crossorigin="anonymous"
                            ></script>
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
                            <?php
                            /* ERROR MESSAGES */

                            function set_err_msg(array $validArr, array $err_msg): void {
                                foreach ($validArr as $key => $value):
                                    if (!$value):
                                        echo "
                                <script>
                                    $('#{$key}').addClass('is-invalid');
                                    var feedback = \"<div id='{$key}-feedback' class='invalid-feedback'>{$err_msg[$key]}</div>\";
                                    $('#{$key}-container').append(feedback);

                                </script>
                            ";
                                    endif;
                                endforeach;
                            }

                            function remove_err_message(array $validArr): void {
                                foreach ($validArr as $k => $v):
                                    echo "
                            <script>
                                $('#{$k}-feedback').remove();
                            </script>
                            ";
                                endforeach;
                            }

                            if ($_SERVER["REQUEST_METHOD"] == "POST"):

                                if (isset($_POST['save'])):
                                    /* Load Data to Array */
                                    foreach ($_POST as $key => $value) :
                                        if (isset($health_article[$key])) :
                                            $health_article[$key] = htmlspecialchars($value);
                                            $validArr[$key] = False; // Set All Field Validation Check As False
                                            $err_msg[$key] = "";
                                        endif;
                                    endforeach;

                                    ###### -- VALIDATION -- ######
                                    foreach ($health_article as $key => $value):

                                        # Step 1: Check Empty
                                        $value = StringUtils::trim_string($value);
                                        if (!empty($value)):

                                            # Step 2: Other Validations
                                            if ($key == 'type'):

                                                $validArr[$key] = Health_Info_Type::validate_type($value);

                                                # Check For Valid Type Selection
                                                if (!$validArr[$key]):
                                                    $err_msg[$key] = "Invalid Input";
                                                endif;
                                            else:
                                                $validArr[$key] = true;
                                            endif;

                                        else:
                                            # Some Error Message
                                            $err_msg[$key] = strtoupper($key) . " cannot be blank";
                                        endif;

                                    endforeach;
                                    ?>

                                    <script>

                                        console.log("post function is called");
                                    </script>
                                    <?php
                                    ###### -- END VALIDATION -- ######
                                    // If There At Least 1 Failing Condition
                                    if (in_array(FALSE, $validArr)) :
                                        set_err_msg($validArr, $err_msg);
                                        ?>
                                        <script>
                                            $("#title").val("<?php echo $health_article['title']; ?>");
                                            $("#descriptions").val("<?php echo $health_article['descriptions']; ?>");
                                        </script>
                                        <?php
                                    else:
                                        ?>
                                        <script>
                                            $('#new-health-article').html("");
                                            var spinner_container = '<div class="text-center" id="spinner-container"></div>';
                                            var spinner = '<div class="spinner-border" role="status" style="width: 20rem; height: 20rem; border-width:4em;"></div>';
                                            $('#new-health-article').append(spinner_container);
                                            $('#spinner-container').append(spinner);
                                            console.log("post function is called");
                                        </script>
                                        <?php
                                        # Update To Database
                                        Health_Info::update_healthinfo($health_info->get_id(), $health_article['title'], $health_article['descriptions']);
                                        # Reset The Form Values
                                        ArrayCreation::reset_form_arr(array_keys($health_article), $health_article);
                                        # Redirect User To Articles Page
                                        ?>
                                        <script>
                                            window.location.replace(window.location.origin + '<?php echo FADMIN_WEB . "/views/health-articles.php"; ?>');
                                        </script>
                                    <?php
                                    endif;

                                endif; # -- END CHECK FOR SUBMIT BTN TRIGGER
                            endif; # -- END POST REQUEST
                            ?>
                        </body>
                    </html>
                <?php
                endif; # -- END FOR VALID VARS
            endif; # -- END GET ID CHECK ISSET
        endif; # -- END GET & POST REQUEST
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>

