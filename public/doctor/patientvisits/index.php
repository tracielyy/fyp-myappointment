<?php
session_start();
/* Load Config File */
require_once '../../../resources/config.php';
require '../../../vendor/autoload.php';
require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once USER_MOD . '/Account_User.php';
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
    header("Location:./../"); # -- REDIRECT USER TO THE INDEX PAGE
else:
    $user = unserialize($_SESSION["user"]);
    $user_type = $user->get_usertype();
    $user_email = $user->get_email();
    if (!User_Type::check_user_type(User_Type::MEDICAL_PERSONNEL, $user_type)):
        header("Location:./../"); # -- REDIRECT USER TO THE INDEX PAGE 
    else:
        if ($_SERVER['REQUEST_METHOD'] == "GET"):
            // id will be generated when the doctor clicks (using user email and mrid)
            ?><!DOCTYPE html>
            <html lang="en">
                <head>
                    <link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
                    <?php
                    include TEMPLATES_PATH . '/bootstrap.php';
                    include_once TEMPLATES_PATH . '/navbar.php';

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
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Medical Record</title>
                    <!-- font awesome cdn -->
                    <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
                </head>
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
                            ?>
                        <div>Valid</div>
                        </body>
                    <?php
                    endif; # -- END VARS CHECKS
                endif; # -- END CHECK IF VARS SET
            endif; # -- END GET REQUEST
        endif; # -- END OF USER TYPE CHECK
    endif; # -- END OF SESSION CHECK
    ?>

</html>