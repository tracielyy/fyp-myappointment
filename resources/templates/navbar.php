<!--

    Author: FYP-21-S2-24

-->

<!-- The navigation is added at server level  (PHP File Need To Contain PHP Code) -->
<!-- Will Need To Add Logic To Make Sure User Is Logged In Before Displaying Logout Button. -->
<style>
body{padding-top:70px;}
</style>

  <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-primary py-3 shadow" style="border-radius:0px;" >
    <div class="container-fluid">
      <a class="navbar-brand" href="/">FYP-21-S2-24</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAppt" aria-controls="navbarAppt" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarAppt">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link <?php if ($pageName == 'homepage') {echo 'active';} ?>" aria-current="page" href="/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo LOGIN_WEB; ?>">Create an Appointment</a>
          </li>
          <li class="nav-item">
          <a class="nav-link" href="<?php echo LOGIN_WEB; ?>">View Appointment</a>
          </li>
        </ul>
        <div class="row mx-3">
        <div class="col-8"> 
        <a href="<?php echo REGISTER_WEB; ?>" class="text-white">Register</a> </div>
        <div class="col-4"> 
      <a href="<?php echo LOGIN_WEB; ?>" class="text-white">Login</a>
      </div>
      </div>
      </div>
    </div>
  </nav>

  <!-- JavaScript code -->
 