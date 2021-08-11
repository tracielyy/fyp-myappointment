<!--

    Author: FYP-21-S2-24

-->

<!-- The navigation is added at server level  (PHP File Need To Contain PHP Code) -->
<!-- Will Need To Add Logic To Make Sure User Is Logged In Before Displaying Logout Button. -->
<script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
<style>
    body {
        padding-top: 70px;
    }

</style>

<?php $usertype = $user->get_usertype();

if($usertype == "Medical Personnel"){};
?>



<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-primary py-3 shadow" style="border-radius:0px;">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">FYP-21-S2-24</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAppt"
                aria-controls="navbarAppt" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarAppt">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link navbutton <?php
                    if ($pageName == 'homepage') {
                        echo 'active';
                    }
                    ?>" aria-current="page"
                       href="/">Home</a>
                </li>
                <?php if($usertype == "Patient"): ?>
                <li class="nav-item">
                    <a class="nav-link navbutton <?php
                    if ($pageName == 'createappointment') {
                        echo 'active';
                    }
                    ?>" href="<?php echo APPT_WEB; ?>/create.php">Create an Appointment</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link navbutton <?php
                    if ($pageName == 'viewappointment') {
                        echo 'active';
                    }
                    ?>"
                       href="<?php echo APPT_WEB; ?>">View Appointment</a> <!-- HREF NEED TO BE CHANGED -->
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link navbutton <?php
                    if ($pageName == 'medicinesearch') {
                        echo 'active';
                    }
                    ?>"
                       href="/medicinesearch.php">Search Medicine</a> <!-- HREF NEED TO BE CHANGED -->
                </li>

                <li class="nav-item">
                    <a class="nav-link navbutton <?php
                    if ($pageName == 'conditionsearch') {
                        echo 'active';
                    }
                    ?>"
                       href="/conditionsearch.php">Conditions and Treatments</a> <!-- HREF NEED TO BE CHANGED -->
                </li>
            </ul>
            <div style="display: inline-block;">
                <ul class="navbar-nav pe-5">
                    <li class="nav-item dropdown pe-5">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                           <i class="fas fa-user fa-lg me-2"></i>
                            <label class="text-light mt-1"><?php echo $user->get_firstname(); ?></label>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-secondary dropdown-menu-end" aria-labelledby="navbarDarkDropdownMenuLink">
                        <?php if($usertype == "Medical Personnel"){echo '<li><a class="dropdown-item" href="'.DOC_WEB.'">View Dashboard</a></li>';} ?>
                            <li><a class="dropdown-item" href="<?php echo ACC_WEB. '/profile.php';?>">Profile</a></li>
                        <?php if($usertype == "Patient"){echo '<li><a class="dropdown-item" href="'.APPT_WEB.'">View Appointments</a></li>';} ?>
                            <li><a class="dropdown-item" href="<?php echo LOGIN_WEB . "/logout.php"; ?>">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</nav>

<!-- JavaScript code -->