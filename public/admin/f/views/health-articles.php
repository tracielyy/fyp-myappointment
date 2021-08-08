<?php
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';

require_once ENUMS_PATH . '/Health_Info_Type.php';
require_once HINFO_MOD . '/Health_Info.php';
require_once UTIL_MOD . '/StringUtils.php';

require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';

require_once FACILITY_MOD . '/Medical_Facility.php';

/*
 *  DISPLAY ALL HEALTH INFO --- (Health Articles) 
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

        if ($_SERVER['REQUEST_METHOD'] == "POST"):

            // DELETE BUTTON    
            if (isset($_POST["ajax_delete"]) && isset($_POST['id'])):
                Health_Info::delete_healthinfo($_POST["id"]);
            endif;

        endif;
        $facility = $user->get_facility();
        $all_health_articles = Health_Info::retrieve_all_healthinfo();
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>Health Articles</title>
                <!-- fontawesome -->
                <script src="https://kit.fontawesome.com/dcfd5ba5e7.js" crossorigin="anonymous"></script>
                <!-- google fonts -->
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap" rel="stylesheet"/>
                <!-- bootstrap cdn link -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
                <!-- bootstrap data table -->
                <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css"/>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
                <!-- jQuery -->
                <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
                <!-- Prevent Form Resubmission -->
                <script>
                    if (window.history.replaceState) {
                        window.history.replaceState(null, null, window.location.href);
                    }
                </script>
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
                    #title{
                        font-weight: 600;
                        margin-bottom: .625rem;
                    }
                    .desc_header{
                        text-decoration: underline;
                        font-weight: 600;
                    }
                    .Url{
                        text-decoration: underline;
                        font-weight: 600;
                    }
                    #ArticleUrl{
                        margin-bottom: 10px;
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
                <!-- Current Page Display -->
                <main class="mt-5 pt-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 text-center fw-700 fs-1">Health Articles</div>
                            <div class="col-md-12 text-muted text-center fw-700">
                                Facility admin @ <span id="facilityName"><?php echo StringUtils::get_acronym($facility->get_facilityname()); ?> </span>
                            </div>
                        </div>
                        <div class="row mt-4 ms-auto me-auto">
                            <div class="card text-start text-dark bg-dark" style="max-width: 60rem;">
                                <div class="card-body">
                                    <div class="col-lg-12">
                                        <!-- ADD HEALTH ARTICLE -->
                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <a href="<?php echo FADMIN_WEB . '/create/new-health-article.php'; ?>" class="btn btn-outline-light me-md-2 mr-2">
                                                <span><i class="bi bi-plus-lg"></i></span>
                                            </a>
                                        </div>
                                        <!-- DISPLAY HEALTH ARTICLES (Loop) -->
                                        <?php
                                        foreach ($all_health_articles as $article):
                                            ?>
                                            <div class="card my-2" id="<?php echo $article->get_id(); ?>">
                                                <div class="card-body">
                                                    <h3 id="title"><?php echo $article->get_title(); ?></h3>
                                                    <h4 id="type" class="text-start small text-muted"><?php echo $article->get_type(); ?></h4>
                                                    <h6 class="desc_header"></h6>
                                                    <p><?php echo $article->get_descriptions(); ?></p>
                                                    <!-- Buttons -->
                                                    <div class="d-grid gap-2 d-md-flex justify-content-between">
                                                        <!-- edit button -->
                                                        <a href="<?php echo FADMIN_WEB . "/edit/health-article.php?id=" . $article->get_id(); ?>" class="btn btn-success me-md-2 mr-2">
                                                            <span><i class="fa fa-edit"></i></span>
                                                            <span id="articleEdit">Edit</span>
                                                        </a>
                                                        <!-- delete button (trigger modal) -->
                                                        <button value="<?php echo $article->get_id(); ?>" onclick="delete_article(this.value)" class="btn btn-danger me-md-2 mr-2" data-bs-toggle="modal" data-bs-target="#deleteArticle">
                                                            <span><i class="bi bi-x-lg"></i></span>
                                                            <span id="articleDelete">Delete</span>
                                                        </button>
                                                    </div><!-- end buttons -->
                                                </div>
                                            </div><!-- END DISPLAY ONE ARTICLE -->
                                            <?php
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                </main>
                <!-- main section ends here -->
                <!-- DELETE MODAL -->
                <section>
                    <div class="modal fade" id="deleteArticle" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="margin-left:30rem;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Delete Article</h5>
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
                                <!-- Triggering Delete (Health Article) -->
                                <div class="modal-footer">
                                    <button id="delete-article-btn" type="button" class="btn btn-danger" name="delete"><i class="fas fa-trash"></i> Delete</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </section><!-- END DELETE MODAL -->
                <script>
                    // -- Pass Information To Modal
                    function delete_article(article_id) {
                        // -- Testing
                        console.log(article_id);

                        // jQuery Calls To Set The Modal Information 
                        $("#delete-article-btn").val(article_id); // Set ID To Btn
                    }


                    $("#delete-article-btn").on("click", function () {
                        var id = $('#delete-article-btn').val();
                        $("#delete-alert").hide();
                        var spinner_container = '<div class="text-center" id="spinner-container"></div>';
                        var spinner = '<div class="spinner-border text-secondary" role="status" style="width: 10rem; height: 10em; border-width:2em;"></div>';
                        $('#delete-modal-content').append(spinner_container);
                        $('#spinner-container').append(spinner);

                        req = $.ajax({
                            type: "POST",
                            url: "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>",
                            data: {
                                ajax_delete: true,
                                id: id
                            },
                            success: function () {
                                $('#spinner-container').remove();
                                $("#deleteArticle").modal('hide');
                                $("#" + id).remove();
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
                <script
                    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
                    crossorigin="anonymous"
                ></script>

            </body>
        </html>
    <?php
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>