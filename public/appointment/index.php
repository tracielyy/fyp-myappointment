<?php
session_start();
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';

require_once APPT_MOD . '/Appointment_Record.php';

require_once EMAIL_MOD . '/EmailTemplate.php';

require_once ENUMS_PATH . '/User_Type.php';

require_once TIME_MOD . '/CalendarICS.php';
require_once TIME_MOD . '/Time.php';

/*
 * VIEW APPOINTMENT
 */


$pageName = "viewappointment";

// -- CANCEL APPOINTMENT
function cancel_appointment(string $patient_email, string $appointmentid): Appointment_Record {

    $patient_doc_id = Account_User::retrieve_user_doc_id($patient_email);

    # Get Appointment Record Information Before Removal
    $appt_record = Appointment_Record::retrieve_appointment_by_id($patient_email, $appointmentid);

    # Remove Appointment Record From The Patient DB
    Appointment_Record::remove_appointment_record($patient_doc_id, $appointmentid);

    # Remove Patient From The Slot
    remove_from_slot($patient_doc_id, $appt_record);

    return $appt_record;
}

// Remove Patient From Slot
function remove_from_slot(string $patient_doc_id, Appointment_Record $appt_record): void {

    switch ($appt_record->get_appointmenttype()):
        case Appointment_Type::CHECK_UP:
        case Appointment_Type::DOCTOR_CONSULTATION:
            Normal_Slot::remove_patient_from_slot($appt_record->get_appointmentslot()->get_slotid(), $appt_record->get_facility()->get_facilityid(), $patient_doc_id);
            break;
        case Appointment_Type::SPECIALIST_CONSULTATION:
            Special_Slot::remove_patient_from_slot($appt_record->get_appointmentslot()->get_slotid());
            break;
    endswitch;
}

// -- Check If User Is Signed In (When Redirect or Load The Page) -- //
if (isset($_SESSION["user"])):

    # "Unboxin" User Information
    $user = unserialize($_SESSION["user"]);
    $user_email = $user->get_email();
    $user_type = $user->get_usertype();
    $email['credentials']['email'] = $user_email;


    // -- UPDATE APPOINTMENT RECORD IN PATIENT OBJECT -- //
    # Get Appt Record
    $updated_appt_records = Appointment_Record::retrieve_patient_all_appointments($email);

    # Update Appt Record
    $user->set_appointmentrecords($updated_appt_records);


    // -- When User Click On The Buttons -- //
    if ($_SERVER['REQUEST_METHOD'] == "POST"):

        // -- ADD TO CALENDAR
        if (isset($_POST["calendar-invite"])):

            $appt_event = new CalendarICS($_POST["calendar-invite"], $_POST["calendar-invite"], "MyAppointment");
