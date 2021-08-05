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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
        <style>
            .profile-text {
                font-size: 20px;
                margin-bottom: 10px;
            }

            div {
                margin-top: 0px;
            }

            .help-block {
                display: none;
                width: 100%;
                margin-top: .25rem;
                font-size: .875em;
                color: #dc3545;
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

            if (User_Type::check_user_type(User_Type::PATIENT, $user_type) || User_Type::check_user_type(User_Type::MEDICAL_PERSONNEL, $user_type)):
                include_once TEMPLATES_PATH . '/navbar-loggedin.php';

                if (isset($_POST['submitpassword'])) :

                    $oldpass = $_POST['oldpassword'];
                    $newpass = $_POST['password'];
                    $confirmpass = $_POST['confirmpassword'];
                    $secure = new Security();

                    if (Authentication::authenticate_user($user->get_email(), $user->get_usertype(), $oldpass)) :
                        if ($confirmpass == $newpass) :
                            $new_hashed_pw = $secure->hash($newpass);
                            Account_User::update_password($user->get_email(), $new_hashed_pw);
                        //$user->set_password($new_hashed_pw);
                        //  -- NEED ALERT TO SHOW PASSWORD HAS CHANGED
                        //echo "new password set";
                        else :
                        //confirm pass and new pass are not the same
                        endif;
                    else :
                    //echo "Current Password is wrong";
                    //  -- NEED ALERT TO SHOW PASSWORD ENTERED WRONG
                    endif;

                elseif (isset($_POST['updateaddress'])) :

                    $newaddress = $_POST['address'];
                    if (Account_User::update_address($user->get_email(), $newaddress)) :
                        $user->set_address($newaddress);
                    else :
                    //echo "failed change";
                    endif;

                elseif (isset($_POST['updatecontactnum'])) :

                    $contactnum = $_POST['contactnum'];
                    if (!empty($contactnum)):
                        if (Account_User::update_contact_number($user->get_email(), $contactnum)):
                            $user->set_contactnumber($contactnum);
                        else:
                        //echo "failed change";
                        endif;
                    endif; # -- Check Contact Number Not Empty
                elseif (isset($_POST['changeEmail'])):
                    $new_email = $_POST['email'];
                    if (!empty($new_email)):
                        # Change Email 
                        if (Account_User::change_email($user_email, $new_email)):
                            $user->set_email($new_email);
                            $_SESSION['user'] = serialize($user);
                        endif;
                    endif;
                endif;
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
                    </ul>

                    <div class="tab-content">
                        <!-- ACCOUNT TAB -->
                        <div id="account" class="tab-pane shadow rounded active">
                            <div class="container chart-container mt-3">
                                <h3 class="text-center my-5">Account</h3>

                                <p class="profile-text mb-5"><strong>Email Address: </strong> <?php echo $user->get_email(); ?></p>

                                <!-- EMAIL FORM -->
                                <div class="profile-text mb-0"><strong>Change Email Address:</strong></div>
                                <div class="text-muted  mt-0">New email needs to be verified first, before the new email gets
                                    updated.</div>
                                <form id="changeEmail" class="form-group mt-1" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >
                                    <div class="row" id="email_container">
                                        <div id="inputgroupemail" class="input-group w-75">

                                            <input onkeyup="typedEmail()" class="form-control" type="email" id="email"
                                                   style="display:inline;" placeholder="Enter new email" name="email">
                                            <button type="button" class="btn btn-outline-secondary" type="button" id="verifybutton" disabled="true">
                                                <span id="email-span">Send OTP</span>
                                            </button>

                                        </div>
                                        <div id="email-error" class="help-block mt-1">Email exist! Please use another email.</div>
                                    </div>

                                    <!-- ONE TIME PASS  -->
                                    <div class="row">
                                        <div id="onetimepass" class="onetimepass" style="display: hidden;">
                                            <div class="input-group w-25">
                                                <input class="form-control" type="otp" id="otp" style="display:inline;"
                                                       placeholder="Enter OTP" name="otp">
                                                <button class="btn btn-primary" type="button" id="submitOTP">Submit
                                                    OTP
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-auto">
                                            <button id="chgEmailBttn" type="submit" class="btn btn-primary mt-2" name= "changeEmail"
                                                    style="margin-bottom:2px;" disabled="disable">Change
                                                Email Address
                                            </button>
                                        </div>
                                    </div>
                                </form><!-- END CHANGE EMAIL FORM

                                -->                                <!-- ONE TIME PASS FORM 
                                                                <div id="onetimepass" class="onetimepass" style="display: hidden;">
                                                                    <form class="form-group form-inline mt-1" style="display: hidden;" method="post">
                                                                        <div class="input-group w-75">
                                                                            <input class="form-control" type="otp" id="otp" style="display:inline;"
                                                                                   placeholder="Enter OTP" name="otp">
                                                                            <button class="btn btn-primary" type="submit">Submit
                                                                                OTP
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
              -->
                                
                                <!-- CHANGE PASSWORD FORM -->
                                <div id="changepassword" class="mt-5 mb-2 profile-text"><strong>Change Password:</strong></div>
                                <form id="changepass" class="form-group" action="" method="post">

                                    <input type="password" class="form-control w-75" id="oldPasswordID" name="oldpassword"
                                           placeholder="Current Password">
                                    <input type="password" class="form-control w-75 mt-3" name="password" id="newpasswordID"
                                           placeholder="New Password">
                                    <input type="password" class="form-control w-75 mt-3" name="confirmpassword"
                                           id="confirmpasswordID" placeholder="Confirm New Password">

                                    <button name="submitpassword" class="btn btn-primary" style="margin-top:10px"
                                            type="submit">Change
                                        Password</button>
                                </form>
                            </div>
                        </div>

                        <!-- PROFILE TAB -->
                        <div id="profile" class="tab-pane shadow rounded">
                            <div class="container mt-3">
                                <h3 class="text-center mt-5">Profile</h3>
                                <div>
                                    <div class="row my-3">
                                        <!-- Name Row -->
                                        <div class="row my-3">
                                            <div class="col-2">
                                                <div class="profile-text"><strong>Name: </strong> </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="profile-text">
                                                    <?php echo $user->get_firstname() . " " . $user->get_lastname(); ?> </div>
                                            </div>
                                        </div>

                                        <!-- Date of Birth -->
                                        <div class="row my-3">
                                            <div class="col-2">
                                                <div class="profile-text"><strong>Date of Birth: </strong> </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="profile-text"><?php echo $user->get_dob(); ?> </div>
                                            </div>
                                        </div>

                                        <!-- Gender -->
                                        <div class="row my-3">
                                            <div class="col-2">
                                                <div class="profile-text"><strong>Gender: </strong> </div>
                                            </div>
                                            <div class="col-auto">
                                                <div class="profile-text"><?php echo $user->get_gender(); ?> </div>
                                            </div>
                                        </div>

                                        <!-- Address -->
                                        <div class="row my-3">
                                            <div class="col-2">
                                                <div class="profile-text"><strong>Address: </strong> </div>
                                            </div>
                                            <div class="col-10">
                                                <div class="profile-text" id="Address"><?php echo $user->get_address(); ?>
                                                    <button type="button" class="btn btn-secondary ms-3" style="float:right"
                                                            id="changADbttn">Change Address</button>
                                                </div>

                                                <!-- CHANGE ADDRESS -->
                                                <div id="formchangeAD" class="" style="display:none">
                                                    <form id="addressform" class="row" method="post" action="">
                                                        <div class="col-9">
                                                            <div class="input-group" id="inputgroupaddress">
                                                                <input class="form-control" type="text"
                                                                       placeholder="<?php echo $user->get_address(); ?>"
                                                                       name="address"></input>
                                                                <button type="button" class="btn btn-outline-danger" type="button"
                                                                        id="cancelChangeAD">x</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto px-4" style="float:right">
                                                            <button class="btn btn-secondary" type="submit" name="updateaddress"
                                                                    style="float:right">Update Address</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contact Number -->
                                        <div class="row my-3">
                                            <div class="col-2">
                                                <div id="contactNumText" class="profile-text"><strong>Contact Number: </strong>
                                                </div>
                                            </div>

                                            <div class="col-10">
                                                <div class="profile-text" id="ContactNum">
                                                    <?php echo $user->get_contactnumber(); ?>
                                                    <button type="button" class="btn btn-secondary ms-3" style="float:right"
                                                            id="changeCNbttn">Change
                                                        Contact Number</button>
                                                </div>

                                                <!-- CHANGE CONTACT NUMBER -->
                                                <div id="formchangeCN" class="" style="display:none">
                                                    <form id="contactform" class="row" method="post" action="">
                                                        <div class="col-9 p-0">
                                                            <div class="input-group" id="inputgroupcontact">
                                                                <span class="input-group-text">+65</span><input class="form-control"
                                                                                                                type="text" name="contactnum"
                                                                                                                placeholder="<?php echo $user->get_contactnumber(); ?>"></input>

                                                                <button type="button" class="btn btn-outline-danger" type="button"
                                                                        id="cancelChangeCN">x</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto px-4" style="float:right">
                                                            <button class="btn btn-secondary" type="submit" style="float:right"
                                                                    name="updatecontactnum">Update Contact Number</button>
                                                        </div>
                                                    </form>
                                                </div>
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

                    </div>
                </div>



                <script type="text/javascript">

                    // DISABLE THE `VERIFY` BUTTON WHEN NEEDED
                    function typedEmail()
                    {
                        $("#chgEmailBttn").prop('disabled', true);

                        $("#verifybutton").show();
                        document.cookie = 'email_verified=false';
                        $("#email").removeClass("is-valid");
                        $("#resend-otp").remove();

                        console.log(document.cookie);
                        $('#onetimepass').hide();
                        email_verified_validation = false;

                        if (document.getElementById("email").value === "") {
                            document.getElementById('verifybutton').disabled = true;
                        } else {
                            document.getElementById('verifybutton').disabled = false;
                        }
                    }

                    function strip_string(str) {
                        return str.replace(/(\r\n|\n|\r)/gm, "");
                    }

                    /*===========================
                     Dynamic Tabs
                     =============================*/

                    $(window).on("popstate", function () {
                        var scrollHeight = $(document).scrollTop();
                        var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
                        $("a[href='" + anchor + "']").tab("show");
                        console.log("a[href='" + anchor + "']");
                        setTimeout(function () {
                            $(window).scrollTop(scrollHeight);
                        }, 5);
                    });

                    $(document).ready(function () {

                        if (location.hash) {
                            $("a[href='" + location.hash + "']").tab("show");
                        }
                        $(document.body).on("click", "a[data-toggle='tab']", function (ev) {
                            var scrollHeight = $(document).scrollTop();
                            location.hash = this.getAttribute("href");
                            setTimeout(function () {
                                $(window).scrollTop(scrollHeight);
                            }, 5);
                            console.log("going thru false");
                        });

                    });

                    $("#changADbttn").click(function () {
                        $('#Address').toggle();
                        $('#formchangeAD').toggle();
                    });

                    $("#cancelChangeAD").click(function () {
                        $('#Address').toggle();
                        $('#formchangeAD').toggle();
                    });

                    $("#changeCNbttn").click(function () {
                        $('#ContactNum').toggle();
                        $('#formchangeCN').toggle();
                    });

                    $("#cancelChangeCN").click(function () {
                        $('#ContactNum').toggle();
                        $('#formchangeCN').toggle();
                    });


                    //hides one time pass
                    $('#onetimepass').children().hide();

                    // function typedEmail() {
                    //     if (document.getElementById("newEmail").value === "") {
                    //         document.getElementById('chgEmailBttn').disabled = true;
                    //     } else {
                    //         document.getElementById('chgEmailBttn').disabled = false;
                    //     }
                    // }

                    /*------------------------------------------------
                     ONE TIME PASS
                     -------------------------------------------------*/

                    var resend_otp_req = false;
                    // RESEND OTP
                    function clickedResendOTP() {
                        console.log("resend otp called");
                        if (resend_otp_req) {
                            verifyEmailReq.abort();
                            console.log("resend otp aborted");
                        }

                        resend_otp_req = $.ajax({
                            type: "POST",
                            url: "func/checkfieldexist.php",
                            data: {
                                ajax_email_check_exist: true,
                                email: $('#email').val()
                            },
                            success: function (email_status) {

                                console.log("Email Sent");
                                console.log(email_status);
                                console.log(JSON.stringify(strip_string(email_status)));

                                var email_exist = strip_string(email_status);
                                console.log(email_exist);

                                // check if email exist in database
                                if (email_exist !== 'true') {

                                    $("#verifybutton").hide();
                                    $('#onetimepass').show();
                                    $('#onetimepass').children().show();

                                    console.log("Email is new");
                                }
                            },
                            error: function () {
                                console.log("Email NOT SENT");
                            }
                        });
                    }
                    var verifyEmailReq = false;
                    $("#verifybutton").on('click', function clickedChangeEmail() {

                        // Resetting The OTP Behaviour
                        $('#onetimepass').hide();
                        $('#onetimepass').children().hide();
                        $('#otp').val('');
                        document.cookie = 'email_verified=false';
                        $('#email').removeClass("is-valid");

                        // add spinner (loading)
                        var spinner = '<span id="spinner-email" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                        $("#email-span").html("Verifying");
                        $("#verifybutton").prepend(spinner);

                        if (verifyEmailReq) {
                            verifyEmailReq.abort();
                            console.log("verifyemailreq aborted");
                        }

                        verifyEmailReq = $.ajax({
                            type: "POST",
                            url: "func/checkfieldexist.php",
                            data: {
                                ajax_email_check_exist: true,
                                email: $('#email').val()
                            },
                            success: function (email_status) {

                                // remove spinner once load finished
                                $("#spinner-email").remove();
                                $("#email-span").html("Send OTP");

                                console.log("Email Sent");
                                console.log(email_status);
                                console.log(JSON.stringify(strip_string(email_status)));

                                var email_exist = strip_string(email_status);
                                console.log(email_exist);

                                // check if email exist in database
                                if (email_exist === 'true') {
                                    console.log('Email exist');
                                    $('#email-error').html("Email Exist");
                                    $('#onetimepass').hide();

                                } else {
                                    $("#verifybutton").hide();
                                    $('#onetimepass').show();
                                    $('#onetimepass').children().show();

                                    // create resend otp element
                                    if (!$('#resend-otp').length) {
                                        console.log("creating resend otp element");
                                        var resend_otp =
                                                "<button type='button' id='resend-otp' onclick='clickedResendOTP()' class='btn btn-link link-danger shadow-none'>Resend OTP</button>";
                                        $('#inputgroupemail').append(resend_otp);
                                    }

                                    console.log("Email is new");
                                }
                            },
                            error: function () {
                                // remove spinner once load finished
                                $("#spinner-email").remove();
                                console.log("Email NOT SENT");
                            }
                        });

                    });






                    function invalid_otp_msg(msg) {
                        // WHEN THE OTP IS INVALID
                        $("#otp").addClass("is-invalid");
                        if (!$("#otp-feedback").length) {
                            var otp_feedback =
                                    `<div id='otp-feedback' class='invalid-feedback'>${msg}</div>`;
                            $('#onetimepass').append(otp_feedback);
                        }
                        $('#onetimepass').children().show();
                    }

                    function refresh_otp_feedback() {
                        $('#otp-feedback').remove();
                        $("#otp").removeClass("is-invalid");

                    }

                    // WHEN USER SUBMITS THE OTP
                    $("#submitOTP").on('click', function clickedSubmitOTP() {

                        var reg = /^\d{6}$/;

                        refresh_otp_feedback();

                        // Check If OTP Is Empty
                        var otp_val = $('#otp').val().trim();
                        $('#otp').val(otp_val);
                        if (otp_val !== "") {

                            // Check If Has 6 digits
                            if (reg.test(otp_val)) {
                                // -- EMAIL VERIFY TRIGGER
                                $.ajax({
                                    type: "POST",
                                    url: "func/otpvalidate.php",
                                    data: {
                                        ajax_otp: true,
                                        email: $('#email').val(),
                                        otp: otp_val
                                    },
                                    success: function (valid_otp) {
                                        email_verified_validation = true;
                                        console.log("Email Validate");
                                        console.log(valid_otp);
                                        console.log(strip_string(valid_otp));
                                        var valid_status = strip_string(valid_otp);
                                        if (valid_status === 'true') {

                                            // remove the resent-otp button link
                                            $('#resend-otp').remove();

                                            // enable the change button
                                            $("#chgEmailBttn").prop('disabled', false);

                                            // hide the otp section
                                            $("#verifybutton").hide();
                                            $('#onetimepass').hide();

                                            // reset by removing email feedback
                                            $('#email-feedback').remove();
                                            $('#email-error').remove();

                                            // add the new email feedback
                                            $("#email").addClass("is-valid");

                                            // set the email verification cookie to true
                                            document.cookie = 'email_verified=true';
                                            console.log('tick');
                                        } else {
                                            console.log('untick');
                                            invalid_otp_msg("Incorrect OTP Entered");
                                        }
                                    },
                                    error: function () {
                                        console.log("Email Validation Error");
                                    }
                                });
                            } else {
                                invalid_otp_msg("6 digits OTP required");
                            }
                        } else {
                            invalid_otp_msg("OTP Field Cannot Be Empty");
                        }// Check For Empty

                    }
                    );


                    /*------------------------------------------------
                     CLIENT SIDE VALIDATION FOR EMAIL
                     -------------------------------------------------*/

                    $(document).ready(function () {
                        $("#changeEmail").validate({
                            rules: {
                                email: {
                                    required: true,
                                    emailRegex: true
                                }
                            },
                            messages: {
                                email: {
                                    required: "Required to enter your address",
                                    emailRegex: "Email format is incorrect."
                                }
                            },
                            errorElement: "em",
                            errorPlacement: function (error, element) {
                                // This is the default behavior 
                                document.getElementById('verifybutton').disabled = true;
                                error.insertAfter("#inputgroupemail");
                                error.addClass("help-block invalid-feedback");
                            },
                            success: function (label, element) {

        //                                $(element).addClass("is-valid");
                                document.getElementById('verifybutton').disabled = false;

                            },
                            highlight: function (element, errorClass, validClass) {
                                $(element).addClass("is-invalid").removeClass("is-valid");
                            },
                            unhighlight: function (element, errorClass, validClass) {
        //                                $(element).addClass("is-valid").removeClass("is-invalid");

                            }
                        });
                    });


                    $.validator.addMethod("emailRegex", function (value, element) {
                        return this.optional(element) ||
                                /^[a-zA-Z0-9]+(.[_a-z0-9-]+)(?!.*[~@\%\/\\\&\?\,\'\;\:\!\-]{2}).*@[a-z0-9-]+(.[a-z0-9-]+)(.[a-z]{2,3})/
                                .test(value);
                    }, "Email format is incorrect.");


                    $(document).ready(function () {

                        /*------------------------------------------------
                         CLIENT SIDE REGULAR EXPRESSION FOR PASSWORD
                         -------------------------------------------------*/

                        $("#changepass").validate({
                            rules: {
                                oldpassword: {
                                    required: true
                                },
                                password: {
                                    required: true,
                                    oneDigit: true,
                                    lowerCase: true,
                                    upperCase: true,
                                    specialChar: true,
                                    minlength: 8,
                                    maxlength: 32

                                },
                                confirmpassword: {
                                    required: true,
                                    equalTo: "#newpasswordID"
                                }
                            },
                            messages: {
                                oldpassword: {
                                    required: "Required to put your current password"
                                },
                                password: {
                                    required: "Please provide a password",
                                    minlength: "Password needs to be at least 8 characters",
                                    maxlength: "Password exceeded 32 characters limit"
                                },
                                confirmpassword: {
                                    required: "Please provide a confirm password",
                                    equalTo: "Please enter the same password"
                                }
                            },
                            errorElement: "em",
                            errorPlacement: function (error, element) {
                                // This is the default behavior 
                                error.insertAfter(element);
                                error.addClass("help-block invalid-feedback");
                            },
                            success: function (label, element) {

                                $(element).addClass("is-valid");


                            },
                            highlight: function (element, errorClass, validClass) {
                                $(element).addClass("is-invalid").removeClass("is-valid");
                            },
                            unhighlight: function (element, errorClass, validClass) {
                                $(element).addClass("is-valid").removeClass("is-invalid");


                            }
                        });

                    });


                    $.validator.addMethod("oneDigit", function (value, element) {
                        return this.optional(element) ||
                                /(?=.*[0-9])/
                                .test(value);
                    }, "Password needs at least one digit.");

                    $.validator.addMethod("lowerCase", function (value, element) {
                        return this.optional(element) ||
                                /(?=.*[a-z])/
                                .test(value);
                    }, "Password needs at least one lower case character.");

                    $.validator.addMethod("upperCase", function (value, element) {
                        return this.optional(element) ||
                                /(?=.*[A-Z])/
                                .test(value);
                    }, "Password needs at least one upper case character.");

                    $.validator.addMethod("specialChar", function (value, element) {
                        return this.optional(element) ||
                                /(?=.*[\*\.\!\@\$\%\^\&\(\)\{\}\[\]\:\;\<\>\,\?\/\~\_\+\-\=\|\#])/.test(value);
                    }, "Password needs at least one special character. e.g. [!@#$%^&*]");




                    /*------------------------------------------------
                     CLIENT SIDE VALIDATION FOR ADDRESS
                     -------------------------------------------------*/

                    $(document).ready(function () {

                        $("#changADbttn").on("click", function () {
                            $("#addressform").validate({
                                rules: {
                                    address: {
                                        required: true
                                    }
                                },
                                messages: {
                                    address: "Required to enter your address"
                                },
                                errorElement: "em",
                                errorPlacement: function (error, element) {
                                    // This is the default behavior 
                                    error.insertAfter("#inputgroupaddress");
                                    error.addClass("help-block invalid-feedback");
                                },
                                success: function (label, element) {

                                    $(element).addClass("is-valid");

                                },
                                highlight: function (element, errorClass, validClass) {
                                    $(element).addClass("is-invalid").removeClass("is-valid");
                                },
                                unhighlight: function (element, errorClass, validClass) {
                                    $(element).addClass("is-valid").removeClass("is-invalid");

                                }
                            });
                        });

                    });


                    /*------------------------------------------------
                     CLIENT SIDE VALIDATION FOR PHONE NUM
                     -------------------------------------------------*/

                    $(document).ready(function () {

                        $("#changeCNbttn").on("click", function () {
                            $("#contactform").validate({
                                rules: {
                                    contactnum: {
                                        required: true,
                                        phoneRegex: true
                                    }
                                },
                                messages: {
                                    contactnum: {
                                        required: "Required to enter your Contact Number",
                                        phoneRegex: "Contact number format is incorrect"
                                    }

                                },
                                errorElement: "em",
                                errorPlacement: function (error, element) {
                                    // This is the default behavior 
                                    error.insertAfter("#inputgroupcontact");
                                    error.addClass("help-block invalid-feedback mt-2");
                                },
                                success: function (label, element) {

                                    $(element).addClass("is-valid");

                                },
                                highlight: function (element, errorClass, validClass) {
                                    $(element).addClass("is-invalid").removeClass("is-valid");
                                },
                                unhighlight: function (element, errorClass, validClass) {
                                    $(element).addClass("is-valid").removeClass("is-invalid");

                                }
                            });
                        });


                    });

                    $.validator.addMethod("phoneRegex", function (value, element) {
                        return this.optional(element) || /^[689]{1}[0-9]{7}$/.test(value);
                    }, "Contact number format is incorrect");
                </script>
            </body>

            <?php
        endif; # -- END USER TYPE CHECK
    endif; # -- END SESSION CHECK
    ?>

</html>