<!--

    Author: FYP-21-S2-24

-->

<!-- The navigation is added at server level  (PHP File Need To Contain PHP Code) -->
<!-- Will Need To Add Logic To Make Sure User Is Logged In Before Displaying Logout Button. -->

<link href="/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<style>
    body {
        padding-top: 80px;
    }

</style>



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
                <li class="nav-item">
                    <a class="nav-link navbutton<?php
                    if ($pageName == 'createappointment') {
                        echo 'active';
                    }
                    ?>" href="<?php echo APPT_WEB; ?>/create.php">Create an Appointment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navbutton<?php
                    if ($pageName == 'viewappointment') {
                        echo 'active';
                    }
                    ?>"
                       href="<?php echo APPT_WEB; ?>">View Appointment</a> <!-- HREF NEED TO BE CHANGED -->
                </li>
                <li class="nav-item">
                    <a class="nav-link navbutton<?php
                    if ($pageName == 'viewappointment') {
                        echo 'active';
                    }
                    ?>"
                       href="#">Search Medicine</a> <!-- HREF NEED TO BE CHANGED -->
                </li>
                <li class="nav-item">
                    <a class="nav-link navbutton<?php
                    if ($pageName == 'viewappointment') {
                        echo 'active';
                    }
                    ?>"
                       href="#">Conditions and Treatements</a> <!-- HREF NEED TO BE CHANGED -->
                </li>
            </ul>
            <div style="display: inline-block;">
                <ul class="navbar-nav pe-5">
                    <li class="nav-item dropdown pe-5">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="mx-2"style="border-radius: 50%;" src="https://via.placeholder.com/30" />
                            <label class="text-light mt-1"><?php echo $user->get_firstname(); ?></label>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-secondary dropdown-menu-end" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item" href="./profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo APPT_WEB; ?>">View Appointments</a></li>
                            <li><a class="dropdown-item" href="<?php echo LOGIN_WEB . "/logout.php"; ?>">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</nav>


<script src="/docs/5.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
</script>
<!-- JavaScript code -->