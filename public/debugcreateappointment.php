<!DOCTYPE html>
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require_once TIME_MOD . '/Time.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once APPT_MOD . '/Appointment_Record.php';
require_once ENUMS_PATH . '/User_Type.php';
require_once ENUMS_PATH . '/Appointment_Type.php';
?>

<html>

    <head>
        <!-- Title -->
        <title>FYP-21-S2-24</title>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Styling -->
        <?php include TEMPLATES_PATH . '/bootstrap.php'; ?>
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
        <script>
            function dateChange(input, date) {

                var ipt = input.id.split(",");
                var facilityid = ipt[0];
                var appointmenttype = ipt[1];

                // Set Date
                $('#hide_date').val(date);
                console.log("js triggered");

                $.ajax({
                    type: "POST",
                    url: "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>",
                    data: {
                        ajax: 1,
                        set_location: 1,
                        set_appointmenttype: 1,
                        facilityid: facilityid,
                        appointmenttype: appointmenttype,
                        date: date
                    },
                    success: function () {
                        $('#hide_form').submit();
                        console.log("post submit");
                        console.log(appointmenttype);
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

        </script>
    </head>

    <body>

        <!-- One "tab" for each step in the form: -->



        <!-- Navigation -->
        <?php
        $user = unserialize($_SESSION["user"]);
        include TEMPLATES_PATH . '/navbar-loggedin.php';
        // -- Used to store correct data
        $appointmentArr = array(
            'facilityid' => '',
            'appointmenttype' => '',
            'date' => '',
            'slotid' => ''
        );
        ?>


        <!-- HIDDEN FIELDS For Appointment Form Submission -->
        <form id="hide_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" id="hide_facilityid"/>
            <input type="hidden" id="hide_appointmenttype"/>
            <input type="hidden" id="hide_date"/>
            <?php
            #-- Get The Next Day -- #
            $cal_default = Time::CALENDAR_FORMAT_DEFAULT;
            $next_day = Time::get_enddate(date($cal_default), 1, $cal_default);

            # -- Allow Date Selection For 6 Mths (est. 180 days) -- #
            $future_days = 180;
            $max_date = Time::get_enddate($next_day, $future_days, $cal_default);

            # -- Check For Some On Change Event -- #
            $selected_date = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST"):
                if (isset($_POST['ajax'])):
                    echo "date called";
                    $selected_date = Time::date_format_default($_POST['date']);
                    $appt_date = Time::date_format_change($selected_date, $cal_default);
                    $slots = PatientFunctions::get_apptslots($appointmentArr['facilityid'], $appointmentArr['appointmenttype'], $selected_date);
                else:
                    $selected_date = Time::date_format_default($next_day);
                    $appt_date = $next_day;
                endif;
            else:
                echo "post not called";
                $selected_date = Time::date_format_default($next_day);
                $appt_date = $next_day;
            endif; # -- END POST REQUEST
            ?>
        </form>
        <!-- HIDDEN FIELDS END -->



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
                                         class="col-auto me-sm-2 mx-1 card-block py-0 text-center radio radio-facilityid">
                                        <div class="opt-icon"><img src="cgh.png" class="img-fluid" width="100"
                                                                   height="100">
                                        </div>
                                        <p><b>Changi General Hospital</b></p>
                                    </div>

                                    <div id="mf001"
                                         class="col-auto me-sm-2 mx-1 card-block py-0 text-center radio radio-facilityid">
                                        <div class="opt-icon"><img src="nuh.png" class="img-fluid" width="100"
                                                                   height="100"></div>
                                        <p><b>National University Hospital</b></p>
                                    </div>

                                    <div id="mf002"
                                         class="col-auto me-sm-2 mx-1 card-block py-0 text-center radio radio-facilityid">
                                        <div class="opt-icon"><img src="tts.png" class="img-fluid" width="75"
                                                                   height="50">
                                        </div>
                                        <p><b>Tan Tock Seng Hospital</b></p>
                                    </div>

                                </div>
                            </div>
                            <!--                            <div class="card-footer">
                                                            <button type="button" class="action next btn btn-sm btn-outline-secondary float-end"
                                                                    onclick="submitLoc()" disabled="">Next</button>
                                                        </div>-->
                        </div> <!-- END OF STEP 1 -->


                        <!-- STEP 2 -->
                        <div class="step" style="display: none">
                            <div class="text-center">
                                <h5 class="card-title font-weight-bold pb-2 mt-3">Book appointment for</h5>
                            </div>

                            <div class="card-body p-4">
                                <div class="radio-group row justify-content-between px-3 text-center"
                                     style="justify-content:center !important">

                                    <div id="cp" class="col-auto me-sm-2 mx-1 card-block py-0 text-center radio radio-appointmenttype">
                                        <div class="opt-icon"><i class="fas fa-clinic-medical" style="font-size: 80px;"></i>
                                        </div>
                                        <p><b>Check-up</b></p>
                                    </div>

                                    <div id="dc" class="col-auto me-sm-2 mx-1 card-block py-0 text-center radio radio-appointmenttype">
                                        <div class="opt-icon"><i class="fas fa-stethoscope" style="font-size: 80px;"></i>
                                        </div>
                                        <p><b>Doctor Consultation</b></p>
                                    </div>

                                    <div id="sc" class="col-auto ms-sm-2 mx-1 card-block py-0 text-center radio radio-appointmenttype">
                                        <div class="opt-icon"><i class="fas fa-user-md" style="font-size: 80px;"></i></div>
                                        <p><b>Specialist Consultation</b></p>
                                    </div>

                                </div>


                                <div class="searchfield input-group px-5">
                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search text-white"
                                                                                        aria-hidden="true"></i></span>
                                    <input id="txt-search" class="form-control" type="text" placeholder="Search"
                                           aria-label="Search">
                                </div>
                                <div id="filter-records" class="mx-5"></div>
                            </div>
                            <!--                            <div class="card-footer">
                                                            <button type="button" class="action back btn btn-sm btn-outline-warning"
                                                                    style="display: none">Back</button>
                                                            <button type="button" class="action next btn btn-sm btn-outline-secondary float-end"
                                                                    onclick="submitAT()" disabled="">Next</button>
                                                        </div>-->
                        </div> <!-- END OF STEP 2 -->

                        <!-- STEP 3 -->
                        <div id="userinfo" class="step" style="display: none">
                            <div class="text-center">
                                <h5 class="card-title font-weight-bold pb-2 mt-3">Time Slots</h5>
                            </div>
                            <div class="card-body p-4">





                                <!-- Appointment Date Selection -->
                                <div class="form-group row">
                                    <input type="date" class="form-control"
                                           id="<?php echo "mf001" . "," . "Doctor Consultation"; ?>"
                                           name="date" value="<?php echo $appt_date; ?>" min="<?php echo $next_day; ?>"
                                           max="<?php echo $max_date; ?>" >
                                </div>

                                <!-- List Of Available Appointments (HARDCODE) -->
                                <div class="list-group mt-3">
                                    <?php
                                    # -- Check & Loop All The Available Slots (Only Show Not Full Slots) -- #
                                    if (empty($slots)):
                                        echo "No Slots Available<br/>";
                                    else:
                                        foreach ($slots as $slot):
                                            $sid = $slot->get_appointmentschedule()->get_date() . "~" . $slot->get_slotid() . "~" . $slot->get_appointmentschedule()->get_time();
                                            ?>
                                            <button type="button" class="list-group-item list-group-item-action timebtn" id="<?php echo $sid; ?>" 
                                                    name="slotid" value="<?php echo $sid; ?>"
                                                    aria-current="true">
                                                        <?php echo $slot->get_slot_description(); ?>
                                            </button>
                                            <?php
                                        endforeach;
                                    endif;
                                    ?>
                                    <!--                                    <button type="button" class="list-group-item list-group-item-action timebtn">17 June
                                                                            2021 (Thu), 08:30 AM <span class="badge bg-warning rounded-pill ms-2">half
                                                                                full</span></button>
                                                                        <button type="button" class="list-group-item list-group-item-action timebtn">17 June
                                                                            2021 (Thu), 09:30 AM <span class="badge bg-success rounded-pill ms-2">mostly
                                                                                vacant</span></button>-->
                                </div>

                            </div>
                            <!--                            <div class="card-footer">
                                                            <button type="button" class="action back btn btn-sm btn-outline-warning"
                                                                    style="display: none">Back</button>
                                                            <button type="submit" name="bookappointment"
                                                                    class="action submit btn btn-sm btn-outline-success float-end"
                                                                    style="display: none">Book Now</button>
                                                        </div>-->
                        </div><!-- END OF STEP 3 -->

                    </div>
                    <div class="card-footer">
                        <button type="button" class="action back btn btn-sm btn-outline-warning" style="display: none">Back</button>
                        <button onclick="set_appt_fields()" type="button" class="action next btn btn-sm btn-outline-secondary float-end" disabled="">Next</button>
                        <button type="submit" class="action submit btn btn-sm btn-outline-success float-end"
                                style="display: none">Book
                            Now</button>
                    </div>

                </form>
            </div>

        </div>

        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js'></script>

        <script>
                            var data = [{
                                    id: "1",
                                    fname: "Tiger",
                                    lname: "Noxx",
                                    team: 'Team 1',
                                    address: 'Ryecroft Field',
                                    tel: '0494645879'
                                },
                                {
                                    id: "2",
                                    fname: "Garrett",
                                    lname: "Pellens",
                                    team: 'Team 2',
                                    address: 'Kiln Circus',
                                    tel: '0493658746'
                                },
                                {
                                    id: "3",
                                    fname: "Ashton",
                                    lname: "Fox",
                                    team: 'Team 1',
                                    address: 'Thurne View',
                                    tel: '0498532546'
                                },
                                {
                                    id: "4",
                                    fname: "Melissa",
                                    lname: "Perenboom",
                                    team: 'Team 3',
                                    address: 'Thornton Glade',
                                    tel: '0499454891'
                                },
                                {
                                    id: "5",
                                    fname: "Frankie",
                                    lname: "Winters",
                                    team: 'Team 2',
                                    address: 'Drayton Brae',
                                    tel: '0494678943'
                                },
                                {
                                    id: "6",
                                    fname: "Benoist",
                                    lname: "Muniz",
                                    team: 'Team 4',
                                    address: 'Foxglove Lane',
                                    tel: '0492884618'
                                },
                                {
                                    id: "7",
                                    fname: "Kelly",
                                    lname: "London",
                                    team: 'Team 2',
                                    address: 'Doxford Park Way',
                                    tel: '0497978945'
                                },
                                {
                                    id: "8",
                                    fname: "Hope",
                                    lname: "Gilmore",
                                    team: 'Team 3',
                                    address: 'Bradford Manor',
                                    tel: '0499894125'
                                },
                                {
                                    id: "9",
                                    fname: "Muriel",
                                    lname: "Smith",
                                    team: 'Team 3',
                                    address: 'Wardle Street',
                                    tel: '0491484215'
                                },
                                {
                                    id: "10",
                                    fname: "Gary",
                                    lname: "Hendren",
                                    team: 'Team 4',
                                    address: 'Church Street',
                                    tel: '0493596488'
                                }
                            ];

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

                            $(document).on("click", ".li-search", function () {
                                $("#txt-search").val($(this).html());
                                setFormFields($(this).attr("id"));
                                $("#filter-records").html("");
                                $(".next").prop("disabled", false);
                            });


                            // CALLING RADIO GROUP
                            $(".radio-group .radio").on("click", function () {

                                // Remove Any Previous Inputs
                                $(".selected .fa").removeClass("fa-check");
                                $(".radio").removeClass("selected");

                                // Add The Triggering "Radio" With "selected" class
                                $(this).addClass("selected");

                                //  FACILITY ID
                                if ($("#mf001").hasClass("selected") === true) {
                                    $(".next").prop("disabled", false);
                                    $('#hide_facilityid').val("mf001");
                                } else if ($("#mf002").hasClass("selected") === true) {
                                    $(".next").prop("disabled", false);
                                    $('#hide_facilityid').val("mf002");
                                } else if ($("#mf003").hasClass("selected") === true) {
                                    $(".next").prop("disabled", false);
                                    $('#hide_facilityid').val("mf003");
                                }


                                // APPOINTMENT TYPE
                                if ($("#cp").hasClass("selected") === true) {
                                    $(".next").prop("disabled", false);
                                    $('#hide_appointmenttype').val("<?php echo Appointment_Type::CHECK_UP; ?>");
                                } else if ($("#dc").hasClass("selected") === true) {
                                    $(".next").prop("disabled", false);
                                    $('#hide_appointmenttype').val("<?php echo Appointment_Type::DOCTOR_CONSULTATION; ?>");
                                } else if ($("#sc").hasClass("selected") === true) {
                                    $(".next").prop("disabled", false);
                                    $('#hide_appointmenttype').val("<?php echo Appointment_Type::SPECIALIST_CONSULTATION; ?>");
                                }
                            });

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
                                // At The Last Step
                                if (step === limit) {
                                    $(".next").hide();
                                    $(".submit").show();

                                    // -- Load Appointment
                                    var facilityid = $('#hide_facilityid').val();
                                    var appointmenttype = $('#hide_appointmenttype').val();
                                    var date = $('#hide_date').val();
                                    $.ajax({
                                        type: "POST",
                                        url: "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>",
                                        data: {
                                            set_facilityid: facilityid,
                                            set_appointmenttype: appointmenttype
                                        },
                                        success: function () {
//                                            $("#apptform").submit();
                                            console.log("Load Step 1 & 2");

                                        },
                                        error: function () {
                                            console.log("Error Date Change");
                                        }
                                    });
                                }
                            };
                            function setFormFields(id) {
                                if (id !== false) {
                                    // FILL STEP 2 FORM FIELDS
                                    d = data.find(x => x.id === id);
                                    $('#fname').val(d.fname);
                                    $('#lname').val(d.lname);
                                    $('#team').val(d.team);
                                    $('#address').val(d.address);
                                    $('#tel').val(d.tel);
                                } else {
                                    // EMPTY USER SEARCH INPUT
                                    $("#txt-search").val('');
                                    // EMPTY STEP 2 FORM FIELDS
                                    $('#fname').val('');
                                    $('#lname').val('');
                                    $('#team').val('');
                                    $('#address').val('');
                                    $('#tel').val('');
                                }
                            }

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

</html>