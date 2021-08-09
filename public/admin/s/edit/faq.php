
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
 *  EDIT FAQ
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

            function valid_vars(string $id): bool|Faq {
                $faq_info_obj = Faq::retrieve_faq_by_id($id);
                if ($faq_info_obj === null):
                    return false;
                endif;
                return $faq_info_obj;
            }
            ?><!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8" />
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <title>Admin HomePage</title>
                    <!-- fontawesome -->
                    <script src="https://kit.fontawesome.com/dcfd5ba5e7.js"  crossorigin="anonymous"></script>
                    <!-- google fonts -->
                    <link rel="preconnect" href="https://fonts.googleapis.com" />
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap"   rel="stylesheet"/>
                    <!-- bootstrap cdn link -->
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"  integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
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
                        $faq = valid_vars($id);
                        if (!$faq):
                            ?>
                            <main class="mt-5 pt-1">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="alert alert-danger" role="alert">
                                            The FAQ article you are looking for does not exist.
                                        </div>
                                    </div>
                                </div>
                            </main>
                            <?php
                        else: /* If The Variables Exists In The Database */
                            if ($_SERVER["REQUEST_METHOD"] == "GET"):
                                $faq_info = array(
                                    'question' => $faq->get_question(),
                                    'answer' => $faq->get_answer(),
                                );
                            else:
                                $faq_info = array(
                                    'question' => '',
                                    'answer' => '',
                                );
                            endif;
                            ?>
                            <!-- main section starts here -->
                            <main class="mt-5 pt-3">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card ms-auto me-auto outerCard" style="max-width: 55rem;">
                                            <div class="card-body">
                                                <h4 class="text-muted text-center small">Edit FAQ</h4>
                                                <div class="card text-dark innerCard mb-3" id="edit-faq">
                                                    <form id="edit-faq-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?' . http_build_query($_GET); ?>">
                                                        <div class="card-title ms-2 mt-2">
                                                            <!-- Question -->
                                                            <div class="mb-3 row">
                                                                <label for="Question" class="col-sm-2 col-form-label">Query Title: </label>
                                                                <div class="col-sm-10" id="question-container">
                                                                    <input type="text" class="form-control" id="question" name="question" style="max-width:90%" value="<?php echo $faq_info['question']; ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr class="ms-2" style="max-width: 60%;">
                                                        <div class="card-body">
                                                            <!-- Answer -->
                                                            <div class="mb-3 row">
                                                                <label for="answer" class="col-sm-2 col-form-label">Answer: </label>
                                                                <div class="col-sm-10" id="answer-container">
                                                                    <textarea name="answer" id="answer" class="form-control" cols="30" rows="8"><?php echo $faq_info['answer']; ?></textarea>
                                                                </div>
                                                            </div>
                                                            <!-- Button -->
                                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end" style=" margin-top: 10px;">
                                                                <button class="btn btn-dark me-md-2 mr-2" name="save_faq">
                                                                    <span><i class="fas fa-save"></i></span>
                                                                    <span>Save</span>
                                                                </button>
                                                                <a href="<?php echo SADMIN_WEB . "/views/faqs.php"; ?>" class="btn btn-danger" id="delBtn">
                                                                    <span><i class="fas fa-times"></i></span>
                                                                    <span>Cancel</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </main>
                            <!-- main ends here -->
                            <script>
                                $('#nav-faq').addClass('active');
                            </script>
                            <!-- bootstrap js link -->
                            <script
                                src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                                integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
                                crossorigin="anonymous"
                            ></script>
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

                                if (isset($_POST['save_faq'])):
                                    /* Load Data to Array */
                                    foreach ($_POST as $key => $value) :
                                        if (isset($faq_info[$key])) :
                                            $faq_info[$key] = htmlspecialchars($value);
                                            $validArr[$key] = False; // Set All Field Validation Check As False
                                            $err_msg[$key] = "";
                                        endif;
                                    endforeach;

                                    ###### -- VALIDATION -- ######
                                    foreach ($faq_info as $key => $value):

                                        # Check Empty
                                        $value = StringUtils::trim_string($value);
                                        $faq_info[$key] = $value;
                                        if (!empty($value)):
                                            $validArr[$key] = true;
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
                                            $("#question").val("<?php echo $faq_info['question']; ?>");
                                            $("#answer").val("<?php echo $faq_info['answer']; ?>");
                                        </script>
                                        <?php
                                    else:
                                        ?>
                                        <script>
                                            $('#edit-faq').html("");
                                            var spinner_container = '<div class="text-center" id="spinner-container"></div>';
                                            var spinner = '<div class="spinner-border m-4" role="status" style="width: 20rem; height: 20rem; border-width:3em;"></div>';
                                            $('#edit-faq').append(spinner_container);
                                            $('#spinner-container').append(spinner);
                                            console.log("post function is called");
                                        </script>
                                        <?php
                                        # Update To Database
                                        Faq::update_faq($faq->get_id(), $faq_info['question'], $faq_info['answer']);
                                        # Reset The Form Values
                                        ArrayCreation::reset_form_arr(array_keys($faq_info), $faq_info);
                                        # Redirect User To FAQ Articles Page
                                        ?>
                                        <script>
                                            window.location.replace(window.location.origin + '<?php echo SADMIN_WEB . "/views/faqs.php"; ?>');
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