<?php
/*
 *  @author: tracieqwynn
 */
?>
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
                    <a href="homepage.html" class="nav-link px-3">
                        <span class="me-2"><i class="bi bi-speedometer2"></i></span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="my-4"><hr class="dropdown-divider" /></li>
                <!-- MANAGE -->
                <li>
                    <div class="text-muted medium text-uppercase px-3">MANAGE</div>
                </li>
                <li>
                    <a
                        class="nav-link px-3 my-2 sidebar-link"
                        data-bs-toggle="collapse"
                        href="#collapseExample"
                        role="button"
                        aria-expanded="false"
                        aria-controls="collapseExample"
                        >
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
                                    <a href="manageHospital.html" class="nav-link px-3">
                                        <span class="me-2"><i class="bi bi-pencil-square"></i></span>
                                        <span>Hospital Details</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="Specialization.html" class="nav-link px-3">
                                        <span class="me-2"><i class="bi bi-plus-square"></i></span>
                                        <span>Specialization</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="manageDoctor.html" class="nav-link px-3 my-2">
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
                    <a href="<?php echo FADMIN_WEB. '/views/health-articles.php';?>" class="nav-link px-3 my-2 active">
                        <span class="me-2"><i class="fas fa-newspaper"></i></span>
                        <span>Health Articles</span>
                    </a>
                </li>
                <li class="my-4"><hr class="dropdown-divider" /></li>
                <li class="ms-2">
                    <h5 class="text-white text-muted small fw-700">
                        &copy; MyAppointment 2021
                    </h5>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- Off canvas ends here -->
