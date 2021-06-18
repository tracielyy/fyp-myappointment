<!DOCTYPE html>
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require_once UTILS_PATH . '/Time.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once ENTITIES_PATH . '/Appointment_Record.php';
require_once ENUMS_PATH . '/User_Type.php';
require_once ENUMS_PATH . '/Appointment_Type.php';
require_once FUNCTIONS_PATH . '/PatientFunctions.php';
require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';
?>

<html>

<head>
    <!-- Title -->
    <title>FYP-21-S2-24</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Styling -->
    <?php include COMPONENTS_PATH . '/bootstrap.php'; ?>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css'>
    <style>
    .li-search {
        list-style-type: none;
        padding: 0;
        margin: 0;
        border: 1px solid #ddd;
        margin-top: -1px;
        background-color: #f6f6f6;
        padding: 8px;
        font-size: 14px;
        color: black;
        display: block;
        cursor: pointer;
    }

    .li-search:hover {
        background-color: #eee;
    }

    .opt-icon {
        margin-top: 30px;
        margin-bottom: 20px
    }

    .card-block {
        width: 215px;
        border: 1px solid lightgrey;
        border-radius: 5px !important;
        background-color: #FAFAFA;
        margin-bottom: 30px
    }

    .radio {
        display: inline-block;
        border-radius: 0;
        box-sizing: border-box;
        cursor: pointer;
        color: #000;
        font-weight: 500;
        opacity: 0.6;
    }

    .radio.selected {
        box-shadow: 0px 8px 16px 0px #EEEEEE;
        opacity: 1;
    }

    .radio:hover {
        background-color: #d7f5e8;
        opacity: 1;
    }

    .selected {
        background-color: #e0f2ea;
    }

    .timebtn:active {
        background: #e0f2ea;
        box-shadow: 0px 8px 16px 0px #EEEEEE;
        opacity: 1;
    }

    .timebtn:focus {
        background: #e0f2ea;
        box-shadow: 0px 8px 16px 0px #EEEEEE;
        opacity: 1;
    }
    
    </style>
</head>

