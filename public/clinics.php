<!-- This File Is Solely Used For Debugging -->
<!DOCTYPE html>
<?php
session_start();
/* Load Config File */
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
    require VENDOR_PATH . '/autoload.php';
?>

<html>

<head>
    <!-- Title -->
    <title>FYP-21-S2-24: FAQs</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <!-- Styling -->
    <?php require_once TEMPLATES_PATH . '/bootstrap.php' ?>
    <!-- Prevent Form Resubmission -->
    <script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
    <style>
        /* .container.custom-container {
      margin: 0 100px;
    } */
    </style>

</head>

<body>
    <!-- Logic -->
    <?php
       require_once USER_MOD . '/Account_User.php';
       require_once USER_MOD . '/Patient.php';
       require_once USER_MOD . '/Medical_Personnel.php';
       require_once USER_MOD . '/Facility_Admin.php';
       require_once USER_MOD . '/Super_Admin.php';
       require_once FACILITY_MOD . '/Medical_Facility.php';

       require_once DB_MOD . '/DbQuery.php';
       require_once DB_MOD . '/Database.php';
       require_once DB_MOD . '/DbStorage.php';

        if (isset($_SESSION["user"])):
 
        $user = unserialize($_SESSION["user"]);
        include TEMPLATES_PATH . '/navbar-loggedin.php';
    else:
        include TEMPLATES_PATH . '/navbar.php';
    endif;
        // Code here

       
        function retrieve_facility_icon(array $facilities): array {
            $icon_url_arr = array();
            $db_storage = new DbStorage();
            foreach ($facilities as $facility):
                $img_path = "facility/facilityicon/" . $facility->get_facilityid() . ".png";
                $icon_url_arr[$facility->get_facilityid()] = $db_storage->retrieve_data_url($img_path);
            endforeach;
            return $icon_url_arr;
        }
        $facilities = Medical_Facility::retrieve_all_facilities();
        $facility_icons = retrieve_facility_icon($facilities);
        
        ?>
    <!-- Show Different Sections Of FAQs  (Make Sure Can MInimize and Maximize) -->
    <div>
        <!-- Navigation -->

        <div class="header container">
            <br>
            <h1 class="display-6 m-5">Registered Hospitals and Clinics</h1>
        </div>

        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-3">

                        </div>
                        <div class="col-9">

                        </div>
                    </div>
                    <?php
                        $html = "";


                            $html = $html."";
                            foreach($facilities as $facility):
                            $icon = $facility_icons[$facility->get_facilityid()];

                            $html .= '<div class="row my-5">';
                            $html .= '<div class="col-3">';
                            $html .= '<img style="height: 100%; width: 35%; object-fit: contain; margin-left: 7rem;" src="'.$icon.'"
                            alt="<No Image Available>"
                            onerror="this.onerror=null;this.src=\'../img/example_img.jpg\';"
                            class="img-fluid" width="" height="100" />';
                            $html .= '</div>';
                            $html .= '<div class="col-auto">';
                            $html .= '<h1 class="lead" style="font-size:2rem"><strong>'.$facility->get_facilityname().'</strong></h1>';
                            $html .= '<p class="lead">'.$facility->get_address().'</p>';
                            $html .= '<p class="lead">Contact: +65 '.$facility->get_contactnumber().'</p>';
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<hr/>';
                            endforeach;
                  
                    echo $html;
                    ?>

                </div>
            </div>
        </div>

    </div>
    <!-- Footer -->
</body>

</html>