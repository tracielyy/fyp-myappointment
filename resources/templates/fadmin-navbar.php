<?php
/*
 *  @author: tracieqwynn
 */
?>
<!-- Navbar -->
<!-- Navbar starts here -->
<section id="navbar">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <!-- offcanvas trigger start -->
            <button class="navbar-toggler"  type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- offcanvas trigger end -->
            <a class="navbar-brand ms-2" href="<?php echo FADMIN_WEB; ?>">MyAppointment</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mr-2 mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"  href="#" id="navbarDropdownMenuLink"  role="button"data-bs-toggle="dropdown" aria-expanded="false" >
                            <i class="bi bi-person-badge-fill"></i>
                            <span style="font-size: 14px; font-weight: 600" id="adminID">
                                <?php echo $user->get_adminname(); ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark"  aria-labelledby="navbarDropdownMenuLink" >
                            <li>
                                <a class="dropdown-item" href="<?php echo LOGIN_WEB . '/logout.php'; ?>">
                                    Log Out
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
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
                <!-- CORE -->
                <li>
                    <div class="text-muted medium text-uppercase px-3 my-2">CORE</div>
                </li>
                <li>
                    <a href="<?php echo FADMIN_WEB; ?>" class="nav-link px-3" id="nav-home">
                        <span class="me-2"><i class="bi bi-speedometer2"></i></span>
                        <span>Home</span>
                    </a>
                </li>
                <li class="my-4"><hr class="dropdown-divider" /></li>
                <!-- MANAGE -->
                <li>
                    <div class="text-muted medium text-uppercase px-3">MANAGE</div>
                </li>
                <li>
                    <a class="nav-link px-3 my-2 sidebar-link" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="nav-facility">
                        <span class="me-2"><i class="fas fa-hospital"></i></span>
                        <span>Hospital</span>
                        <span class="right-icon ms-auto">
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="collapse" id="collapseExample">
                        <div>
                            <ul class="navbar-nav ps-3">
                                <li>
                                    <a href="<?php echo FADMIN_WEB . "/views/myfacility.php"; ?>" class="nav-link px-3" id="nav-facility-details">
                                        <span class="me-2"><i class="bi bi-pencil-square"></i></span>
                                        <span>Hospital Details</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo FADMIN_WEB . "/views/specialisations.php"; ?>" class="nav-link px-3" id="nav-specialisation">
                                        <span class="me-2"><i class="bi bi-plus-square"></i></span>
                                        <span>Specialisation</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="<?php echo FADMIN_WEB . "/views/doctors.php"; ?>" class="nav-link px-3 my-2" id="nav-doctors">
                        <span class="me-2"><i class="fas fa-user-md"></i></span>
                        <span>Doctors</span>
                    </a>
                </li>
                <li class="my-4"><hr class="dropdown-divider" /></li>
                <!-- TOOLS -->
                <li>
                    <div class="text-muted medium text-uppercase px-3">TOOLS</div>
                </li>
                <li>
                    <a href="<?php echo FADMIN_WEB . '/views/health-articles.php'; ?>" class="nav-link px-3 my-2" id="nav-health-articles">
                        <span class="me-2"><i class="fas fa-newspaper"></i></span>
                        <span>Health Articles</span>
                    </a>
                </li>
                <li class="my-4"><hr class="dropdown-divider" /></li>
                <li>
                    <div class="text-muted medium text-uppercase px-3">ACCOUNT</div>
                </li>
                <li>
                    <a href="<?php echo FADMIN_WEB . "/views/admin.php"; ?>" class="nav-link px-3 my-2" id="nav-edit-profile">
                        <span class="me-2"><i class="fas fa-user-edit"></i></span>
                        <span>Edit Profile</span>
                    </a>
                </li>
                <li class="my-4"><hr class="dropdown-divider" /></li>
                <li class="ms-2">
                    <h5 class="text-white text-muted small fw-700">
                        &copy; FYP-21-S2-24 (MyAppointment)
                    </h5>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- Off canvas ends here -->


