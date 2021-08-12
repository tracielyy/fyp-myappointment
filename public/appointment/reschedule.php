<?php
/*
 *  @author: tracieqwynn
 */

session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';

require_once TIME_MOD . '/Time.php';
require_once EMAIL_MOD . '/EmailTemplate.php';

require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once USER_MOD . '/Medical_Personnel.php';

require_once ENUMS_PATH . '/User_Type.php';
require_once ENUMS_PATH . '/Appointment_Type.php';

require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/DbStorage.php';
require_once DB_MOD . '/Database.php';

require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';
require_once APPT_MOD . '/Appointment_Record.php';

/*
 * RESCHEDULE APPOINTMENT
 */
if (!isset($_SESSION['user'])):
    header("Location:./login.php"); # -- REDIRECT USER TO THE LOGIN PAGE
else:
    $user = unserialize($_SESSION["user"]);

    if (!User_Type::check_user_type(User_Type::PATIENT, $user->get_usertype())):
        header("Location:/"); # -- REDIRECT USER TO THE LANDING PAGE

    else:
        if ($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER['REQUEST_METHOD'] == "POST"):

            function valid_vars(string $user_email, string $id): bool|Appointment_Record {
                $appt_obj = Appointment_Record::retrieve_appointment_by_id($user_email, $id);
                if ($appt_obj === null):
                    return false;
                endif;
                return $appt_obj;
            }

            # -- Get The Next Day -- #
            $cal_default = Time::CALENDAR_FORMAT_DEFAULT;
            $next_day = Time::get_enddate(date($cal_default), 1, $cal_default);

            # -- Allow Date Selection For 6 Mths (est. 180 days) -- #
            $future_days = 180;
            $max_date = Time::get_enddate($next_day, $future_days, $cal_default);

            # -- Check For Some On Change Event -- #
            $selected_date = Time::date_format_default($next_day);
            $appt_date = $next_day;
            $patient_doc_id = Account_User::retrieve_user_doc_id($user->get_email());
            ?>

            <!DOCTYPE html>
            <html>
                <head>
                    <!-- Title -->
                    <title>FYP-21-S2-24: Appointment Reschedule</title>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">

                    <!-- Styling -->
                    <?php include TEMPLATES_PATH . '/bootstrap.php'; ?>
                    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css'>
                    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js'></script>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
                    <!-- Latest compiled and minified CSS -->
                    <link rel="stylesheet"
                          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
                    <!--Latest compiled and minified JavaScript-->
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
                    <!-- jQuery -->
                    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                    <script src="./../js/calendar.js"></script>
                    <link rel="stylesheet" href="./../css/createappointment.css">
                    <!-- Prevent Form Resubmission -->
                    <script>
                        if (window.history.replaceState) {
                            window.history.replaceState(null, null, window.location.href);
                        }
                    </script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/wrick17/calendar-plugin@master/style.css">
                    <script>
                        function set_slotid(slotid) {
                            $('#hide_slotid').val(slotid);
                            console.log(slotid);
                        }
                        // RESCHEDULE APPOINTMENT ON CLICK TRIGGER
                        function reschedule_appt() {
                            console.log("appointment type: " + $('#hide_appointmenttype').val());
                            console.log("new slotid: " + $('#hide_slotid').val());
                            console.log("facilityid : " + $('#hide_facilityid').val());

                            console.log("new date: " + $('#hide_date').val());
                            $('#hide_form').submit();
                        }
                    </script>
                </head>
                <body>
                    <!-- Navigation -->
                    <?php include TEMPLATES_PATH . '/navbar-loggedin.php'; ?>
                    <?php
                    if (!isset($_GET['id'])):
                        ?>
                        <!-- SHOW INVALID PAGE -->
                        <main class="pt-1">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="alert alert-danger" role="alert">
                                        Invalid URL
                                    </div>
                                </div>
                            </div>
                        </main>
                        <?php
                    else:
                        $id = $_GET['id'];
                        # Check if the id exist in database for edit
                        $appt_obj = valid_vars($user->get_email(), $id);
                        if (!$appt_obj):
                            ?>
                            <main class="pt-1">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="alert alert-danger" role="alert">
                                            The appointment that you are looking for does not exist.
                                        </div>
                                    </div>
                                </div>
                            </main>
                            <?php
                        else: /* If The Variables Exists In The Database */

                            $facilityid = $appt_obj->get_facility()->get_facilityid();
                            if ($_SERVER["REQUEST_METHOD"] == "POST"):
                                $booking_info = array(
                                    'appointmentid' => '',
                                    'appointmenttype' => '',
                                    'facilityid' => '',
                                    'slotid' => ''
                                );

                                // If the reschedule form is submitted
                                if (isset($_POST['reschedule_appt'])):


                                    $validArr = array();
                                    // ___ Setting Of Booking Information
                                    foreach ($_POST as $key => $value):
                                        if (isset($booking_info[$key])):
                                            $booking_info[$key] = htmlspecialchars($value);

                                            $validArr[$key] = False; // Set All Field Validation Check As False
                                            // -- Valid If It Is Not Empty
                                            if (!empty($booking_info[$key])):
                                                $validArr[$key] = True;
                                            endif;

                                        endif;
                                    endforeach; # -- END LOOPING INFO TO ARRAY

                                    // -- BOOK AN APPOINTMENT  (Put This Function In The Create Appointment Page)
                                    function reschedule_appt(string $patient_email, array $booking_info, Appointment_Record $current_appt): bool|Appointment_Record {

                                        $patient_doc_id = Account_User::retrieve_user_doc_id($patient_email);

                                        $new_slot = ($booking_info['appointmenttype'] == Appointment_Type::SPECIALIST_CONSULTATION) ?
                                                Special_Slot::retrieve_apptslot_by_id($booking_info['slotid'], $booking_info['facilityid']) :
                                                Normal_Slot::retrieve_apptslot_by_id($booking_info['slotid'], $booking_info['facilityid']);

                                        # Validate Appointment
                                        $valid = Appointment_Record::validate_appt_booking($patient_doc_id, $booking_info);
                                        if ($valid || $current_appt->get_appointmentslot()->get_appointmentschedule()->get_date() == $new_slot->get_appointmentschedule()->get_date()):

                                            # Update User Appointment Record
                                            $appt_record = Appointment_Record::update_appointment_schedule($patient_doc_id, $booking_info['appointmentid'], $booking_info['slotid']);

                                            # Remove From Old Slot
                                            remove_from_slot($patient_doc_id, $current_appt);

                                            # Add To New Slot
                                            add_to_slot($patient_doc_id, $booking_info);

                                            return $appt_record;

                                        endif;
                                        return false; # -- Same Day Booking
                                    }

                                    // -- Remove Patient From Appropriate Slot 
                                    function remove_from_slot(string $patient_doc_id, Appointment_Record $current_appt): void {
                                        switch ($current_appt->get_appointmenttype()):
                                            case Appointment_Type::CHECK_UP:
                                            case Appointment_Type::DOCTOR_CONSULTATION:
                                                Normal_Slot::remove_patient_from_slot($current_appt->get_appointmentslot()->get_slotid(), $current_appt->get_facility()->get_facilityid(), $patient_doc_id);
                                                break;
                                            case Appointment_Type::SPECIALIST_CONSULTATION:
                                                Special_Slot::remove_patient_from_slot($current_appt->get_slotid());
                                                break;
                                        endswitch;
                                    }

                                    // -- Add Patient To Appropriate Slot 
                                    function add_to_slot(string $patient_doc_id, array $booking_info): void {
                                        switch ($booking_info['appointmenttype']):
                                            case Appointment_Type::CHECK_UP:
                                            case Appointment_Type::DOCTOR_CONSULTATION:
                                                Normal_Slot::insert_patient_to_slot($booking_info['slotid'], $patient_doc_id, $booking_info['facilityid']);
                                                break;
                                            case Appointment_Type::SPECIALIST_CONSULTATION:
                                                Special_Slot::insert_patient_to_slot($booking_info['slotid'], $patient_doc_id);
                                                break;
                                        endswitch;
                                    }

                                endif;
                            endif; # -- END POST REQUET 
                            ?>


                            <!-- HIDDEN FIELDS For Appointment Form Submission -->
                            <form id="hide_form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?' . http_build_query($_GET); ?>">
                                <input type="hidden" name="reschedule_appt" id="reschedule_appt" value=""/>
                                <input type="hidden" name="appointmentid" id="hide_appointmentid" value="<?php echo $appt_obj->get_appointmentid(); ?>"/>
                                <input type="hidden" name="facilityid" id="hide_facilityid" value="<?php echo $appt_obj->get_facility()->get_facilityid(); ?>" />
                                <input type="hidden" name="appointmenttype" id="hide_appointmenttype" value="<?php echo $appt_obj->get_appointmenttype(); ?>" />
                                <input type="hidden" name="date" id="hide_date" />
                                <input type="hidden" name="specialist" id="hide_specialist" /> <!-- Doctor's Email -->
                                <input type="hidden" name="slotid" id="hide_slotid" />
                            </form>
                            <!--HIDDEN FIELDS END-->
                            <!-- Same Day Booking -->
                            <div class="justify-content-center" style="min-width:720px!important; display:none;" id="appt-error">
                                <div class="alert alert-danger" role="alert" id="alert-message">

                                </div>
                            </div>
                            <!-- END OF SAME DAY BOOKING -->
                            <!-- If Specialist Then Show A Possible Selection Of  Other Doctors (???) -->
                            <div class="container mt-5 d-flex justify-content-center" style="min-width:720px!important" id="appt-booking">
                                <div class="col-11 col-offset-2">
                                    <div class="display-6">Reschedule Appointment</div>
                                    <form id="reschedule-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                                        <div class="card mt-3">
                                            <!-- LOAD SLOTS -->
                                            <div id="appt-slots" class="step" >
                                                <div class="text-center">
                                                    <h5 class="card-title font-weight-bold pb-2 mt-3">Time Slots</h5>
                                                </div>
                                                <div class="card-body p-4">
                                                    <!--Appointment Date Selection-->
                                                    <div class="form-group row">
                                                        <div class="calendar-wrapper"></div>
                                                    </div>

                                                    <!-- Spinner Display (Loading) -->
                                                    <div id="spinner_display">
                                                    </div>

                                                    <!--List Of Available Appointments (Slots Will Be Updated Via jQuery) -->
                                                    <div class="list-group mt-3" id="display_slots"></div>
                                                </div>
                                            </div><!-- END OF SLOTS LOAD -->

                                            <div class="card-footer">
                                                <button onclick="reschedule_appt()" type="button" class="action submit btn btn-sm btn-outline-success float-end" >
                                                    Reschedule
                                                </button>
                                            </div> <!-- END OF CARD FOOTER -->
                                        </div><!-- Card Form -->
                                </div>
                            </form>
                        </div>
                        <br /><br />

                        <script>
                            /*
                             |---------------------|
                             |  CALENDAR SCRIPT    |
                             |---------------------|
                             */

                            function selectDate(date) {
                                $('.calendar-wrapper').updateCalendarOptions({
                                    date: date
                                });
                                function prefixZero(num) {
                                    if (num < 10) {
                                        return '0' + num;
                                    } else {
                                        return num;
                                    }
                                }
                                ;
                                var pickedDay = new Date(date);
                                var dayToQuery = prefixZero(pickedDay.getDate()) + "-" + prefixZero((pickedDay.getMonth() + 1)) + "-" +
                                        pickedDay.getFullYear();
                                console.log(dayToQuery);
                                $('#hide_date');
                                slots_load('<?php echo $facilityid; ?>', '<?php echo $appt_obj->get_appointmenttype(); ?>', dayToQuery, '<?php echo $appt_obj->get_appointmentslot()->get_slotid(); ?>');

                            }

                            var dateTomorrow = new Date();
                            dateTomorrow.setDate(dateTomorrow.getDate() + 1);
                            var defaultConfig = {
                                weekDayLength: 1,
                                date: dateTomorrow,
                                prevButton: "Last Month",
                                nextButton: "Next Month",
                                onClickDate: selectDate,
                                showYearDropdown: true,
                                showTodayButton: false,
                                startOnMonday: true,
                                disable: function (date) {
                                    var dateMax = new Date();
                                    var dateToday = new Date();
                                    dateToday.setDate(dateToday.getDate());
                                    dateMax.setDate(dateMax.getDate() + <?php echo $future_days ?>);
                                    return (date < dateToday || date > dateMax); // Disable the days
                                }
                            };
                            $('.calendar-wrapper').calendar(defaultConfig);





                            // -- END OF CALENDAR SCRIPT --
                            function slots_load(facilityid, appointmenttype, date, slotid) {
                                slotid_arr = slotid.split("~");
                                $('#hide_date').val(date);

                                $('#display_slots').empty();
                                $.ajax({
                                    type: "POST",
                                    url: "func/loadslots.php",
                                    data: {
                                        ajax: true,
                                        reschedule: true,
                                        patient_id: '<?php echo $patient_doc_id; ?>',
                                        set_facilityid: facilityid,
                                        set_appointmenttype: appointmenttype,
                                        set_date: date,
                                        set_specialist: slotid_arr[2]
                                    },
                                    success: function (data) {
                                        console.log("Load Step 1 & 2");
                                        console.log("facility id: " + facilityid);
                                        console.log("appointment type: " + appointmenttype);
                                        console.log("default date: " + date);
                                        var slot_arr = null;
                                        try {
                                            console.log(JSON.stringify(data));
                                            var slot_arr = JSON.parse(data);
                                            if (slot_arr.length === 0) {
                                                $('#spinner').remove();
                                                $('#display_slots').append("<div>No Slots Available</div>");
                                            } else {
                                                for (var i = 0; i < slot_arr.length; i++) {
                                                    var slot_description = slot_arr[i]['slotdescription'];
                                                    var sid = slot_arr[i]['slotid'];
                                                    var btn = `<button onclick="set_slotid(this.id)"  type="button" class="list-group-item list-group-item-action timebtn" id="${sid}" name="slotid" value="${sid}" 
                                    aria-current="true">${slot_description}</button>`;
                                                    console.log(sid);
                                                    $('#spinner').remove();
                                                    $('#display_slots').append(btn);
                                                }
                                            }
                                        } catch (e) {
                                            // forget about it :)
                                            console.log(e);
                                            $('#display_slots').append("<div>Invalid Input</div>");
                                        }
                                    },
                                    error: function () {
                                        console.log("Error Date Change");
                                    }
                                });
                            }


                            slots_load('<?php echo $facilityid; ?>', '<?php echo $appt_obj->get_appointmenttype(); ?>', '<?php echo $selected_date; ?>', '<?php echo $appt_obj->get_appointmentslot()->get_slotid(); ?>');


                        </script>

                    </body>

                    <?php
                    // If the reschedule form is submitted
                    if (isset($_POST['reschedule_appt'])):
                        # Short Validation
                        if (!in_array(false, $validArr)):
                            $status = reschedule_appt($user->get_email(), $booking_info, $appt_obj);
                            if ($status):
                                # __ Success Reschedule ___
                                ?>
                                <script>
                                    window.location.replace(window.location.origin + '<?php echo APPT_WEB; ?>');
                                </script>
                                <?php
                            else:
                                # __ Error Prompt For Selecting Same Day Appointment Slot __
                                ?>
                                <script>
                                    $('#alert-message').html(' Booking of the same appointment type in the same day is not allowed.<br/>' +
                                            'Proceed to select a different appointment slot  instead.</a>');
                                    $('#appt-booking').hide();
                                    $('#appt-error').show();
                                </script>
                            <?php
                            endif;
                        else:
                            # __ Error Prompt For Not Selecting The Slot __
                            ?>
                            <script>
                                $('#alert-message').html('Slot Not Selected');
                                $('#appt-booking').hide();
                                $('#appt-error').show();
                            </script>
                        <?php
                        endif;

                    endif; # -- END RESCHEDULE POST CALL
                endif; # -- END FOR VALID VARS
            endif; # -- END GET ID CHECK ISSET
        endif; # -- END GET & POST REQUEST
    endif; # -- END PATIENT CHECK
endif;  # -- END USER CHECK 
?>