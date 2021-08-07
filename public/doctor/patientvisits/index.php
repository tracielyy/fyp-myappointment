<?php
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once MEDDOC_MOD . '/Medical_Record.php';

require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';
require_once DB_MOD . '/DbStorage.php';

require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';

require_once SECURE_MOD . '/ValidateIC.php';

require_once USER_MOD . '/Medical_Personnel.php';
require_once EMAIL_MOD . '/EmailTemplate.php';
require_once EMAIL_MOD . '/EmailVerify.php';


/*
 *      DOCTOR VIEW OF PATIENT MEDICAL RECORD (create & update)
 */

if (!isset($_SESSION['user'])):
    header("Location:/"); # -- REDIRECT USER TO THE INDEX PAGE
    echo "error here";
else:
    $user = unserialize($_SESSION["user"]);
    $user_type = $user->get_usertype();
    $user_email = $user->get_email();
    if (!User_Type::check_user_type(User_Type::MEDICAL_PERSONNEL, $user_type)):
        header("Location:/"); # -- REDIRECT USER TO THE INDEX PAGE 
    else:

        ?>
    <!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
    <?php
                    include TEMPLATES_PATH . '/bootstrap.php';
                    include_once TEMPLATES_PATH . '/navbar-loggedin.php';
                    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Record</title>
    <!-- font awesome cdn -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
    <style>
    body {
        font-size: 20px !important;
    }

    .spinner {
        border: 1px solid;
        position: fixed;
        z-index: 1;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5) !important;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 50 50'%3E%3Cpath d='M28.43 6.378C18.27 4.586 8.58 11.37 6.788 21.533c-1.791 10.161 4.994 19.851 15.155 21.643l.707-4.006C14.7 37.768 9.392 30.189 10.794 22.24c1.401-7.95 8.981-13.258 16.93-11.856l.707-4.006z'%3E%3CanimateTransform attributeType='xml' attributeName='transform' type='rotate' from='0 25 25' to='360 25 25' dur='0.6s' repeatCount='indefinite'/%3E%3C/path%3E%3C/svg%3E") center / 100px no-repeat;
        display: none;
    }
    </style>

    <script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    function loopforpresc(value) {

        $(function() {
            $(window).bind('load', function() {

                console.log("test");
                inputpresc =
                    '<div class="input-group mt-3"><input type="text" class="form-control" placeholder="" name="prescriptions[]" value="' +
                    value +
                    '"><button class="btn btn-danger" id="removepresc"><i class="fas fa-minus"></i></button></div>';
                $('.fieldwrapper').append(inputpresc);
                console.log(inputpresc);
            });
        });
    }
    </script>
<div class="spinner"></div>
<script>$('.spinner').show();</script>
   
</head>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "GET" || $_SERVER['REQUEST_METHOD'] == "POST"):

            function retrieve_facility_icon(array $facilities): array {
                $icon_url_arr = array();
                $db_storage = new DbStorage();
                foreach ($facilities as $facility):
                    $img_path = "facility/facilityicon/" . $facility->get_facilityid() . ".png";
                    $icon_url_arr[$facility->get_facilityid()] = $db_storage->retrieve_data_url($img_path);
                endforeach;
                return $icon_url_arr;
            }
            // -- Retrieve All Facility Icons
            $facilities = Medical_Facility::retrieve_all_facilities();
            $facility_icons = retrieve_facility_icon($facilities);

            // id will be generated when the doctor clicks (using user email and mrid)
            // NEED TO VALIDATE THE GET TOKENS
            function valid_get_vars(string $patientid, string $mrid): bool|Medical_Record {
                $mr_object = Medical_Record::retrieve_medical_record($patientid, $mrid);
                if ($mr_object == null):
                    return false;
                else:
                    return $mr_object;
                endif;
            }
            ?>


