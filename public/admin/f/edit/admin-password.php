<?php
/*
 *  @author: tracieqwynn
 */
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';

require_once FACILITY_MOD . '/Medical_Facility.php';

/*
 *  EDIT FACILITY ADMIN  -- PASSWORD CHANGE
 */

if (!isset($_SESSION['user'])):
    header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
else:
    $user = unserialize($_SESSION["user"]);
    $user_type = $user->get_usertype();
    $user_email = $user->get_email();

// Check If User Is Super Admin
    if (!User_Type::check_user_type(User_Type::FACIILITY_ADMIN, $user_type)):
        header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
    else:

        // loop and store all the information into an array
        function store_info(array &$post, array &$info_arr, array &$validArr): void {
            foreach ($post as $key => $value) :
                if (isset($info_arr[$key])) :

                    // calls itself if it is an array
                    if (is_array($value)):
                        store_info($post[$key], $info_arr[$key], $validArr);
                    else:
                        $info_arr[$key] = htmlspecialchars($value);
                        $validArr[$key] = False; // Set All Field Validation Check As False
                    endif;
                endif;
            endforeach;
        }

        $admin = array(
            'credentials' => array(
                'password' => '',
            ),
            'confirmpassword' => '',
            'currentpassword' => ''
        );

        if ($_SERVER["REQUEST_METHOD"] == "POST"):
            $user_password = Account_User::retrieve_password_by_id($user->get_adminid());
            $validArr = array();
            if (isset($_POST['edit_admin'])):
                store_info($_POST, $admin, $validArr);
                // Validation 
                if (!empty($admin['currentpassword'])):
                    $validArr['currentpassword'] = true;
                endif;
                if (!empty($admin['credentials']['password'])):
                    $validArr['password'] = true;
                endif;
                if (!empty($admin['confirmpassword']) && $admin['confirmpassword'] == $admin['credentials']['password']):
                    $validArr['confirmpassword'] = true;
                endif;

            endif;

        endif;
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <title>Admin Edit Profile: Password</title>
                <!-- fontawesome -->
                <script src="https://kit.fontawesome.com/dcfd5ba5e7.js"  crossorigin="anonymous"></script>
                <!-- google fonts -->
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap"  rel="stylesheet" />
                <!-- bootstrap cdn link -->
                <link
                    href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
                    rel="stylesheet"
                    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
                    crossorigin="anonymous"
                    />
                <!-- bootstrap data table -->
                <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" />
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" />
                <!-- Prevent Form Resubmission -->
                <script>
                    if (window.history.replaceState) {
                        window.history.replaceState(null, null, window.location.href);
                    }
                </script>
                <!-- jQuery -->
                <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
                <style>
                    body {
                        margin: 0;
                        padding: 0;
                        background-color: #eeeded;
                        font-family: "Poppins", sans-serif;
                    }
                    /* defining some variables for the offcanvas */
                    :root {
                        --offcanvas-width: 270px;
                        --topNavBarHeight: 56px;
                    }
                    .sidebar-nav {
                        width: var(--offcanvas-width);
                    }
                    .sidebar-link{
                        display: flex;
                        align-items: center;
                    }
                    .sidebar-link .right-icon{
                        display: inline-flex;
                    }
                    .sidebar-link[aria-expanded="true"] .right-icon{
                        transform: rotate(180deg);
                        transition: all ease 0.25s;
                    }
                    .innerCard{
                        background-color: #eeeded;
                    }
                    .editAdm{
                        color: #198754;
                    }
                    .editAdm:hover{
                        color: #292b2c;
                    }
                    /* make the offcanvas visible on the large screens */
                    @media (min-width: 992px) {
                        body {
                            overflow: auto !important;
                        }

                        .Offcanvas-backdrop::before {
                            display: none;
                        }
                        .sidebar-nav {
                            transform: none;
                            visibility: visible !important;
                            top: var(--topNavBarHeight);
                            height: calc(100% - var(--topNavBarHeight));
                        }
                        main {
                            margin-left: var(--offcanvas-width);
                        }
                    }
                    @media (max-width: 992px) {
                        /* active doctor */
                        .AP {
                            margin-right: auto;
                        }
                        /* active patients */
                        .AD {
                            margin-left: auto;
                        }
                        .navbar-brand{
                            margin-left: auto;
                            margin-right: auto;
                        }
                    }
                </style>
            </head>
            <body>
                <?php require_once TEMPLATES_PATH . "/fadmin-navbar.php"; ?>
                <!-- main section starts here -->
                <main class="mt-5 pt-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card ms-auto me-auto outerCard" style="max-width: 55rem;">
                                <div class="card-body">
                                    <div class="card text-dark innerCard mb-3">
                                        <div class="card-title ms-2 mt-2">
                                            <h4 class="text-muted" style="font-weight: 600; font-size: 1.5rem;">Profile: Password Change</h4>
                                        </div>
                                        <hr class="ms-2" style="max-width: 60%;">

                                        <!-- Card Body -->
                                        <div class="card-body">
                                            <!-- Form -->
                                            <form id="edit_admin_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                                <!-- Admin ID -->
                                                <div class="mb-3 row">
                                                    <label for="adminid" class="col-sm-3 col-form-label">Admin ID: </label>
                                                    <div class="col-sm-9">
                                                        <div class="form-control-plaintext px-2" id="adminid"><?php echo $user->get_adminid(); ?></div>
                                                    </div>
                                                </div>
                                                <!-- Current Password -->
                                                <div class="mb-3 row">
                                                    <label for="currentpassword" class="col-sm-3 col-form-label">Current Password: </label>
                                                    <div class="mb-3 col-sm-7">
                                                        <div class="input-group"  id="currentpassword-container">
                                                            <input type="password" name="currentpassword" id="currentpassword" class="form-control" value="<?php echo $admin['currentpassword']; ?>">
                                                            <button class="btn btn-outline-secondary"  id="currentpassword-hide-show" type="button" value="currentpassword">
                                                                <i class="fas fa-eye" id="currentpassword_show_eye"></i>
                                                                <i class="fas fa-eye-slash d-none" id="currentpassword_hide_eye"></i>
                                                            </button>
                                                            <span id="error-currentpassword" class="invalid-feedback"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br/>
                                                <!-- Admin Password -->
                                                <div class="mb-3 row">
                                                    <label for="password" class="col-sm-3 col-form-label">Password: </label>
                                                    <div class="mb-3 col-sm-7">
                                                        <div class="input-group"  id="password-container">
                                                            <input type="password" name="credentials[password]" id="password" class="form-control" value="<?php echo $admin['credentials']['password']; ?>">
                                                            <button class="btn btn-outline-secondary"  id="password-hide-show" type="button" value="password">
                                                                <i class="fas fa-eye" id="password_show_eye"></i>
                                                                <i class="fas fa-eye-slash d-none" id="password_hide_eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Confirm Password -->
                                                <div class="mb-3 row">
                                                    <label for="confirmpassword" class="col-sm-3 col-form-label">Confirm Password: </label>
                                                    <div class="mb-3 col-sm-7">
                                                        <div class="input-group" id="confirmpassword-container">
                                                            <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" value="<?php echo $admin['confirmpassword']; ?>">
                                                            <button class="btn btn-outline-secondary"  id="confirmpassword-hide-show" type="button" value="confirmpassword">
                                                                <i class="fas fa-eye" id="confirmpassword_show_eye"></i>
                                                                <i class="fas fa-eye-slash d-none" id="confirmpassword_hide_eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-grid gap-5 d-md-flex justify-content-md-between" style=" margin-top: 30px;">
                                                    <a href="<?php echo FADMIN_WEB . "/views/admin.php"; ?>" class="btn btn-danger me-md-2 mr-2">
                                                        <span><i class="fas fa-window-close"></i></span>
                                                        <span>Cancel</span>
                                                    </a>
                                                    <button type="submit" name="edit_admin" class="btn btn-dark mr-2">
                                                        <span><i class="fas fa-save"></i></span>
                                                        <span>Save</span>
                                                    </button>

                                                </div>
                                            </form><!-- END FORM -->
                                        </div><!-- END CARD BODY --> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <!-- main ends here -->
                <script>
                    $('#nav-edit-profile').addClass('active');
                    $('#currentpassword-hide-show, #password-hide-show,#confirmpassword-hide-show').on('click', function () {
                        console.log($(this).val());
                        var x = document.getElementById($(this).val());
                        var show_eye = document.getElementById($(this).val() + "_show_eye");
                        var hide_eye = document.getElementById($(this).val() + "_hide_eye");
                        hide_eye.classList.remove("d-none");
                        if (x.type === 'password') {
                            x.type = 'text';
                            show_eye.style.display = "none";
                            hide_eye.style.display = "block";
                        } else {
                            x.type = 'password';
                            show_eye.style.display = "block";
                            hide_eye.style.display = "none";
                        }

                    });
                    $("#edit_admin_form").validate({
                        rules: {
                            "currentpassword": {
                                required: true
                            },
                            "credentials[password]": {
                                required: true,
                                oneDigit: true,
                                lowerCase: true,
                                upperCase: true,
                                specialChar: true,
                                minlength: 8,
                                maxlength: 32

                            },
                            "confirmpassword": {
                                required: true,
                                equalTo: "#password"
                            }
                        },
                        messages: {
                            "currentpassword": {
                                required: "Please provide current password"
                            },
                            "credentials[password]": {
                                required: "Please provide a password",
                                minlength: "Password needs to be at least 8 characters",
                                maxlength: "Password exceeded 32 characters limit"
                            },
                            "confirmpassword": {
                                required: "Please provide a confirm password",
                                equalTo: "Please enter the same password"
                            }
                        },
                        errorElement: "em",
                        errorPlacement: function (error, element) {
                            // Add the `help-block` class to the error element
                            if (element.is('#currentpassword')) {
                                error.insertAfter(element.parents('#currentpassword-container'));
                            } else if (element.is("#password")) {
                                error.insertAfter(element.parents('#password-container'));
                            } else if (element.is("#confirmpassword")) {
                                error.insertAfter(element.parents('#confirmpassword-container'));
                            } else {
                                error.insertAfter(element);
                            }
                            error.addClass("help-block invalid-feedback");
                        },
                        success: function (label, element) {
                            // Add the span element, if doesn't exists, and apply the icon classes to it.

                            $(element).addClass("is-valid");
                        },
                        highlight: function (element, errorClass, validClass) {
                            $(element).addClass("is-invalid").removeClass("is-valid");
                        },
                        unhighlight: function (element, errorClass, validClass) {
                            $(element).addClass("is-valid").removeClass("is-invalid");
                            // if (element.is(":radio")) {
                            //     $("#Male").addClass("is-valid").removeClass("is-invalid");
                            // }
                        }
                    });



                    /*----------------------------------------------
                     CLIENT SIDE REGULAR EXPRESSION FOR PASSWORD
                     -----------------------------------------------*/

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



                </script>
                <!-- bootstrap js link -->
                <script
                    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
                    crossorigin="anonymous"
                ></script>
            </body>
        </html>
        <?php
        if (isset($_POST['edit_admin'])):
            if (!(in_array(false, $validArr))):
                $change_status = Account_User::change_password($user_email, $admin['credentials']['password'], $admin['currentpassword']);
                if ($change_status):
                    ?>
                    <script>
                        window.location.replace(window.location.origin + '<?php echo FADMIN_WEB . "/views/admin.php"; ?>');
                    </script>
                    <?php
                else:

                    # Add Some Error Message 
                    ?>
                    <script>
                        console.log("current password is incorrect");
                        $('#currentpassword').addClass("is-invalid");
                        $('#error-currentpassword').html("Incorrect Password Entered");
                    </script>
                <?php
                endif;
            endif;
        endif;
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>