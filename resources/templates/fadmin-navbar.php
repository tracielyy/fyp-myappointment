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