<body>
    <?php
                    # IF THE GET VARIABLES NOT SET
                    if (!isset($_GET['pt']) && !isset($_GET['id'])):
                        ?>
    <!-- SHOW INVALID PAGE -->
    <div>
        Invalid Page
    </div>
    <?php
                    # IF GET VARS SET THEN CHECK VARS
                    else:
                        $patientid = $_GET['pt'];
                        $mrid = $_GET['id'];

                        # START CHECKING THE VARS
                        $valid_vars = valid_get_vars($patientid, $mrid);
                        if (!$valid_vars):
                            ?>
    <div>
        Invalid GET VARS
    </div>
    <?php
        else: /* If The Variables Exists In The Database */
            $presciptionsArray = array();
            $presciptionsArray = $valid_vars->get_prescriptions();
            $num_of_presc = count($presciptionsArray);
         
            //include_once TEMPLATES_PATH . '/navbar-loggedin.php';
            $diagnosisdesc = $valid_vars->get_diagnosisdesc();
            $practitioner = $valid_vars->get_practitioner();
            $practitionerid = $practitioner->get_nric();
            //echo $practitionerid;

            if($_SERVER["REQUEST_METHOD"] == "POST"):

                
                if(isset($_POST['prescriptions'])) :
                    $presciptionsArray = $_POST['prescriptions'];
                    $num_of_presc = count($presciptionsArray);
                    //echo  $num_of_presc;
                endif;

                if(isset($_POST['diagnosis'])) :
                    $diagnosisdesc = $_POST['diagnosis'];
                    //echo $diagnosisdesc;
                endif;

                   
                    
                Medical_Record::update_medical_record($practitionerid, $patientid , $mrid, $diagnosisdesc,  $presciptionsArray);
                foreach($presciptionsArray as $key => $value) :
                    ?><script>
    loopforpresc(<?php echo "'".$value."'"?>)
    </script><?php
                endforeach;
            else:
                foreach($presciptionsArray as $key => $value) :
                    ?><script>
    loopforpresc(<?php echo "'".$value."'"?>)
    </script><?php
                endforeach;
                
        endif;
            
            // echo $valid_vars->get_createdon()->get_time()."<br/>";
            // echo $valid_vars->get_medicalrecordid()."<br/>";
            // echo $valid_vars->get_appointmenttype()."<br/>";
            // echo $valid_vars->get_slotid()."<br/>";
            // echo $valid_vars->get_diagnosisdesc()."<br/>";

            $array = $valid_vars->get_prescriptions();
            // foreach ($array as $key => $value)
            // {
            //     echo $value."<br/>";
            // }
            $patient = Patient::retrieve_patient_by_id($patientid);
                            ?>
    
    <div class="container mt-5">
        <div class="col-auto">
        <img src="<?php echo $facility_icons[$valid_vars->get_facility()->get_facilityid()]; ?>"
                                                         alt="<No Image Available>"
                                                         onerror="this.onerror=null;this.src='../img/example_img.jpg';"
                                                         class="img-fluid" width="80" height="100" />
        </div>
        <div class="col-auto">
                <h1 class="display-6">
                <?php echo $valid_vars->get_facility()->get_facilityname();?>
                </h1>
        </div>
    </div>
 
    <div class="container mt-5">
        
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Appointment Information
                    </div>
                    <div class="card-body">
                        <strong>Details:</strong>
                        <div class="row">
                            <div class="col">
                                Date: <?php echo $valid_vars->get_createdon()->get_date();?>

                            </div>
                            <div class="col">
                            Type: <?php echo $valid_vars->get_appointmenttype();?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                Time: <?php echo $valid_vars->get_createdon()->get_time();?>
                            </div>
                            <div class="col">
                                Facility: <?php echo $valid_vars->get_facility()->get_facilityname();?>
                            </div>

                        </div>
                        <strong class="mt-4">Patient:</strong>
                        <div class="row">
                            <div class="col">
                                Name: <?php echo $patient->get_firstname(); ?>
                            </div>
                            <div class="col">
                                Date of Birth: <?php echo $patient->get_dob(); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                Gender: <?php echo $patient->get_gender(); ?>
                            </div>
                            <div class="col">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <form method="post" action=<?php echo $_SERVER["PHP_SELF"] . '?'.http_build_query($_GET); ?>>
            <div class="row mt-2">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            Diagnosis
                        </div>
                        <div class="card-body">

                            <textarea type="text" id="diagnosistext" class="form-control" name="diagnosis"
                                rows="15"><?php echo htmlspecialchars($diagnosisdesc);?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2 mb-2">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            Prescriptions
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <button id="addprescription" type="button" class="btn btn-secondary mb-2"
                                        style="float:right">Add prescription</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="fieldwrapper">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="d-grid gap-2 mb-5">
                <button name="submitdiagnosis" type="submit" class="btn btn-primary btn-lg" style="float:right;"
                    id="savechanges">Save Changes</button>
            </div>
        </form>

    </div>


</body>

</html>
<script>
/*---------------------------
        PRESCRIPTIONS
----------------------------*/

$(document).ready(function() {
    $('.spinner').hide();
    var maxField = 10; //Input fields increment limitation
    var addButton = $('#addprescription'); //Add button selector
    var wrapper = $('.fieldwrapper'); //Input field wrapper
    var fieldHTML =
        '<div class="input-group mt-3"><input type="text" class="form-control" placeholder="" name="prescriptions[]" value=""><button class="btn btn-danger" id="removepresc"><i class="fas fa-minus"></i></button></div>'; //New input field html 
    var x = <?php echo $num_of_presc; ?>; //Initial field counter is 1

    //Once add button is clicked
    $(addButton).click(function() {
        //Check maximum number of input fields
        console.log(x);
        if (x < maxField) {
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
            if (x == maxField) {
                $(wrapper).append(
                    '<small class="text-muted" id="maxalert"> Maximum 10 prescriptions reached </small>'
                ); //Add field html
            }
        }

    });

    //Once remove button is clicked
    $(wrapper).on('click', '#removepresc', function(e) {
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });

    $('#savechanges').on('click', function() {

        $('.spinner').show();
        $(".btn-danger").prop("disabled", true);
    });
});
</script>
<?php
                endif; # -- END VARS CHECKS
            endif; # -- END CHECK IF VARS SET
        endif; # -- END GET REQUEST
    endif; # -- END OF USER TYPE CHECK
endif; # -- END OF SESSION CHECK
?>