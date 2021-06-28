<!DOCTYPE html>
<html lang="en" dir="ltr">
    <?php
    session_start();
    require_once '../resources/config.php';
    require_once USER_MOD . '/Account_User.php';
    require_once USER_MOD . '/Patient.php';
    require_once USER_MOD . '/Medical_Personnel.php';
    require_once APPT_MOD . '/Appointment_Record.php';
    require_once ENUMS_PATH . '/User_Type.php';
    ?>

    <head>
        <meta charset="utf-8">
        <title>Edit Profile</title>
        <!-- font awesome cdn -->
        <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
        <!-- embedding the Monsterrat and Ubuntu font family from google fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,900|Ubuntu&display=swap" rel="stylesheet">
        <!-- bootstrap cdn -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    </head>

    <?php
    if (isset($_SESSION["user"])):
        $user = unserialize($_SESSION["user"]);
        include TEMPLATES_PATH . '/navbar-loggedin.php';
        ?>

        <body class="bg-light">
            <section class="editProfileCard">
                <h3 class="mt-5 text-center font-weight-bold display-6"><b>Edit Profile</b></h3>
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-10 mt-3 pt-5">
                            <div class="row z-depth-3" >
                                <div class="col-sm-4 bg-primary shadow" style="border-top-left-radius: 20px; border-bottom-left-radius: 20px; border" >
                                    <div class="card-block text-center text-white" >
                                        <i class="fas fa-user-tie fa-7x" style="margin-top: 100px;"></i>
                                        <h2 class="font-weight-bold mt-4">UserName</h2>
                                        <p>Patient</p>
                                        <i class="far fa-edit fa-2x mb-4"></i>
                                    </div>
                                </div>
                                <div class="col-sm-8 bg-white rounded-end shadow">
                                    <h3 class="mt-3 text-center">Information</h3>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="font-weight-bold">First Name:</p>
                                            <input class="form-control" type="text" placeholder="<?php echo $user->get_firstname() ?>" value="">
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="font-weight-bold">Last Name:</p>
                                            <input class="form-control" type="text" placeholder="<?php echo $user->get_lastname() ?>">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="font-weight-bold">Email:</p>
                                            <input class="form-control" type="text" placeholder="<?php echo $user->get_email() ?>">
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="font-weight-bold">Phone:</p>
                                            <input class="form-control" type="text" placeholder="<?php echo substr($user->get_contactnumber(), 0, -4) ?>****">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="font-weight-bold">NRIC:</p>
                                            <input class="form-control" type="text" placeholder="******">
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="font-weight-bold">Password:</p>
                                            <input class="form-control" type="password" placeholder="********">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 mt-3 mr-auto">

                                        </div>
                                        <div class="col-sm-6 mt-3 mr-auto">
                                            <button class="btn btn-lg btn-primary mb-3 m3-2" type="button" name="button"
                                                    style="float:right">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <!-- JavaScript Bundle with Popper -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
            </script>
        </body>
<?php
else:
    header("Location:login.php");
endif;
?>

</html>