<body>

    <!-- One "tab" for each step in the form: -->

    <script type="text/javascript">
    var facilityid;
    var appointmenttype;
    var medicalfacility;
    var appointmenttype;
    var date;

    function dateChange(input, date) {

        var ipt = input.id.split(",");
        facilityid = ipt[0];
        appointmenttype = ipt[1];

        console.log("js triggered");
        $.ajax({
            type: "POST",
            url: "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>",
            data: {
                ajax: 1,
                set_location: medicalfacility,
                set_appointmenttype: appointmenttype,
                facilityid: facilityid,
                appointmenttype: appointmenttype,
                date: date
            },
            success: function() {
                $('#apptform').submit();
                console.log("post submit");
                console.log(appointmenttype);
            },
            error: function() {
                console.log("Error Date Change");
            }
        });
    }

    function submitLoc() {
        medicalfacility = $('#inputmf').attr('value');
        $.ajax({
            type: "POST",
            url: "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>",
            data: {
                ajax: 1,
                set_location: medicalfacility,
                set_appointmenttype: 1,
                facilityid: facilityid,
                appointmenttype: appointmenttype,
                date: date
            },
            success: function() {
                $('#inputmf').submit();
                console.log("med submit");
                console.log(medicalfacility);
            },
            error: function() {
                console.log("Error submitting medical facility");
            }
        });
    }

    function submitAT() {
        appointmenttype = $('#inputAT').attr('value');
        $.ajax({
            type: "POST",
            url: "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>",
            data: {
                ajax: 1,
                set_location: medicalfacility,
                set_appointmenttype: appointmenttype,
                facilityid: facilityid,
                appointmenttype: appointmenttype,
                date: date
            },
            success: function() {
                $('#inputmf').submit();
                console.log("appttype submit");
                console.log(appointmenttype);
            },
            error: function() {
                console.log("Error submitting appointmenttype");
            }
        });
    }
    </script>

    <!-- PHP Codes -->
    <?php
        // -- Check If User Is Signed In (When Redirect or Load The Page) -- //
        if (isset($_SESSION["user"])):

            // -- Get Signed In User Information
            $user = unserialize($_SESSION["user"]);
            $user_email = $user->get_email();
            $user_type = $user->get_usertype();
            include COMPONENTS_PATH . '/navbar-loggedin.php'

            // -- Check User Type (PATIENT ONLY) --//
            if (User_Type::check_user_type(User_Type::PATIENT, $user_type)):
                // -- Get All Facilities -- //
                $facility_list = AccountUserFunctions::get_all_facilities();

                // -- Used to store correct data
                $appointmentArr = array(
                    'facilityid' => '',
                    'appointmenttype' => '',
                    'date' => '',
                    'slotid' => ''
                );
                $validArr = array();


                // -- When Submit Appointment
                if ($_SERVER["REQUEST_METHOD"] == "POST") :

                    /* Load Data to Array */
                    foreach ($_POST as $key => $value):
                        if (isset($appointmentArr[$key])):
                            $appointmentArr[$key] = htmlspecialchars($value);
                            $validArr[$key] = False; // Set All Field Validation Check As False
                        endif;
                    endforeach;

                    if (isset($_POST['bookappointment'])):


                        // Possible Validation of Email Before Firestore Query
                        /* ------------ Start Validation ------------ */

                        # -- Facility ID
                        if (!empty($appointmentArr['facilityid'])):
                            $validArr['facilityid'] = True;
                        endif;

                        # -- Appointment Type
                        if (!empty($appointmentArr['appointmenttype'])):
                            $validArr['appointmenttype'] = True;
                        endif;

                        # -- Date
                        if (!empty($appointmentArr['date'])):
                            $appointmentArr['date'] = Time::date_format_default($appointmentArr['date']);
                            $validArr['date'] = True;
                        endif;

                        # -- Slot ID
                        if (!empty($appointmentArr['slotid'])):
                            $validArr['slotid'] = True;
                        endif;

                        /* ------------ End Validation ------------ */
                        echo var_dump($validArr);


                        // Can Only Book Appointment When Required Fields Are Filled
                        if (!in_array(FALSE, $validArr)) :
                            
                            # -- Booking Of Appointment -- #
                            $slot_info_arr = explode("~", $appointmentArr['slotid']);
                            $appointmentArr['slotid'] = $slot_info_arr[1];
                            $appointmentArr['time'] = $slot_info_arr[2];
                            $booking_status = PatientFunctions::book_appointment($user_email, $appointmentArr);
                            
                            # -- Make Use Of The Following Message To Show Patient Their Booking Status -- #
                            if ($booking_status):
                                echo "Booking Success";
                            else:
                                echo "You Have Already Book The Slot Previously";
                            endif;
                        else:
                            echo "Booking Fail";
                        endif;

                    endif;
                endif;
                ?>

    <!-- HTML Page Design -->


    <div class="container mt-5 d-flex justify-content-center" style="min-width:720px!important">

        <div class="col-11 col-offset-2">
            <div class="display-6">Book an Appointment</div>

            <div class="progress mt-3" style="height: 30px;">
                <div class="progress-bar" style="font-weight:bold; font-size:15px;" role="progressbar" aria-valuemin="0"
                    aria-valuemax="100">
                </div>
            </div>
            <form id="apptform" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="card mt-3">
                    <!-- STEP 1 -->
                    <div class="step">
                        <div class="text-center">
                            <h5 class="card-title font-weight-bold pb-2 mt-4">Book for appointment at location: </h5>
                        </div>

                        <div class="card-body p-4">
                            <div class="radio-group row justify-content-between px-3 text-center"
                                style="justify-content:center !important">

                                <div id="mf003"
                                    class="col-auto me-sm-2 mx-1 card-block py-0 text-center radio location">
                                    <div class="opt-icon"><img src="cgh.png" class="img-fluid" width="100"
                                            height="100"></img>
                                    </div>
                                    <p><b>Changi General Hospital</b></p>
                                </div>

                                <div id="mf001"
                                    class="col-auto me-sm-2 mx-1 card-block py-0 text-center radio location">
                                    <div class="opt-icon"><img src="nuh.png" class="img-fluid" width="100"
                                            height="100"></img></div>
                                    <p><b>National University Hospital</b></p>
                                </div>

                                <div id="mf002"
                                    class="col-auto me-sm-2 mx-1 card-block py-0 text-center radio location">
                                    <div class="opt-icon"><img src="tts.png" class="img-fluid" width="75"
                                            height="50"></img>
                                    </div>
                                    <p><b>Tan Tock Seng Hospital</b></p>
                                </div>

                                <input id="inputmf" name="facilityid" type="hidden" value="" />

                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="button" class="action next btn btn-sm btn-outline-secondary float-end"
                                onclick="submitLoc()" disabled="">Next</button>
                        </div>

                    </div> <!-- END OF STEP 1 -->


                    <!-- STEP 2 -->
                    <div class="step" style="display: none">
                        <div class="text-center">
                            <h5 class="card-title font-weight-bold pb-2 mt-3">Book appointment for</h5>
                        </div>

                        <div class="card-body p-4">
                            <div class="radio-group row justify-content-between px-3 text-center"
                                style="justify-content:center !important">

                                <div id="cp" class="col-auto me-sm-2 mx-1 card-block py-0 text-center radio">
                                    <div class="opt-icon"><i class="fas fa-clinic-medical" style="font-size: 80px;"></i>
                                    </div>
                                    <p><b>Check-up</b></p>
                                </div>

                                <div id="dc" class="col-auto me-sm-2 mx-1 card-block py-0 text-center radio">
                                    <div class="opt-icon"><i class="fas fa-stethoscope" style="font-size: 80px;"></i>
                                    </div>
                                    <p><b>Doctor Consultation</b></p>
                                </div>

                                <div id="sc" class="col-auto ms-sm-2 mx-1 card-block py-0 text-center radio">
                                    <div class="opt-icon"><i class="fas fa-user-md" style="font-size: 80px;"></i></div>
                                    <p><b>Specialist Consultation</b></p>
                                </div>

                            </div>

                            <!--    <div class="searchfield input-group px-5">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-search text-white"
                                        aria-hidden="true"></i></span>
                                <input id="txt-search" class="form-control" type="text" placeholder="Search"
                                    aria-label="Search">
                            </div>
                            <div id="filter-records" class="mx-5"></div>
                            </div>  -->

                            <input id="inputAT" name="set_appointment" type="hidden" value="" />

                        </div>
                        <div class="card-footer">
                            <button type="button" class="action back btn btn-sm btn-outline-warning"
                                style="display: none">Back</button>
                            <button type="button" class="action next btn btn-sm btn-outline-secondary float-end"
                                onclick="submitAT()" disabled="">Next</button>
                        </div>
                    </div> <!-- END OF STEP 2 -->

                    <!-- STEP 3 -->
                    <div id="userinfo" class="step" style="display: none">
                        <div class="text-center">
                            <h5 class="card-title font-weight-bold pb-2 mt-3">Time Slots</h5>
                        </div>
                        <div class="card-body p-4">

                            <?php
                    # -- Setting The Appointment Type -- #
                    if (isset($_POST['set_appointmenttype']) || isset($_POST['date'])):
                        
                        echo "hello";
                        if (isset($appointmentArr['appointmenttype']) && isset($appointmentArr['facilityid'])):
                            echo "hello2";

                            #-- Get The Next Day -- #
                            $cal_default = Time::CALENDAR_FORMAT_DEFAULT;
                            $next_day = Time::get_enddate(date($cal_default), 1, $cal_default);

                            # -- Allow Date Selection For 6 Mths (est. 180 days) -- #
                            $future_days = 180;
                            $max_date = Time::get_enddate($next_day, $future_days, $cal_default);

                            # -- Check For Some On Change Event -- #
                            if (isset($_POST['date'])):
                                $selected_date = Time::date_format_default($_POST['date']);
                                $appt_date = Time::date_format_change($selected_date, $cal_default);
                            else:
                                $selected_date = Time::date_format_default($next_day);
                                $appt_date = $next_day;
                            endif;
                            $slots = PatientFunctions::get_apptslots($appointmentArr['facilityid'], $appointmentArr['appointmenttype'], $selected_date);

                            # -- View By Dates -- #
                            ?>
                            <label for="date">Date:</label>
                            <input type="date"
                                id="<?php echo $appointmentArr['facilityid'] . "," . $appointmentArr['appointmenttype']; ?>"
                                name="date" value="<?php echo $appt_date; ?>" min="<?php echo $next_day; ?>"
                                max="<?php echo $max_date; ?>" onchange="dateChange(this, this.value)"><br />
                            <?php
                                   # -- Check & Loop All The Available Slots (Only Show Not Full Slots) -- #
                                   if (empty($slots)):
                                       echo "No Slots Available<br/>";
                                   else:
                                       foreach ($slots as $slot):
                                           $sid = $slot->get_appointmentschedule()->get_date() . "~" . $slot->get_slotid() . "~" . $slot->get_appointmentschedule()->get_time();
                                           ?>
                            <input type="radio" id="<?php echo $sid; ?>" name="slotid" value="<?php echo $sid; ?>" <?php
                                    if ($appointmentArr['slotid'] == $sid):
                                        echo "checked";
                                    endif;
                                    ?> />
                            <label for="<?php echo $sid; ?>"><?php echo $slot->get_slot_description(); ?></label><br />
                            <?php
                                endforeach;
                            endif;
                            ?>

                        </div>
                        <div class="card-footer">
                            <button type="button" class="action back btn btn-sm btn-outline-warning"
                                style="display: none">Back</button>
                            <button type="submit" name="bookappointment"
                                class="action submit btn btn-sm btn-outline-success float-end"
                                style="display: none">Book Now</button>
                        </div>
                        <?php
                        endif;
                    endif;?>
                    </div> <!-- END OF STEP 2 -->


                </div>
            </form>
        </div>

    </div>

    <?php
            else:
                echo "Wrong User Type";
            endif; # Check User Type
        else:
            echo "User Not Logged In";
        endif;
        ?>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js'></script>

    <script type="text/javascript">
    $(".radio-group .radio").on("click", function() {
        $(".selected .fa").removeClass("fa-check");
        $(".radio").removeClass("selected");
        $(this).addClass("selected");

        if ($("#mf001").hasClass("selected") == true) {
            $(".next").prop("disabled", false);
            $('#inputmf').val("mf001");
        } else if ($("#mf002").hasClass("selected") == true) {
            $(".next").prop("disabled", false);
            $('#inputmf').val("mf002");
        } else if ($("#mf003").hasClass("selected") == true) {
            $(".next").prop("disabled", false);
            $('#inputmf').val("mf003");
        }

        if ($("#cp").hasClass("selected") == true) {
            $(".next").prop("disabled", false);
            <?php $checkup = Appointment_Type::CHECK_UP; ?>
            $('#inputAT').val("<?php echo $checkup?>");
        } else if ($("#dc").hasClass("selected") == true) {
            $(".next").prop("disabled", false);
            <?php $dr_consult = Appointment_Type::DOCTOR_CONSULTATION; ?>
            $('#inputAT').val("<?php echo $dr_consult?>");
        } else if ($("#sc").hasClass("selected") == true) {
            $(".next").prop("disabled", false);
            <?php $sp_consult = Appointment_Type::SPECIALIST_CONSULTATION; ?>
            $('#inputAT').val("<?php echo $sp_consult?>");
        }

        // {
        //     setFormFields(false);
        //     $(".next").prop("disabled", false);
        //     $("#filter-records").html("");
        //     $(".searchfield").hide();
        // }
    });

    var step = 1;
    $(document).ready(function() {
        stepProgress(step);
    });

    $(".next").on("click", function() {
        var nextstep = false;
        if (step == 3) {
            nextstep = true;
        } else {
            nextstep = true;
        }

        if (nextstep == true) {
            if (step < $(".step").length) {
                $(".step").show();
                $(".step")
                    .not(":eq(" + step++ + ")")
                    .hide();
                stepProgress(step);
            }
            hideButtons(step);
        }
    });

    // ON CLICK BACK BUTTON
    $(".back").on("click", function() {
        if (step > 1) {
            step = step - 2;
            $(".next").trigger("click");
        }
        hideButtons(step);
    });

    // CALCULATE PROGRESS BAR
    stepProgress = function(currstep) {
        var percent = parseFloat(100 / $(".step").length) * currstep;
        percent = percent.toFixed();
        $(".progress-bar")
            .css("width", percent + "%")
            .html(currstep + " of 3");
    };

    // DISPLAY AND HIDE "NEXT", "BACK" AND "SUMBIT" BUTTONS
    hideButtons = function(step) {
        var limit = parseInt($(".step").length);
        $(".action").hide();
        if (step < limit) {
            $(".next").show();
        }
        if (step > 1) {
            $(".back").show();
        }
        if (step == limit) {
            $(".next").hide();
            $(".submit").show();
        }
    };
    </script>


</body>

</html>