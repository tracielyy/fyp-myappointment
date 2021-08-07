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
    <!-- Navbar -->
    <!-- Navbar starts here -->
    <section id="navbar">
      <nav class="navbar navbar-expand-lg navbar-light bg-warning fixed-top">
        <div class="container-fluid">
          <!-- offcanvas trigger start -->
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasExample"
            aria-controls="offcanvasExample"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <!-- offcanvas trigger end -->
          <a class="navbar-brand ms-2" href="SuperAdminhomepage.html">SuperAdmin | <span style="font-weight: 600;">MyAppointment</span></a>
        </div>
      </nav>
    </section>
    <!-- Navbar Ends here -->

    <!-- Off canvas starts here -->
    <div
      class="offcanvas offcanvas-start sidebar-nav bg-dark text-white"
      tabindex="-1"
      id="offcanvasExample"
      aria-labelledby="offcanvasExampleLabel"
    >
      <div class="offcanvas-body p-0">
        <nav class="navbar-dark">
          <ul class="navbar-nav">
              <br>
            <li>
              <div class="text-muted medium text-uppercase px-3">MANAGE</div>
            </li>
            <li>
              <a href="SuperAdminhomepage.html" class="nav-link px-3 my-2 sidebar-link active">
                <span class="me-2"><i class="fas fa-hospital"></i></span>
                <span>Medical Facilites</span>
              </a>
            </li>
            <li>
              <a href="viewFAQ.html" class="nav-link px-3 my-2">
                <span class="me-2"><i class="fas fa-question-circle"></i></span>
                <span>FAQ</span>
              </a>
            </li>
            <li class="my-4"><hr class="dropdown-divider" /></li>
            <li>
              <div class="text-muted medium text-uppercase px-3">TOOLS</div>
            </li>
            <li>
              <a href="editSuperAdmin.html" class="nav-link px-3 my-2">
                <span class="me-2"><i class="fas fa-user-edit"></i></span>
                <span>Edit Profile</span>
              </a>
            </li>
            <li class="my-3"><hr class="dropdown-divider" /></li>
            <li>
                <a
                class="nav-link px-3 mb-2 mt-1"
                href="#"
              >
                <span class="me-2"> <i class="fas fa-sign-out-alt"></i></span>
                <span style="font-weight: 600;">Log Out</span>
              </a>
            </li>
            <hr class="my-4">
            <li class="ms-2">
              <h5 class="text-white text-muted small fw-600">
                &copy; MyAppointment 2021
              </h5>
            </li>
          </ul>
        </nav>
      </div>
    </div>
    <!-- Off canvas ends here -->
    <!-- main section starts here -->
    <main class="mt-5 pt-3">
      <h2 class="text-center my-2">Modify Medical Facility</h2>
      <hr class="bg-dark w-75 ms-auto me-auto">
      <div class="row">
          <div class="col-lg-12">
              <div class="card ms-auto me-auto outerCard" style="max-width: 55rem;">
                <div class="card-body">
                    <div class="card text-dark innerCard mb-3">
                        <div class="card-title ms-2 mt-2">
                            <h4 style="font-weight: 600; font-size: 1.5rem;">National University Hospital</h4>
                            <div class="d-grid gap-1 d-md-flex justify-content-md-start" style=" margin-top: 10px;">
                              <input type="text" readonly class="form-control-plaintext text-muted" name="adminName" id="adminName" value="Admin-nuh" style="font-weight: 600; max-width: 10rem;">
                                <a href="editAdmin.html" class="me-md-2 mr-2 editAdm" data-bs-toggle="tooltip" data-bs-placement="right" title="Edit Admin">
                                    <span><i class="fas fa-pen-square fa-2x"></i></span>
                                </a>
                            </div>
                        </div>
                        <hr class="ms-2" style="max-width: 60%;">
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label for="address" class="col-sm-2 col-form-label">Address: </label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control-plaintext" id="staticAddress" value="5 Lower Kent Ridge Rd, Singapore 119074">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="contact" class="col-sm-2 col-form-label">Contact: </label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control-plaintext" id="staticContact" value="67795555">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="openinghour" class="col-sm-2 col-form-label">Opening Hour: </label>
                                <div class="col-sm-10">
                                  <input type="time" style="margin-top: 5px; border: 1px solid #eeeded; background-color: #eeeded;">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="closinghour" class="col-sm-2 col-form-label">Closing Hour: </label>
                                <div class="col-sm-10">
                                    <input type="time" style="margin-top: 5px; border: 1px solid #eeeded; background-color: #eeeded;">
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end" style=" margin-top: 10px;">
                                <a href="viewMF.html" class="btn btn-success me-md-2 mr-2">
                                    <span><i class="fas fa-save"></i></span>
                                    <span>Save</span>
                                </a>
                                <a href="viewMF.html" class="btn btn-danger" id="delBtn">
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
