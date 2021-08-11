<!DOCTYPE html>
<html lang="en">

<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';

require_once APPT_MOD . '/Appointment_Record.php';
require_once ENUMS_PATH . '/User_Type.php';

require_once ENUMS_PATH . '/Health_Info_Type.php';
require_once HINFO_MOD . '/Health_Info.php';
require_once UTIL_MOD . '/StringUtils.php';

include TEMPLATES_PATH . '/bootstrap.php';
include_once TEMPLATES_PATH . '/navbar.php';
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article</title>
    <style>
    h4 {
        margin-bottom: 0px;
    }
    </style>
</head>

<body>
    <?php

if (isset($_SESSION["user"])):
        
    $user = unserialize($_SESSION["user"]);
        include TEMPLATES_PATH . '/navbar-loggedin.php';
    else:
        include TEMPLATES_PATH . '/navbar.php';
    endif;


    if (isset($_GET['id'])):
    
        $article = Health_Info::retrieve_health_info_by_id($_GET['id']);

        $desc = nl2br($article->get_descriptions());
        $type = $article->get_type();
        if ($type == 'World Health Notice')
        {
            $badge = '<span class="badge bg-warning">World Health Notice</span>';
            $icon = '<i class="fas fa-shield-virus fa-3x opt-icon"></i>';
        } else if ($type == "Doctor's Advice")
        {
            $badge = '<span class="badge bg-primary">Doctor\'s Advice</span>';
            $icon = '<i class="fas fa-user-md fa-3x opt-icon"></i>';
        }   
        else if($type == 'Health Tips')
        {
            $badge = '<span class="badge bg-success">Health Tips</span>';
            $icon = '<i class="fas fa-plus-square fa-3x opt-icon"></i>';
        }
    
?>
    <div class="container mt-5">

        <div class="row">
            <div class="col">

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="./">Article List</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Article:
                            <?php echo $article->get_title(); ?></li>
                    </ol>
                </nav>
                <h1 class="display-4">
                    <strong> <?php echo $article->get_title();?> </strong>
                </h1>
                <h4>
                    <?php echo $badge ?>
                </h4>

                <?php
               echo '<br><small class="text-muted">Created on '.$article->get_createdon()->get_date().'</small>';
                if($article->get_createdon()->get_date() != $article->get_updatedon()->get_date())
                {
                    echo '<small class="text-muted"> • Updated on '.$article->get_updatedon()->get_date().'</small>';
                }
                echo '<p class="lead mt-5">'.$desc.'</p>';
                ?>
            </div>
        </div>

    </div>
</body>
<?php 
    else:

endif;?>

</html>