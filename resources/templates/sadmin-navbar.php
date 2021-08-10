<?php
/*
 *  @author: tracieqwynn
 */
?>
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
            <a class="navbar-brand ms-2" href="<?php echo SADMIN_WEB; ?>">SuperAdmin | <span style="font-weight: 600;">MyAppointment</span></a>
        </div>
    </nav>
</section>
<!-- Navbar Ends here -->

<!-- Off canvas starts here -->
<div class="offcanvas offcanvas-start sidebar-nav bg-dark text-white"
     tabindex="-1"
     id="offcanvasExample"
     aria-labelledby="offcanvasExampleLabel"
     >
    <div class="offcanvas-body p-0">
        <nav class="navbar-dark">
            <ul class="navbar-nav">
                <br>
                <!-- Manage -->
                <li>
                    <div class="text-muted medium text-uppercase px-3">MANAGE</div>
                </li>
                <li>
                    <a href="<?php echo SADMIN_WEB; ?>" class="nav-link px-3 my-2 sidebar-link" id="nav-facility">
                        <span class="me-2"><i class="fas fa-hospital"></i></span>
                        <span>Medical Facilites</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo SADMIN_WEB . "/views/faqs.php"; ?>" class="nav-link px-3 my-2" id="nav-faq">
                        <span class="me-2"><i class="fas fa-question-circle"></i></span>
                        <span>FAQ</span>
                    </a>
                </li>
                <li class="my-4"><hr class="dropdown-divider" /></li>
                <li>
                    <div class="text-muted medium text-uppercase px-3">TOOLS</div>
                </li>
                <li>
                    <a href="<?php echo SADMIN_WEB . "/views/super-admin.php"; ?>" class="nav-link px-3 my-2" id="nav-edit-profile">
                        <span class="me-2"><i class="fas fa-user-edit"></i></span>
                        <span>Edit Profile</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo SADMIN_WEB . "/views/users.php"; ?>" class="nav-link px-3 my-2" id="nav-users">
                        <span class="me-2"><i class="fas fa-user"></i></span>
                        <span>View Users</span>
                    </a>
                </li>
                <li class="my-3"><hr class="dropdown-divider" /></li>
                <li>
                    <a class="nav-link px-3 mb-2 mt-1"  href="<?php echo LOGIN_WEB . '/logout.php'; ?>">
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