//            $appt_event = new CalendarICS("2021-11-06 15:00", "2021-11-06 15:00", "Test Event", "ha", "ga");
//            $appt_event->debug_print();
            $appt_event->show();


        // -- CANCEL APPOINTMENT
        elseif (isset($_POST['cancel'])):

            # Delimit & Get Information
            $appointmentid = $_POST['cancel'];

            # -- Cancel The Appointment : Function is working (COMMENT IT FOR  OTHER TESTING PURPOSE) -- #
            $appt_record = cancel_appointment($user_email, $appointmentid);

            # -- Alert User Of Cancelled Appointment
            EmailTemplate::template_cancelappointment($user_email, $appt_record);
            header("Location:./debugviewappointments.php");

        // -- RESCHEDULE APPOINTMENT
        elseif (isset($_POST['reschedule'])):
        # Ask For Reschedule Date & Time (Could Be Some Pop-Up) -- Return Rescheduled Array #
        endif;

    endif;

    // -- TEMPLATES ARE PLACED HERE TO PREVENT HINDERANCE MADE TO 'ADD TO CALENDAR'
    include_once TEMPLATES_PATH . '/navbar-loggedin.php';
    include TEMPLATES_PATH . '/bootstrap.php';

    if (User_Type::check_user_type(User_Type::PATIENT, $user_type)):
        # -- TESTING
        $appt_container = $user->get_appointmentrecords();
        $appt_sorted_container = Appointment_Record::sort_appointment_by_status($appt_container);
        $upcoming_arr = $appt_sorted_container['upcoming'];
        $missed_arr = $appt_sorted_container['missed'];
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <!-- REFRESH EVERY 900 SECONDS (15 MINS) -->
                <meta http-equiv="refresh" content="900">
                <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
                <link rel='stylesheet' href='./css/viewappointments.css'>

                <?php ?>
                <!--
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

                <title>View Appointments</title>
                <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                <script>
                    // -- Pass Information To Modal
                    function upcoming_cancel_appt(appt_info) {
                        var info = appt_info.split("~");
                        var appt_id = info[0];
                        var appt_type = info[1];
                        var appt_date = info[2];
                        var appt_time = info[3];

                        // -- Testing
                        console.log(appt_id);

                        // jQuery Calls To Set The Modal Information 
                        $("#upcoming_cancel_appt_type").html(appt_type);
                        $("#upcoming_cancel_appt_date").html(appt_date);
                        $("#upcoming_cancel_appt_time").html(appt_time);
                        $("#upcoming_cancel_btn").val(appt_id); // Set Appt ID To Btn
                    }

                </script>
            </head>
            <body>

                <div class="row bg-light py-4">
                    <div class="row bg-light">

                        <div class="ms-5 col-xs-8 col-md-6">
                            <h1 class="display-6"> <?php echo $user->get_fullname(); ?> </h1>
                            <h1 class="lead"> Gender: <?php echo $user->get_gender(); ?> </h1>
                            <h1 class="lead"> Date of Birth: <?php echo $user->get_dob(); ?> </h1>
                        </div> <!-- col -->

                    </div>
                </div>


                <div class="container mb-3">
                    <div class="row mt-3">
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <a type="button" href="./create.php" class="btn btn-info btn-lg pb-2">
                                <p class="h4 text-light mt-1"> Create New Appointment </p>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-upcoming-tab" data-bs-toggle="tab" data-bs-target="#nav-upcoming"
                                    type="button" role="tab" aria-controls="nav-upcoming" aria-selected="true">Upcoming</button>
                            <button class="nav-link" id="nav-missed-tab" data-bs-toggle="tab" data-bs-target="#nav-missed" type="button"
                                    role="tab" aria-controls="nav-missed" aria-selected="false">Missed</button>
                        </div>
                    </nav>
                    <form id= "apptform" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div class="mt-4">
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-upcoming" role="tabpanel" aria-labelledby="nav-upcoming-tab">
                                    <?php
                                    if ($upcoming_arr == null):
                                        ?>
                                        <br>
                                        <p class="text-center text-muted display-6">No upcoming appointments</p>
                                    </div>
                                    <?php
                                else:
                                    ?>
                                    <!-- Nav tabs -->
                                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 g-4">
                                        <?php
                                        // For Each Upcoming Appointment Record
                                        foreach ($upcoming_arr as $record):
                                            $appt_schedule = $record->get_appointmentslot()->get_appointmentschedule();
                                            $date = $appt_schedule->get_date();
                                            $time = $appt_schedule->get_time();
                                            ?>
                                            <div class="col">
                                                <div class="card shadow" style="border-radius: 10px;">
                                                    <div class="card-header">
                                                        <?php echo $record->get_appointmenttype(); // Return String           ?>
                                                    </div> <!-- CARD HEADER -->
                                                    <div class="card-body">
                                                        <!-- Add Event To Calendar (ICS FILE) -->
                                                        <?php
                                                        $date_calendar_format = Time::date_format_change($date, Time::CALENDAR_FORMAT_DEFAULT);
                                                        $calendar_event_data = $date_calendar_format . " " . $time;
                                                        ?>
                                                        <button id="<?php echo $calendar_event_data; ?>" type="submit" value="<?php echo $calendar_event_data; ?>" name="calendar-invite" href="#" class="btn btn-outline-secondary" style="float: right;" data-bs-toggle="tooltip" data-bs-placement="top" title="Add to calendar">
                                                            <i class="fas fa-calendar-alt"></i></button><br>
                                                        Appointment ID:
                                                        <?php echo $record->get_appointmentid(); // Return Appointment ID            ?>
                                                        <br>Appointment Status:
                                                        <?php echo $record->get_appointmentstatus(); // Return Appointment status           ?>
                                                        <br>
                                                        <br>Date: <?php echo Time::date_format_change($date, Time::DATE_FORMAT_APPOINTMENT); // Returns Date                                                                                                                                           ?>
                                                        <br>Time: <?php echo Time::to_12hours($time, false); // Returns Time                                                                                                                                              ?>
                                                        <br>Location: <?php echo $record->get_facility()->get_facilityname(); // Returns Date                                                                                                                                                ?>

                                                        <!-- $record->get_facility(); will return `Medical_Facility` object -->
                                                        <br>Address: <?php echo $record->get_facility()->get_address(); ?>
                                                        <br>Contact Number:
                                                        <?php echo $record->get_facility()->get_contactnumber(); ?>
                                                        <div class="row m-2 text-center">
                                                            <?php
                                                            // -- Bunch Of Appointment Info To Be Passed To Button Function -- //
                                                            $appt_info = $record->get_appointmentid() . "~" . $record->get_appointmenttype() .
                                                                    "~" . Time::date_format_change($appt_schedule->get_date(), Time::DATE_FORMAT_APPOINTMENT) .
                                                                    "~" . Time::to_12hours($appt_schedule->get_time(), false);
                                                            ?>
                                                            <div class="col">
                                                                <!-- CANCEL (INFORMATION WILL BE PASSED TO MODAL) -->
                                                                <button value="<?php echo $appt_info; ?>" type="button" class="btn btn-danger col-12" data-bs-toggle="modal" data-bs-target="#cancelappt"
                                                                        onclick="upcoming_cancel_appt(this.value)">Cancel</button>
                                                            </div> <!-- BUTTON CANCEL COLUMN -->
                                                            <div class="col">
                                                                <button type="button" class="btn btn-info col-12 text-light">Reschedule</button>
                                                            </div> <!-- BUTTON RESCEHDULE COLUMN -->
                                                            <!-- Modal -->
                                                            <div class="modal hide fade" id="cancelappt" tabindex="-1" aria-labelledby="cancelappointment" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="cancelappointment">Cancellation Confirmation</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>

                                                                        <div class="modal-body" id="upcoming_cancel_modal_body">
                                                                            You are about to cancel your appointment of <br><span id="upcoming_cancel_appt_type"></span> on
                                                                            <br>Date: <span id="upcoming_cancel_appt_date"></span>
                                                                            <br>Time: <span id="upcoming_cancel_appt_time"></span>
                                                                            <br> <br> <b> Warning: Action cannot be revoked </b>
                                                                        </div>
                                                                        <div class="modal-footer">

                                                                            <button type = "button" class = "btn btn-secondary" data-bs-dismiss = "modal">Close</button>
                                                                            <button id="upcoming_cancel_btn" type = "submit" class = "btn btn-danger"  name="cancel" >Cancel</button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div> <!--MODAL END-->
                                                        </div> <!--BUTTON ROW-->
                                                    </div> <!--CARD BODY-->
                                                </div> <!--CARD-->
                                            </div> <!--COLUMN CARD-->
                                        <?php endforeach;
                                        ?>
                                    </div>
                                </div><!-- TAB-MISSED-CONTENT -->
                            <?php endif; ?>

                            <div class="tab-pane fade" id="nav-missed" role="tabpanel" aria-labelledby="nav-missed-tab">
                                <?php
                                if ($missed_arr == null):
                                    ?>
                                    <br>
                                    <p class="text-center text-muted display-6">No missed appointments </p>
                                </div>
                                <?php
                            else:
                                ?>
                                <!-- Nav tabs -->
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 g-4">
                                    <?php
                                    // For Each Upcoming Appointment Record
                                    foreach ($missed_arr as $record):
                                        ?>
                                        <div class="col">
                                            <div class="card shadow" style="border-radius: 10px;">
                                                <div class="card-header">
                                                    <?php echo $record->get_appointmenttype(); // Return String                    ?>
                                                </div> <!-- CARD HEADER -->
                                                <div class="card-body">
                                                    Appointment ID:
                                                    <?php echo $record->get_appointmentid(); // Return Appointment ID                    ?>
                                                    <br>Appointment Status:
                                                    <?php echo $record->get_appointmentstatus(); // Return Appointment status                   ?>
                                                    <br>
                                                    <?php $appt_schedule = $record->get_appointmentslot()->get_appointmentschedule(); ?>
                                                    <br>Date: <?php echo Time::date_format_change($appt_schedule->get_date(), Time::DATE_FORMAT_APPOINTMENT); // Returns Date                                                                                                                                        ?>
                                                    <br>Time: <?php echo Time::to_12hours($appt_schedule->get_time(), false); // Returns Time                                                                                                                                              ?>
                                                    <br>Location: <?php echo $record->get_facility()->get_facilityname(); // Returns Date                                                                                                                                               ?>

                                                    <!-- $record->get_facility(); will return `Medical_Facility` object -->
                                                    <br>Address: <?php echo $record->get_facility()->get_address(); ?>
                                                    <br>Contact Number:
                                                    <?php echo $record->get_facility()->get_contactnumber(); ?>
                                                    <div class="row m-2 text-center">
                                                        <?php
                                                        // -- Bunch Of Appointment Info To Be Passed To Button Function -- //

                                                        $appt_info = $record->get_appointmentid() . "~" . $record->get_appointmenttype() .
                                                                "~" . Time::date_format_change($appt_schedule->get_date(), Time::DATE_FORMAT_APPOINTMENT) .
                                                                "~" . Time::to_12hours($appt_schedule->get_time(), false);
                                                        ?>
                                                        <div class="col">
                                                            <button type="button" class="btn btn-info col-12 text-light">Reschedule</button>
                                                        </div> <!-- BUTTON RESCEHDULE COLUMN -->
                                                    </div> <!-- BUTTON ROW-->
                                                </div> <!-- CARD BODY -->
                                            </div> <!-- CARD -->
                                        </div> <!-- COLUMN CARD -->
                                    <?php endforeach; ?>
                                </div>
                            </div> <!--MT-4 -->
                        </form> <!-- Form For "Cancel" & "Reschedule" -->
                    <?php endif; ?>
                </div> <!-- CONTAINER -->

                <?php
            else:
                header("Location:" . LOGIN_WEB);
            endif;
        else: header("Location:" . LOGIN_WEB);
        endif;
        ?>
        <script>
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous">
        </script>

        <!-- Option 2: Separate Popper and Bootstrap JS -->

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
                integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"
                integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous">
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                var url = window.location;
                $('ul.nav a[href="' + url + '"]').parent().addClass('active');
                $('ul.nav a').filter(function () {
                    return this.href == url;
                }).parent().addClass('active');
            });
        </script>
    </body>

</html>
