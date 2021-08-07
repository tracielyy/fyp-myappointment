<?php
/*
 *  @author: tracieqwynn
 */
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';

/*
 * CREATE FACILITY
 */
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Admin HomePage</title>
        <!-- fontawesome -->
        <script
            src="https://kit.fontawesome.com/dcfd5ba5e7.js"
            crossorigin="anonymous"
        ></script>
        <!-- google fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap"
            rel="stylesheet"
            />
        <!-- bootstrap cdn link -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
            crossorigin="anonymous"
            />
        <!-- bootstrap data table -->
        <link
            rel="stylesheet"
            href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css"
            />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"
            />
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
        <!-- main section starts here -->
        <main class="mt-5 pt-3">
            <h2 class="text-center my-2">Add Medical Facility</h2>
            <hr class="bg-dark w-75 ms-auto me-auto">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card ms-auto me-auto outerCard" style="max-width: 55rem;">
                        <div class="card-body">
                            <div class="card text-dark innerCard">
                                <!-- Facility Admin Section -->
                                <div class="card-title ms-3 mt-2">
                                    <h5 class="text-muted small">Admin Details</h5>
                                    <!-- Admin Name -->
                                    <div class="mb-3 row">
                                        <label for="adminName" class="col-sm-2 col-form-label">Admin Name: </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" style="max-width:20rem;">
                                        </div>
                                    </div>
                                    <!-- Email -->
                                    <div class="mb-3 row">
                                        <label for="adminEmail" class="col-sm-2 col-form-label">Email: </label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" style="max-width:20rem;">
                                        </div>
                                    </div>
                                    <!-- Password (Might Not Need as we can randomly generate password -->
                                    <div class="mb-3 row">
                                        <label for="adminPass" class="col-sm-2 col-form-label">Password: </label>
                                        <div class="col-sm-10">
                                            <input type="Password" class="form-control" style="max-width:20rem;">
                                        </div>
                                    </div>
                                </div>
                                <!-- Facility Section -->
                                <hr class="ms-3" style="max-width: 90%;">
                                <div class="card-body">
                                    <h5 class="text-muted small mb-3">Facility Details</h5>
                                    <div class="mb-3 row">
                                        <label for="facility-icon" class="col-sm-2 col-form-label">Facility Icon: </label>
                                        <div class="col-sm-10">
                                            <input class="form-control form-control-sm" id="facility-icon" type="file" />
                                        </div>
                                    </div>
                                    <!-- Facility Name -->
                                    <div class="mb-3 row">
                                        <label for="FacilityName" class="col-sm-2 col-form-label">Facility Name: </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <!-- Address -->
                                    <div class="mb-3 row">
                                        <label for="address" class="col-sm-2 col-form-label">Address: </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="staticAddress">
                                        </div>
                                    </div>
                                    <!-- Contact Number -->
                                    <div class="mb-3 row">
                                        <label for="contact" class="col-sm-2 col-form-label">Contact: </label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" id="staticContact">
                                        </div>
                                    </div>
                                    <!-- Opening Hour -->
                                    <div class="mb-3 row">
                                        <label for="openinghour" class="col-sm-2 col-form-label">Opening Hour: </label>
                                        <div class="col-sm-10">
                                            <input type="time" style="margin-top: 5px; border: 1px solid #eeeded;">
                                        </div>
                                    </div>
                                    <!-- Closing Hour -->
                                    <div class="mb-3 row">
                                        <label for="closinghour" class="col-sm-2 col-form-label">Closing Hour: </label>
                                        <div class="col-sm-10">
                                            <input type="time" style="margin-top: 5px; border: 1px solid #eeeded;">
                                        </div>
                                    </div>
                                    <!-- Buttons -->
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end" style=" margin-top: 10px;">
                                        <button type="submit" class="btn btn-success me-md-2 mr-2" name="add_facility" id="add-facility"
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
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- main ends here -->

        <!-- bootstrap js link -->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"
        ></script>
        <!-- js code for the bootstrap tooltip -->
        <script>
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        </script>
    </body>
</html>
