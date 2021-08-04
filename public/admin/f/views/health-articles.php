<?php
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';

require_once ENUMS_PATH . '/Health_Info_Type.php';
require_once HINFO_MOD . '/Health_Info.php';
require_once UTIL_MOD . '/StringUtils.php';

/*
 *  DISPLAY ALL HEALTH INFO --- (Health Articles) 
 */

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
                        Facility admin @ <span id="facilityName">NUH</span>
                    </div>
                </div>
                <div class="row mt-4 ms-auto me-auto">
                    <div class="card text-start text-dark bg-dark" style="max-width: 60rem;">
                        <div class="card-body">
                            <div class="col-lg-12">
                                <!-- ADD HEALTH ARTICLE -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="<?php echo FADMIN_WEB . '/create/add-health-article.php'; ?>" class="btn btn-outline-light me-md-2 mr-2">
                                        <span><i class="bi bi-plus-lg"></i></span>
                                    </a>
                                </div>
                                <!-- DISPLAY HEALTH ARTICLES  (Loop) -->
                                <?php
                                foreach ($all_health_articles as $article):
                                    ?>
                                    <div class="card my-2" id="healthArticle1">
                                        <div class="card-body">
                                            <h3 id="title"><?php echo $article->get_title(); ?></h3>
                                            <h4 id="type" class="text-start small text-muted"><?php echo $article->get_type(); ?></h4>
                                            <h6 class="desc_header"></h6>
                                            <p><?php echo $article->get_descriptions(); ?></p>
                                            <!-- Buttons -->
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                                <!-- edit button -->
                                                <a href="#" class="btn btn-success me-md-2 mr-2">
                                                    <span id="articleEdit">Edit</span>
                                                </a>
                                                <!-- delete button -->
                                                <a href="#" class="btn btn-danger me-md-2 mr-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                    <span id="articleDelete">Delete</span>
                                                </a>
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
        <!-- modal starts here -->
        <section>
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="margin-left:30rem;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Delete Article</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger" role="alert">
                                <span>
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    This action is irrevocable.
                                </span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger"> <i class="fas fa-trash"></i> Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- bootstrap js link -->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
