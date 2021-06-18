<html lang="en">
<?php
session_start();
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once ENTITIES_PATH . '/Appointment_Record.php';
require_once ENUMS_PATH . '/User_Type.php';
require_once FUNCTIONS_PATH . '/PatientFunctions.php';
require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';
?>
<?php $pageName = "homepage"; ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
    <!-- BOOTSTRAP CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <style>
    <?php include './css/homepage.css';
    ?>
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    <title>MyAppointment HomePage</title>
</head>
<body>
<?php
include COMPONENTS_PATH . '/bootstrap.php';

if (isset($_SESSION["user"])):
  $user = unserialize($_SESSION["user"]);
  include COMPONENTS_PATH . '/navbar-loggedin.php';
else:
  include COMPONENTS_PATH . '/navbar.php';
endif;

?>
    <section id="Find-Clinic">
        <h2 class="find-clinic-title">Below is a drop down where you can choose the clinics</h2>
        <div class="dropdown clinicChoice">
          <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">
            Choose Clinic
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Clinic 1</a>
            <a class="dropdown-item" href="#">Clinic 2</a>
            <a class="dropdown-item" href="#">Clinic 3</a>
          </div>
        </div>
        <button type="button" class="btn btn-success btn-lg"><i class="fas fa-arrow-right"></i></button>
      </section>
      <section id="health-snippets">
          <div class="row">
            <div class="col-lg-4">
              <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                  <div class="col-md-4">
                    <img src="https://png.pngtree.com/template/20190316/ourmid/pngtree-medical-health-logo-image_79595.jpg" class="health-img" alt="...">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title">Health Message</h5>
                      <p class="card-text">Health promotional message.</p>
                      <p class="card-text"><small class="text-muted">health updates</small></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                  <div class="col-md-4">
                    <img src="https://png.pngtree.com/template/20190316/ourmid/pngtree-medical-health-logo-image_79595.jpg" class="health-img" alt="...">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title">Health Message</h5>
                      <p class="card-text">Health promotional message.</p>
                      <p class="card-text"><small class="text-muted">health updates</small></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                  <div class="col-md-4">
                    <img src="https://png.pngtree.com/template/20190316/ourmid/pngtree-medical-health-logo-image_79595.jpg" class="health-img" alt="...">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title">Health Message</h5>
                      <p class="card-text">Health promotional message.</p>
                      <p class="card-text"><small class="text-muted">health updates</small></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-4">
              <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                  <div class="col-md-4">
                    <img src="https://png.pngtree.com/template/20190316/ourmid/pngtree-medical-health-logo-image_79595.jpg" class="health-img" alt="...">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title">Health Message</h5>
                      <p class="card-text">Health promotional message.</p>
                      <p class="card-text"><small class="text-muted">health updates</small></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                  <div class="col-md-4">
                    <img src="https://png.pngtree.com/template/20190316/ourmid/pngtree-medical-health-logo-image_79595.jpg" class="health-img" alt="...">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title">Health Message</h5>
                      <p class="card-text">Health promotional message.</p>
                      <p class="card-text"><small class="text-muted">health updates</small></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                  <div class="col-md-4">
                    <img src="https://png.pngtree.com/template/20190316/ourmid/pngtree-medical-health-logo-image_79595.jpg" class="health-img" alt="...">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title">Health Message</h5>
                      <p class="card-text">Health promotional message.</p>
                      <p class="card-text"><small class="text-muted">health updates</small></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </section>
      <section id="footer">
        <div class="container-md">
          <div class="row row-cols-lg-4">
            <div class="col mb-4">
              <div class="card">
                <i class="fas fa-user fa-4x"></i>
                <div class="card-body">
                  <h5 class="card-title">user information</h5>
                  <p class="card-text">some information for the end-users.</p>
                </div>
              </div>
            </div>
            <div class="col mb-4">
              <div class="card">
                <i class="fas fa-phone-alt fa-4x"></i>
                <div class="card-body">
                  <h5 class="card-title">Contact information</h5>
                  <p class="card-text">some contact details for the end users.</p>
                </div>
              </div>
            </div>
            <div class="col mb-4">
              <div class="card">
                <i class="fas fa-clock fa-4x"></i>
                <div class="card-body">
                  <h5 class="card-title">Working Hours</h5>
                  <p class="card-text">some information about the working hours</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
</body>
</html>