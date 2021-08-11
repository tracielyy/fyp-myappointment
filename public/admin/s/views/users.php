<?php
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';

require_once ENUMS_PATH . '/Health_Info_Type.php';
require_once HINFO_MOD . '/Health_Info.php';
require_once UTIL_MOD . '/StringUtils.php';
require_once UTIL_MOD . '/ArrayCreation.php';

require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';

require_once FACILITY_MOD . '/Medical_Facility.php';

/*
 *  VIEW LIST OF USERS 
 */

if (!isset($_SESSION['user'])):
    header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
else:
    $user = unserialize($_SESSION["user"]);
    $user_type = $user->get_usertype();
    $user_email = $user->get_email();

    // Check If User Is Super Admin
    if (!User_Type::check_user_type(User_Type::SUPER_ADMIN, $user_type)):
        header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE
    else:

        // Retrieve Users (DEFAULT) 
        //$user_list = retrieve_user(User_Type::FACIILITY_ADMIN);
        
        ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Facility</title>
    <!-- fontawesome -->
    <script src="https://kit.fontawesome.com/dcfd5ba5e7.js" crossorigin="anonymous"></script>
    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;900&display=swap"
        rel="stylesheet" />
    <!-- bootstrap cdn link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
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

    <!-- DATATABLE JS -->

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js">
    </script>

    <!-- DATATABLE JS RESPONSIVE-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
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

    .sidebar-link {
        display: flex;
        align-items: center;
    }

    .sidebar-link .right-icon {
        display: inline-flex;
    }

    .sidebar-link[aria-expanded="true"] .right-icon {
        transform: rotate(180deg);
        transition: all ease 0.25s;
    }

    .innerCard {
        background-color: #eeeded;
    }

    .editAdm {
        color: #198754;
    }

    .editAdm:hover {
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

        .navbar-brand {
            margin-left: auto;
            margin-right: auto;
        }
    }
    </style>
</head>

<body>
    <?php require_once TEMPLATES_PATH . "/sadmin-navbar.php"; ?>
    <!-- Current Page: Display Users By User_Type -->
    <main class="mt-5 pt-3">
        <div class="container mb-5" style="margin-top:100px;">
            <button id="selectuser-hidden" class="d-none" type="button"></button>
            <button id="selectuser-admin" class="btn btn-secondary" type="button">Facility Admin</button>
            <button id="selectuser-medical" class="btn btn-secondary" type="button">Medical Personnel</button>
            <button id="selectuser-patient" class="btn btn-secondary" type="button">Patient</button>
            <div class="mt-5">
                <table id="users" class="display mt-5" style="width:100%">
                    <thead>
                        <tr id="heading">
                            <th>Name</th>
                            <th>Email</th>
                            <th>Facility Name</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                        </tr>
                    </thead>
                </table>

            </div>

        </div>


    </main>
    <!-- End of user display -->
    <!-- js code for the bootstrap tooltip -->
    <script>
    $('#nav-users').addClass('active');

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    var table;
    
    $("#selectuser-admin").on("click", function() {
        $(this).removeClass('btn-secondary');
        $(this).addClass('btn-primary');

        $('#selectuser-medical').removeClass('btn-primary');
        $('#selectuser-patient').removeClass('btn-primary');
        $('#selectuser-medical').addClass('btn-secondary');
        $('#selectuser-patient').addClass('btn-secondary');

        table.destroy();
        $('#heading').children().remove();
        $('tbody').children().remove();
        var headers = "<th>Name</th><th>Email</th><th>Facility Name</th><th>Address</th><th>Contact Number</th>";
        $('#heading').append(headers);

        table = $('#users').DataTable({
            "ajax": "func/user_facility.php"
        });

    });

    $("#selectuser-medical").on("click", function() {
        $(this).removeClass('btn-secondary');
        $(this).addClass('btn-primary');

        $('#selectuser-admin').removeClass('btn-primary');
        $('#selectuser-patient').removeClass('btn-primary');
        $('#selectuser-admin').addClass('btn-secondary');
        $('#selectuser-patient').addClass('btn-secondary');
        
        table.destroy();
        $('#heading').children().remove();
        $('tbody').children().remove();
        var headers = "<th>First Name</th><th>Last Name</th><th>Specialization</th><th>Medical Liscense</th><th>Email</th><th>Gender</th>";
        $('#heading').append(headers);

        table = $('#users').DataTable({
            "ajax": "func/user_medical.php"
        });
    });

    $("#selectuser-patient").on("click", function() {
        $(this).removeClass('btn-secondary');
        $(this).addClass('btn-primary');

        $('#selectuser-medical').removeClass('btn-primary');
        $('#selectuser-admin').removeClass('btn-primary');
        $('#selectuser-medical').addClass('btn-secondary');
        $('#selectuser-admin').addClass('btn-secondary');

        
        table.destroy();
        $('#heading').children().remove();
        $('tbody').children().remove();
        var headers = "<th>First Name</th><th>Last Name</th><th>Gender</th>";
        $('#heading').append(headers);

        table = $('#users').DataTable({
            "ajax": "func/user_patient.php"
        });
    });

    $("#selectuser-hidden").on("click", function() {

        $('#selectuser-admin').removeClass('btn-secondary');
        $('#selectuser-admin').addClass('btn-primary');
        table = $('#users').DataTable({
            "ajax": 'func/user_facility.php'
        });
    });

    $(document).ready(function() {
        
        $( "#selectuser-hidden" ).click();
        
    });
    </script>
    <!-- bootstrap js link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>
<?php
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>