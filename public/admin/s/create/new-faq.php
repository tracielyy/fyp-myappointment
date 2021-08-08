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
            <div class="row">
                <div class="col-lg-12">
                    <div class="card ms-auto me-auto outerCard" style="max-width: 55rem;">
                        <div class="card-body">
                            <h4 class="text-muted text-center small">Add FAQ</h4>
                            <div class="card text-dark innerCard mb-3">
                                <div class="card-title ms-2 mt-2">
                                    <div class="mb-3 row">
                                        <label for="Question" class="col-sm-2 col-form-label">Query Title: </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" style="max-width:90%">
                                        </div>
                                    </div>
                                </div>
                                <hr class="ms-2" style="max-width: 60%;">
                                <div class="card-body">
                                    <div class="mb-3 row">
                                        <label for="answer" class="col-sm-2 col-form-label">Answer: </label>
                                        <div class="col-sm-10">
                                            <textarea name="answer" class="form-control" cols="30" rows="8"">Enter the answer here</textarea>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end" style=" margin-top: 10px;">
                                        <a href="viewFaq.html" class="btn btn-dark me-md-2 mr-2">
                                            <span><i class="fas fa-save"></i></span>
                                            <span>Save</span>
                                        </a>
                                        <button class="btn btn-danger" id="delBtn" type="button" data-bs-toggle="modal" data-bs-target="#delModal">
                                            <span><i class="fas fa-times"></i></span>
                                            <span>Cancel</span>
                                        </button>
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
    </body>
</html>
