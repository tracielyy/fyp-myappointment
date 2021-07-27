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
    <link rel="stylesheet" href="./../css/medicaldashboard.css">
    <!-- font awesome cdn -->
    <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
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
        <div class="tab">
            <button class="tablinks top active" onclick="openTab(event, 'Account')" id="defaultOpen"><i
                    class="fas fa-user-cog tab-icon"></i>Account </button>
            <button class="tablinks" onclick="openTab(event, 'Profile')"><i
                    class="fas fa-user-circle tab-icon"></i>Profile </button>
            <button class="tablinks" onclick="openTab(event, 'Help')"><i
                    class="fas fa-question-circle tab-icon"></i>Help </button>
        </div>

        <div id="Account" class="tabcontent shadow rounded">
            <div class="container chart-container mt-3">
                <h3 class="text-center mb-4">Account</h3>

                <div class="mb-2">Current Email Address: example@example.com</div>

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
                        <button type="button" class="btn btn-primary" style="margin-bottom:5px;" type="submit">Submit
                            OTP
                        </button>
                    </form>
                    <span class="text-muted">Didn't receive OTP? </span><a id="resendotp" href=#> Resend OTP </a>
                </div>

                <div class="mt-3 mb-1"><strong>Change Password:</strong></div>
                <form class="form-group form-inline" action="/action_page.php">
                    <input type="password" class="form-control w-75" id="InputOldPassword1" placeholder="Old Password">
                    <input type="password" class="form-control w-75 mt-2" style="display:inline;" id="InputNewPassword1"
                        placeholder="New Password">
                    <button type="button" class="btn btn-primary" style="margin-bottom:5px;" type="submit">Change
                        Password</button>
                </form>
            </div>
        </div>

        <div id="Profile" class="tabcontent shadow rounded">
            <div class="container mt-3">
                <h3 class="text-center">Profile</h3>
                <div>
                    <div class="row my-3">
                        <div class="col">
                            First Name:
                            <input class="form-control" type="text" id="firstname" value="Yan" name="firstname"
                                readonly>
                        </div>
                        <div class="col">
                            Last Name:
                            <input class="form-control" type="text" id="lastname" value="Ying Ling" name="lastname"
                                readonly>
                        </div>
                    </div>
                    <div class="row my-3">
                        <div class="col">
                            Date of Birth:
                            <input class="form-control" type="text" id="dob" value="01-01-2000" name="dob" readonly>
                        </div>
                        <div class="col">
                            Gender:
                            <input class="form-control" type="text" id="gender" value="Female" name="gender" readonly>
                        </div>
                    </div>

                    <div class="row my-3">
                        <div class="col">
                            Address:
                            <input class="form-control" type="text" id="address" value="Singapore River Valley"
                                name="address" readonly>
                        </div>
                        <div class="col">
                            Contact Number:
                            <input class="form-control" type="text" id="number" value="8888822" name="number" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="Help" class="tabcontent shadow rounded">
            <div class="container mt-3">
                <h3 class="text-center">Help</h3>
                <div>
                </div>
            </div>
        </div>

       

        <script type="text/javascript">
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

        // Get the element with id="defaultOpen" and click on it 
        $("#defaultOpen").click();

        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
          
       
        </script>
</body>

<?php
                endif; # -- END USER TYPE CHECK
            endif; # -- END SESSION CHECK
            ?>
</html>