<?php
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

if (!isset($_SESSION['user'])):
    header("Location:./debuglogin.php"); # -- REDIRECT USER TO THE LOGIN PAGE
else:
    $user = unserialize($_SESSION["user"]);

    if ($user->get_usertype() == User_Type::PATIENT):

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

        # -- Get The Next Day -- #
        $cal_default = Time::CALENDAR_FORMAT_DEFAULT;
        $next_day = Time::get_enddate(date($cal_default), 1, $cal_default);

        # -- Allow Date Selection For 6 Mths (est. 180 days) -- #
        $future_days = 180;
        $max_date = Time::get_enddate($next_day, $future_days, $cal_default);

        # -- Check For Some On Change Event -- #
        $selected_date = Time::date_format_default($next_day);
        $appt_date = $next_day;

        $appt_info = array(
            "facilityid" => "",
            "appointmenttype" => "",
            "date" => "",
            "slotid" => "",
            "specialist" => ""
        );
        $appt_record = "";
        /* Load Appointment Slots */
        if ($_SERVER["REQUEST_METHOD"] == "POST"):

            // -- User Click On BOOK APPOINTMENT
            if (isset($_POST['book_appt'])):
                /* ---------  FUNCTIONS FOR CREATING APPOINTMENT ---------  */

                // -- DISPLAY AVAILABLE SLOTS ($doctor_email is optional -- Only when user select specialist)
                function retrieve_slots(string $facilityid, string $appointmenttype, string $date, ?string $doctor_email = NULL): array {
                    switch ($appointmenttype):
                        case Appointment_Type::CHECK_UP:
                        case Appointment_Type::DOCTOR_CONSULTATION:
                            return Normal_Slot::retrieve_free_slots_by_date($facilityid, $appointmenttype, $date);
                        case Appointment_Type::SPECIALIST_CONSULTATION:
                            if ($doctor_email != NULL):
                                return Special_Slot::retrieve_free_slots_by_date($facilityid, $doctor_email, $date);
                        endif;
                    endswitch;
                }

                // -- BOOK AN APPOINTMENT  (Put This Function In The Create Appointment Page)
                function book_appointment(string $patient_email, array $booking_info): bool|Appointment_Record {

                    $patient_doc_id = Account_User::retrieve_user_doc_id($patient_email);

                    # Validate Appointment
                    $valid = Appointment_Record::validate_appt_booking($patient_doc_id, $booking_info);
                    if ($valid):

                        # Create User Appointment Record
                        $appt_record = Appointment_Record::create_appointment_record($patient_doc_id, $booking_info);

                        # Update To Add Patient's ID To Appointment's patient array
                        add_to_slot($patient_doc_id, $booking_info);

                        return $appt_record;

                    endif;
                    return false; # -- Same Day Booking
                }

                // --Call Appropriate Method For Different Appointment Type
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

                // -- Loop Info To Array
                foreach ($_POST as $key => $value):
                    if (isset($appt_info[$key])):
                        $appt_info[$key] = htmlspecialchars($value);
                        $valid_arr[$key] = False; // Set All Field Validation Check As False
                        // -- Valid If It Is Not Empty
                        if (!empty($appt_info[$key])):
                            $valid_arr[$key] = True;
                        endif;

                    endif;
                endforeach; # -- END LOOPING INFO TO ARRAY


                if (Appointment_Type::validate_type($appt_info['appointmenttype'])):
                    $valid_arr['appointmenttype'] = True;
                else:
                    $valid_arr['appointmenttype'] = False;
                endif; # -- END VALIDATE APPOINTMENT TYPE
                // -- Remove The Array Key For Specialist If It Is Not Specialist
                if ($appt_info['appointmenttype'] !== Appointment_Type::SPECIALIST_CONSULTATION):
                    unset($appt_info['specialist']);
                    unset($valid_arr['specialist']);
                endif;

                $appt_info['date'] = Time::date_format_default($appt_info['date']);

                if (!in_array(False, $valid_arr)) :
                    $appt_record = book_appointment($user->get_email(), $appt_info);

                    # ___ Success Booking ____ #
                    if ($appt_record !== false):
                        EmailTemplate::template_bookappointment($user->get_email(), $appt_record);
                        header("Location:" . APPT_WEB);
                    endif;
                endif; # -- END VALIDATION

            endif; # -- END BOOK APPOINTMENT TRIGGER

        endif; # -- END POST REQUEST
        ?>
        <!DOCTYPE html>
        <html>

            <head>
                <!-- Title -->
                <title>FYP-21-S2-24: Create Appointment</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <!-- Styling -->
                <?php include TEMPLATES_PATH . '/bootstrap.php'; ?>
                <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css'>
                <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js'></script>


                <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
                        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
                </script>


                <!-- Latest compiled and minified CSS -->
                <link rel="stylesheet"
                      href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

                <!--Latest compiled and minified JavaScript-->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

                <script src="./../js/calendar.js"></script>

                <link rel="stylesheet" href="./../css/createappointment.css">
                <script>
                            var spinnerhtml =
                                    '<div id="spinner" class="d-flex justify-content-center pt-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>';

                            var xhr = null;

                            function set_slotid(slotid) {
                                $('#hide_slotid').val(slotid);
                                console.log(slotid);
                            }

                            function set_specialist_id(specialist_id) {
                                $('#hide_specialist').val(specialist_id);
                                $(".next").show();
                                console.log(specialist_id);
                            }

                            function dateChange(date) {
                                if (xhr) {
                                    xhr.abort();
                                    console.log("ajax aborted");
                                }
                                ;

                                //                var ipt = input.split(",");
                                var facilityid = $('#hide_facilityid').val();
                                var appointmenttype = $('#hide_appointmenttype').val();
                                var specialist = $('#hide_specialist').val();
                                // Set Date
                                $('#hide_date').val(date);
                                $('#display_slots').empty();
                                $('#spinner').remove();
                                $('#spinner_display').append(spinnerhtml);

                                console.log("js triggered");
                                xhr = $.ajax({
                                    type: "POST",
                                    url: "func/loadslots.php",
                                    data: {
                                        ajax: 1,
                                        set_location: 1,
                                        set_facilityid: facilityid,
                                        set_appointmenttype: appointmenttype,
                                        set_date: date,
                                        set_specialist: specialist
                                    },
                                    success: function (data) {
                                        //                        $('#hide_form').submit();
                                        console.log("post submit");
                                        console.log("Changed date: " + date);
                                        var slot_arr = null;
                                        try {
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

                                                    $('#' + sid).attr('onclick', 'set_slotid()');
                                                    console.log(sid);
                                                    $('#spinner').remove();
                                                    $('#display_slots').append(btn);
                                                }
                                            }
                                        } catch (e) {
                                            // forget about it :)
                                            console.log("empty");
                                            $('#display_slots').append("<div>Invalid Input</div>");
                                        }

                                    },
                                    error: function () {
                                        console.log("Error Date Change");
                                    }
                                });
                            }

                            // Functions By Nanta (To Save Each Step's Information)
                            function set_appt_fields() {
                                facilityid = $('#hide_facilityid').attr('value');
                                appointmenttype = $('#hide_appointmenttype').attr('value');

                                console.log(facilityid);
                                console.log(appointmenttype);

                            }

                            // BOOK APPOINTMENT ON CLICK TRIGGER
                            function book_appt() {

                                $('#hide_form').submit();
                            }

                            // -- AID TO LOAD INITIAL DEFAULT DATE (NEXT DAY)
                            function load_date(date) {
                                $('#hide_date').val(date);
                                console.log("setting date: " + date);
                            }
                </script>

                <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/wrick17/calendar-plugin@master/style.css">
            </head>

            <body onload="load_date('<?php echo $selected_date; ?>')">

                <!-- One "tab" for each step in the form: -->

                <!-- Navigation -->
                <?php
                include TEMPLATES_PATH . '/navbar-loggedin.php';
                ?>


                <!-- HIDDEN FIELDS For Appointment Form Submission -->
                <form id="hide_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <input type="hidden" name="book_appt" id="book_appt" />
                    <input type="hidden" name="facilityid" id="hide_facilityid" value="" />
                    <input type="hidden" name="appointmenttype" id="hide_appointmenttype" />
                    <input type="hidden" name="date" id="hide_date" />
                    <input type="hidden" name="specialist" id="hide_specialist" /> <!-- Doctor's Email -->
                    <input type="hidden" name="slotid" id="hide_slotid" />
                </form>
                <!--HIDDEN FIELDS END-->
                <!-- Same Day Booking -->
                <div class="justify-content-center" style="min-width:720px!important;" id="appt-error">
                    <div class="alert alert-danger" role="alert">
                        Same Day Booking Of Same Appointment Type Is Not Allowed. <br/>
                        Proceed to book a different appointment instead.</a>
                    </div>
                </div>
                <!-- END OF SAME DAY BOOKING -->

                <div class="container mt-5 d-flex justify-content-center" style="min-width:720px!important" id="appt-booking">

                    <div class="col-11 col-offset-2">
                        <div class="display-6">Book an Appointment</div>

                        <div class="progress mt-3" style="height: 30px;">
                            <div class="progress-bar" style="font-weight:bold; font-size:15px;" role="progressbar" aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div><!-- END OF PROGRESS BAR -->
                        <form id="apptform" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <div class="card mt-3">
                                <!--STEP 1 -->
                                <div class="step">
                                    <div class="text-center">
                                        <h5 class="card-title font-weight-bold pb-2 mt-4">Book for appointment at location: </h5>
                                    </div>

                                    <div class="card-body p-4">
                                        <div class="radio-group row justify-content-between px-3 text-center"
                                             style="justify-content:center !important">
                                                 <?php
                                                 // -- Loop Each Facilities In Database
                                                 foreach ($facilities as $facility):
                                                     ?>
                                                <div id="<?php echo $facility->get_facilityid(); ?>"
                                                     class="col-auto me-sm-2 mx-1 card-block py-0 text-center radio radio-facilityid">
                                                    <div class="opt-icon">
                                                        <img src="<?php echo $facility_icons[$facility->get_facilityid()]; ?>"
                                                             alt="<No Image Available>"
                                                             onerror="this.onerror=null;this.src='../img/example_img.jpg';"
                                                             class="img-fluid" width="80" height="100" />
                                                    </div>
                                                    <p><b><?php echo $facility->get_facilityname(); ?></b></p>
                                                </div>
                                                <?php
                                            endforeach;
                                            ?>
                                        </div>
                                    </div>

                                </div>
                                <!--END OF STEP 1 -->


                                <!--STEP 2 -->
                                <div class="step" style="display: none">
                                    <div class="text-center">
                                        <h5 class="card-title font-weight-bold pb-2 mt-3">Book appointment for</h5>
                                    </div>

                                    <div class="card-body p-4">
                                        <div class="radio-group row justify-content-between px-3 text-center"
                                             style="justify-content:center !important">

                                            <div id="cp" onclick="hideSpecialist();"
                                                 class="col-auto me-sm-2 mx-1 card-block py-0 text-center radio radio-appointmenttype">
                                                <div class="opt-icon"><i class="fas fa-clinic-medical" style="font-size: 80px;"></i>
                                                </div>
                                                <p><b>Check-up</b></p>
                                            </div>

                                            <div id="dc" onclick="hideSpecialist();"
                                                 class="col-auto me-sm-2 mx-1 card-block py-0 text-center radio radio-appointmenttype">
                                                <div class="opt-icon"><i class="fas fa-stethoscope" style="font-size: 80px;"></i>
                                                </div>
                                                <p><b>Doctor Consultation</b></p>
                                            </div>

                                            <div id="sc" onclick="hideSpecialist();"
                                                 class="col-auto ms-sm-2 mx-1 card-block py-0 text-center radio radio-appointmenttype">
                                                <div class="opt-icon"><i class="fas fa-user-md" style="font-size: 80px;"></i></div>
                                                <p><b>Specialist Consultation</b></p>
                                            </div>
                                        </div>
                                        <div id="spec_display">
                                            <select id="specSelection" class="form-control" data-live-search="true"
                                                    title="Select Specialist">
                                            </select>
                                            <div id="display_personnel"></div>
                                        </div>
                                    </div>
                                </div>
                                <!--END OF STEP 2 -->

                                <!--STEP 3 -->
                                <div id="userinfo" class="step" style="display: none">
                                    <div class="text-center">
                                        <h5 class="card-title font-weight-bold pb-2 mt-3">Time Slots</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <!--Appointment Date Selection-->
                                        <div class="form-group row">
                                            <div class="calendar-wrapper"></div>
                                        </div>

                                        <div id="spinner_display">
                                        </div>

                                        <!--List Of Available Appointments (Slots Will Be Updated Via jQuery) -->
                                        <div class="list-group mt-3" id="display_slots"></div>
                                    </div>
                                </div><!-- END OF STEP 3 -->


                                <div class="card-footer">
                                    <button type="button" class="action back btn btn-sm btn-outline-warning"
                                            style="display: none">Back</button>
                                    <button onclick="set_appt_fields()" type="button"
                                            class="action next btn btn-sm btn-outline-secondary float-end" disabled="">Next</button>
                                    <button onclick="book_appt()" type="button"
                                            class="action submit btn btn-sm btn-outline-success float-end" style="display: none">
                                        Book Now
                                    </button>
                                </div> <!-- END OF CARD FOOTER -->
                            </div><!-- END OF APPT CARD IN FORM -->

                        </form>
                    </div>
                </div><!-- END OF OUTER CONTAINER -->
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
                        dateChange(dayToQuery);
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
                </script>

                <script>
                    $('#appt-error').hide();
                    /*
                     |-----------------|
                     |  DYNAMIC BOX    |
                     |-----------------|
                     */

                    //Jquery to search the names of specialist //need to be changed
                    $('#txt-search').keyup(function () {
                        $('.next').prop('disabled', true);
                        var searchField = $(this).val();
                        if (searchField === '') {
                            $('#filter-records').html('');
                            return;
                        }
                        var regex = new RegExp(searchField, "i");
                        var output = '';
                        $.each(data, function (key, val) {
                            var fullname = val.fname + ' ' + val.lname;
                            if ((fullname.search(regex) !== -1)) {
                                output += '<li id="' + val.id + '" class="li-search">' + val.fname + ' ' + val.lname +
                                        '</li>';
                            }
                        });
                        $('#filter-records').html(output);
                    });

                    //Jqery search name of specialist
                    $(document).on("click", ".li-search", function () {
                        $("#txt-search").val($(this).html());
                        setFormFields($(this).attr("id"));
                        $("#filter-records").html("");
                        $(".next").prop("disabled", false);
                    });

                    var spec = null;
                    var selectPickerLoaded = false;

                    //when clicking the facilities
                    $(".radio-group .radio-facilityid").on("click", function () {
                        $('#appt-error').hide();

                        // Remove Any Previous Inputs
                        $(".selected .fa").removeClass("fa-check");
                        $(".radio").removeClass("selected");

                        // Add The Triggering "Radio" With "selected" class
                        $(this).addClass("selected");

                        if ($(this).hasClass("selected") === true) {
                            hideSpecialist();
                            spec = null;
                            selectPickerLoaded = false;
                            $(".next").prop("disabled", false);
                            $('#hide_facilityid').val($(this).attr("id"));
                            console.log($(this).attr("id") + " is selected");
                        }

                    });



                    //when clicking the appointment type


                    $(".radio-group .radio-appointmenttype").on("click", function () {

                        // Remove Any Previous Inputs
                        $(".selected .fa").removeClass("fa-check");
                        $(".radio").removeClass("selected");

                        // Add The Triggering "Radio" With "selected" class
                        $(this).addClass("selected");
                        $('#specSelection').selectpicker('destroy'); // destroy to reset for next selection

                        if (spec) {
                            spec.abort();
                        }

                        // APPOINTMENT TYPE
                        if ($("#cp").hasClass("selected") === true) {
                            if (selectPickerLoaded)
                                $('#specSelection').selectpicker('hide');
                            $(".next").prop("disabled", false);
                            set_specialist_id("");
                            $('#hide_appointmenttype').val("<?php echo Appointment_Type::CHECK_UP; ?>");
                        } else if ($("#dc").hasClass("selected") === true) {
                            if (selectPickerLoaded)
                                $('#specSelection').selectpicker('hide');
                            $(".next").prop("disabled", false);
                            set_specialist_id("");
                            $('#hide_appointmenttype').val("<?php echo Appointment_Type::DOCTOR_CONSULTATION; ?>");
                        } else if ($("#sc").hasClass("selected") === true) {
                            $(".next").prop("disabled", true);
                            $('#display_personnel').children().delay(700).slideDown(100);
                            $('#hide_appointmenttype').val("<?php echo Appointment_Type::SPECIALIST_CONSULTATION; ?>");

                            spec = $.ajax({
                                type: "POST",
                                url: "func/loadspecialist.php",
                                data: {
                                    load_specialist: true,
                                    facilityid: $('#hide_facilityid').val()
                                },
                                success: function (data) {

                                    var personnel_arr = null;
                                    try {
                                        var personnel_arr = JSON.parse(data);
                                        //console.log(Object.keys(personnel_arr).length);
                                        if (Object.keys(personnel_arr).length === 0) {
                                            $('#display_personnel').html("");
                                            $('#display_personnel').append("<div>No Personnel</div>");
                                        } else {
                                            // -- Looping Each Specialisation Category (Alphabetical Order NOT IMPLEMENTED)
                                            //console.log("1");
                                            //console.log(personnel_arr);
                                            $('#specSelection').empty();
                                            $.each(personnel_arr, function (key) {
                                                console.log(key);
                                                var spec_selection =
                                                        `<option data-token=${key} id=${key}>${key}</option>`; // Outer Layer -- nanta to change
                                                $('#specSelection').append(spec_selection);
                                                $(function () {
                                                    $('#specSelection').selectpicker();
                                                    $('#specSelection').selectpicker('show');
                                                    selectPickerLoaded = true;
                                                });

                                                console.log("Retreiving data to dropdown list");
                                            });
                                        }
                                    } catch (e) {
                                        // forget about it :)
                                        console.log(e);
                                        $('#display_slots').append("<div>Invalid Input</div>");
                                    }

                                },
                                error: function () {
                                    console.log("Error Specialist Change");
                                }
                            });

                        } else {

                        }

                    });

                    function hideSpecialist() {
                        console.log("hidespecialist");
                        $('#display_personnel').html("");
                        $('#specSelection').hide();
                        $('#specSelection').selectpicker('destroy'); // destroy to reset for next selection
                        //                    $('#display_personnel').children().hide();
                        if (selectPickerLoaded)
                            $('#specSelection').selectpicker('hide');
                    }


                    function turnOnNext() {
                        $(".next").prop("disabled", false);
                    }

                    $('#specSelection').change(function () {
                        $(".next").prop("disabled", true);

                        // You can access the value of your select field using the .val() method
                        var UserChosen = $('#specSelection').val();

                        var specAjax = null;
                        $("#display_personnel").slideUp(100, function () {
                            $("#display_personnel").empty();
                            specAjax = $.ajax({
                                type: "POST",
                                url: "func/loadspecialist.php",
                                data: {
                                    load_specialist: true,
                                    facilityid: $('#hide_facilityid').val()
                                },
                                success: function (data) {
                                    var personnel_arr = null;

                                    try {
                                        var personnel_arr = JSON.parse(data);
                                        //console.log(Object.keys(personnel_arr).length);
                                        if (Object.keys(personnel_arr).length === 0) {
                                            $('#display_personnel').append("<div>No Personnel</div>");
                                        } else {
                                            var specObj = personnel_arr[UserChosen];
                                            console.log(specObj);

                                            $.each(specObj, function (i, spc) {
                                                var personnel_name = spc.firstname;
                                                var doc_id = spc.email;
                                                var doc_btn = `<button onclick="set_specialist_id(this.id); turnOnNext();"  type="button" class="list-group-item list-group-item-action timebtn" id="${doc_id}" name="slotid" value="${doc_id}" 
                                        aria-current="true">${personnel_name}</button>`;
                                                console.log(personnel_name);
                                                console.log(doc_id);
                                                console.log(spc.specialization);
                                                $('#display_personnel').append(doc_btn);
                                            });

                                            $("#display_personnel").slideDown(100);

                                        }
                                    } catch (e) {
                                        // forget about it :)
                                        console.log(e);
                                        $('display_personnel').append("<div>Invalid Input</div>");
                                    }

                                },
                                error: function () {
                                    console.log("Error Specialist Change");
                                }
                            });

                        });


                    });

                    /*
                     |-------------------------------|
                     |  TRAVERSAL SCRIPT TO PAGES    |
                     |-------------------------------|
                     */
                    var step = 1;
                    $(document).ready(function () {
                        stepProgress(step);
                    });


                    $(".next").on("click", function () {
                        var nextstep = false;
                        if (step === 3) {
                            nextstep = checkForm("userinfo");
                        } else {
                            nextstep = true;
                        }
                        if (nextstep === true) {
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
                    $(".back").on("click", function () {
                        if (step > 1) {
                            step = step - 2;
                            $(".next").trigger("click");

                            // Set Facility ID
                            var facilityid = $('#hide_facilityid').val();
                            $("#" + facilityid).addClass("selected");

                            // Set Appointment Type
                            var appointmenttype = $('#hide_appointmenttype').val();
                            $("#" + appointmenttype).addClass("selected");

                        }
                        hideButtons(step);
                    });

                    // CALCULATE PROGRESS BAR
                    stepProgress = function (currstep) {
                        var percent = parseFloat(100 / $(".step").length) * currstep;
                        percent = percent.toFixed();
                        $(".progress-bar")
                                .css("width", percent + "%")
                                .html(currstep + " of 3");
                    };


                    // DISPLAY AND HIDE "NEXT", "BACK" AND "SUMBIT" BUTTONS
                    hideButtons = function (step) {
                        var limit = parseInt($(".step").length);
                        $(".action").hide();
                        if (step < limit) {
                            $(".next").show();
                        }
                        if (step > 1) {
                            $(".back").show();
                        }
                        // At The Last Step [LOAD DATA BEFORE CHANGING DATE]
                        if (step === limit) {
                            $('#spinner_display').append(spinnerhtml);
                            $(".next").hide();
                            $(".submit").show();

                            var facilityid = $('#hide_facilityid').val();
                            var appointmenttype = $('#hide_appointmenttype').val();
                            var date = $('#hide_date').val();
                            var specialist = $('#hide_specialist').val();
                            $('#display_slots').empty();
                            $.ajax({
                                type: "POST",
                                url: "func/loadslots.php",
                                data: {
                                    ajax: 1,
                                    set_facilityid: facilityid,
                                    set_appointmenttype: appointmenttype,
                                    set_date: date,
                                    set_specialist: specialist
                                },
                                success: function (data) {
                                    //                                            $("#apptform").submit();
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
                    };

                    function checkForm(val) {
                        // CHECK IF ALL "REQUIRED" FIELD ALL FILLED IN
                        var valid = true;
                        $("#" + val + " input:required").each(function () {
                            if ($(this).val() === "") {
                                $(this).addClass("is-invalid");
                                valid = false;
                            } else {
                                $(this).removeClass("is-invalid");
                            }
                        });
                        return valid;
                    }
                </script>


            </body>
            <?php
            if (isset($_POST['book_appt'])):

                if (!in_array(False, $valid_arr)) :

                    # ___ Success Booking ____ #
                    if ($appt_record === false):
                        ?>
                        <script>
                            $('#appt-booking').hide();
                            $('#appt-error').show();
                        </script>
                        <?php
                    endif;
                endif;
            endif; # __ when user trigger the appt booking __

        endif; # -- END CHECK FOR PATIENT
    endif; # -- END USER SESSION CHECK 
    ?>

</html>