<?php
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once EMAIL_MOD . '/Email.php';
require_once UTIL_MOD . '/StringUtils.php';
require_once UTIL_MOD . '/Regex.php';
require_once AUTH_MOD . '/Authentication.php';
require_once USER_MOD . '/Account_User.php';


include TEMPLATES_PATH . '/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./../css/profile.css">
    <!-- font awesome cdn -->
    <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <style>
    .profile-text {
        font-size: 20px;
        margin-bottom: 10px;
    }

    .extramargin
    {
        margin-top: 55px !important;
    }
    </style>
</head>

<body>
    <?php
        if (isset($_SESSION["user"])):

            # "Unboxin" User Information
            $user = unserialize($_SESSION["user"]);
            $user_email = $user->get_email();
            $user_type = $user->get_usertype();
            $email['credentials']['email'] = $user_email;

            if (User_Type::check_user_type(User_Type::PATIENT, $user_type)):
                include_once TEMPLATES_PATH . '/navbar-loggedin.php';
                ?>
    <div class="row bg-light py-4">
        <div class="row bg-light">
            <div class="col-xs-3 col-md-2 mx-md-1 mx-lg-0" style="padding-left: 140px;">
                <img src="https://via.placeholder.com/100" class="rounded shadow float-start" alt="...">
            </div> <!-- col -->

            <div class="col-xs-8 col-md-6">
                <h1 class="display-6"> <?php echo $user->get_fullname(); ?> </h1>
                <h1 class="lead"> Gender: <?php echo $user->get_gender(); ?> </h1>
                <h1 class="lead"> Date of Birth: <?php echo $user->get_dob(); ?> </h1>
            </div> <!-- col -->

        </div>
    </div>

    <div class="container-fluid mt-3">
        <ul class="nav nav-tabs flex-column tabgroup" id="tabsID">
            <li><a class="tablinks top active" href="#account" data-toggle='tab' id="default"><i
                        class="fas fa-user-cog tab-icon"></i>Account </a></li>
            <li> <a class="tablinks" href="#profile" data-toggle='tab'><i
                        class="fas fa-user-circle tab-icon"></i>Profile</a></li>
            <li> <a class="tablinks" href="#help" data-toggle='tab'><i class="fas fa-question-circle tab-icon"></i>Help
                </a></li>
        </ul>

        <div class="tab-content">
            <div id="account" class="tab-pane shadow rounded active">
                <div class="container chart-container mt-3">
                    <h3 class="text-center mb-4">Account</h3>

                    <p class="profile-text mb-2">Current Email Address: <?php echo $user->get_email(); ?></p>

                    <div><strong>Change Email Address:</strong></div>
                    <form class="form-group form-inline mt-1" action="/action_page.php">
                        <input onkeyup="typedEmail()" class="form-control w-75" type="email" id="newEmail"
                            style="display:inline;" placeholder="Enter new email" name="email">
                        <button id="chgEmailBttn" type="button" class="btn btn-primary" style="margin-bottom:5px;"
                            type="submit" disabled="disable">Change
                            Email Address
                        </button>
                    </form>
                    <div id="onetimepass" class="onetimepass" style="display: hidden;">
                        <form class="form-group form-inline mt-1" style="display: hidden;" action="/action_page.php">
                            <input class="form-control w-75" type="otp" id="otp" style="display:inline;"
                                placeholder="Enter OTP" name="otp">
                            <button type="button" class="btn btn-primary" style="margin-bottom:5px;"
                                type="submit">Submit
                                OTP
                            </button>
                        </form>
                        <span class="text-muted">Didn't receive OTP? </span><a id="resendotp" href=#> Resend OTP </a>
                    </div>

                    <div class="mt-3 mb-1"><strong>Change Password:</strong></div>
                    <form class="form-group form-inline" action="/action_page.php">
                        <input type="password" class="form-control w-75" id="oldPasswordID"
                            placeholder="Current Password">
                        <input type="password" class="form-control w-75 mt-2" name="newpassword" style="display:inline;"
                            id="newpasswordID" placeholder="New Password">
                        <input type="password" class="form-control w-75 mt-2" name="confirmpassword"
                            style="display:inline;" id="confirmpasswordID" placeholder="Confirm New Password">
                        <button type="button" class="btn btn-primary" style="margin-bottom:5px;" type="submit">Change
                            Password</button>
                    </form>
                </div>
            </div>

            <div id="profile" class="tab-pane shadow rounded">
                <div class="container mt-3">
                    <h3 class="text-center mt-5">Profile</h3>
                    <div>
                        <div class="row my-3">
                            <div class="col-2">
                                <div class="profile-text my-5"><strong>Name: </strong> </div>
                                <div class="profile-text my-5"><strong>Date of Birth: </strong> </div>
                                <div class="profile-text my-5"><strong>Gender: </strong> </div>
                                <div class="profile-text my-5"><strong>Address: </strong> </div>
                                <div id="contactNumText" class="profile-text my-5"><strong>Contact Number: </strong> </div>
                            </div>

                            <div class="col-10">
                                <div class="profile-text my-5">
                                    <?php echo $user->get_firstname(). " ". $user->get_lastname()  ; ?> </div>
                                <div class="profile-text my-5"><?php echo $user->get_dob(); ?> </div>
                                <div class="profile-text my-5"><?php echo $user->get_gender(); ?> </div>
                                <div class="profile-text my-5" id="Address"><?php echo $user->get_address(); ?>
                                    <button type="button" class="btn btn-secondary ms-3" style="float:right"
                                        id="changADbttn">Change Address</button>
                                </div>

                                <div id="formchangeAD" class="mt-4 mb-5" style="display:none">
                                    <form class="row">
                                        <div class="col-9">
                                            <div class="input-group input-group">
                                                <input class="form-control" type="text"
                                                    placeholder="<?php echo $user->get_address(); ?>"></input>
                                                <button type="button" class="btn btn-outline-danger"
                                                    type="button" id="cancelChangeAD">x</button>
                                            </div>
                                        </div>
                                        <div class="col-auto px-4" style="float:right">
                                            <button type="button" class="btn btn-secondary" type="submit"
                                                style="float:right">Update Address</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="profile-text my-5" id="ContactNum"><?php echo $user->get_contactnumber(); ?>
                                    <button type="button" class="btn btn-secondary ms-3" style="float:right"
                                        id="changeCNbttn">Change
                                        Contact Number</button>
                                </div>


                                <div id="formchangeCN"  class="mt-4" style="display:none">
                                    <form class="row">
                                        <div class="col-9 p-0">
                                            <div class="input-group input-group">
                                                <span class="input-group-text" id="basic-addon1">+65</span><input
                                                    class="form-control" type="text"
                                                    placeholder="<?php echo $user->get_contactnumber(); ?>"></input>

                                                <button type="button" class="btn btn-outline-danger"
                                                    type="button" id="cancelChangeCN">x</button>
                                            </div>
                                        </div>
                                        <div class="col-auto px-4" style="float:right">
                                            <button type="button" class="btn btn-secondary" type="submit"
                                                style="float:right">Update Contact Number</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-muted">Due to security reasons, we lock on changing your <strong>name, date of
                                birth, gender and NRIC.</strong> If you would like to change any of them please email us
                            support at <u>support.fyp.21.s2.24@gmail.com</u></div>
                    </div>
                </div>
            </div>


            <div id="help" class="tab-pane shadow rounded">
                <div class="container mt-3">
                    <h3 class="text-center">Help</h3>
                    <div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script type="text/javascript">
   
   $("#changADbttn").click(function() {
        $('#Address').toggle();
        $('#formchangeAD').toggle();
        $('#contactNumText').addClass('extramargin');
    });

    $("#cancelChangeAD").click(function() {
        $('#Address').toggle();
        $('#formchangeAD').toggle();
        $('#contactNumText').removeClass('extramargin');
    });
   
    $("#changeCNbttn").click(function() {
        $('#ContactNum').toggle();
        $('#formchangeCN').toggle();
       
    });

    $("#cancelChangeCN").click(function() {
        $('#ContactNum').toggle();
        $('#formchangeCN').toggle();
    });


    //hides one time pass
    $('#onetimepass').children().hide();

    function typedEmail() {
        if (document.getElementById("newEmail").value === "") {
            document.getElementById('chgEmailBttn').disabled = true;
        } else {
            document.getElementById('chgEmailBttn').disabled = false;
        }
    }

    $("#chgEmailBttn").on('click', function clickedChangeEmail() {
        $('#onetimepass').children().show();
    });

    /*===========================
            Dynamic Tabs
    =============================*/

    $(document).ready(function() {
        if (location.hash) {
            $("a[href='" + location.hash + "']").tab("show");
        }
        $(document.body).on("click", "a[data-toggle='tab']", function(event) {
            event.preventDefault();
            location.hash = this.getAttribute("href");
        });

    });
    $(window).on("popstate", function() {
        var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
        $("a[href='" + anchor + "']").tab("show");
    });
    </script>
</body>

<?php
                endif; # -- END USER TYPE CHECK
            endif; # -- END SESSION CHECK
            ?>

</html>