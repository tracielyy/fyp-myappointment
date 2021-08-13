<?php
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once EMAIL_MOD . '/Email.php';
require_once UTIL_MOD . '/StringUtils.php';
require_once UTIL_MOD . '/Regex.php';
require_once AUTH_MOD . '/Authentication.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once TIME_MOD . '/Time.php';

/*
 *  MEDICAL DASHBOARD
 */

if (!isset($_SESSION['user'])):
    header("Location:./../"); # -- REDIRECT USER TO THE INDEX PAGE
else:
    $user = unserialize((string) $_SESSION["user"]);
    $user_type = $user->get_usertype();
    $user_email = $user->get_email();
    $facilityids = $user->get_facilityids();

    if (!User_Type::check_user_type(User_Type::MEDICAL_PERSONNEL, $user_type)):
        header("Location:./../"); # -- REDIRECT USER TO THE INDEX PAGE
    else:
        ?>
        <!DOCTYPE html>
        <html lang="en">

            <head>
                <!-- Prevent Form Resubmission -->
                <script>
                    if (window.history.replaceState) {
                        window.history.replaceState(null, null, window.location.href);
                    }
                </script>

                <!-- Styling -->
                <link rel="stylesheet" href="./../css/medicaldashboard.css">

                <?php
                include TEMPLATES_PATH . '/bootstrap.php';
                include_once TEMPLATES_PATH . '/navbar-loggedin.php';
                ?>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Medical Dashboard</title>
                <!-- font awesome cdn -->
                <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
                <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                <script>
                    function startTime() {
                        const today = new Date();
                        let h = today.getHours();
                        let m = today.getMinutes();
                        let s = today.getSeconds();
                        m = checkTime(m);
                        s = checkTime(s);
                        document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
                        setTimeout(startTime, 1000);
                    }

                    function checkTime(i) {
                        if (i < 10) {
                            i = "0" + i;
                        }
                        // add zero in front of numbers < 10
                        return i;
                    }
                </script>

                <!-- CHART JS -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.min.js"
                        integrity="sha512-VCHVc5miKoln972iJPvkQrUYYq7XpxXzvqNfiul1H4aZDwGBGC0lq373KNleaB2LpnC2a/iNfE5zoRYmB4TRDQ=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>


                <!-- DATATABLE JS -->

                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
                <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js">
                </script>


                <!-- DATATABLE JS RESPONSIVE-->
                <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">
                <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

            </head>

            <body onload="startTime()">

                <?php
                // Variables 
                $dateTomorrow = date('Y-m-d', strtotime("+1 day"));
                $error = "";
                $success = "";
                $startDate = $dateTomorrow;
                $endDate = $dateTomorrow;
                $startTime = "";
                $endTime = "";
                $startMeridiem = "";
                $endMeridiem = "";
                $facilityidpicked = "";
                $interval = "";

                if ($_SERVER['REQUEST_METHOD'] == 'POST'):

                    // ____ Creation of slots with date & time array _____
                    function create_slots(string $doctor_email, string $facilityid, array $dateArr, array $timeArr): void {

                        # Get Doctor ID
                        $doctor_doc_id = Account_User::retrieve_user_doc_id($doctor_email);

                        foreach ($dateArr as $date) {

                            foreach ($timeArr as $time) {

                                // insert slots per time given
                                Special_Slot::create_slot($doctor_doc_id, $facilityid, $date, $time);
                            }
                        }
                    }

                    //echo 'going thru post';

                    if (isset($_POST['submittimeslots'])) :
                        //Date
                        $startDate = $_POST['startDate'];
                        $endDate = $_POST['endDate'];

                        // TIme
                        $startTime = $_POST['startTime'];
                        $startMeridiem = $_POST['startMeridiem'];
                        $endTime = $_POST['endTime'];
                        $endMeridiem = $_POST['endMeridiem'];

                        //Interval
                        $interval = $_POST['intervals'];
                        $facilityidpicked = $_POST['facilitypick'];

                        if ($startDate > $endDate) :
                            $error .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                            $error .= '<strong>Your Start date must be earlier than the End date!</strong> Slots have not been submitted.';
                            $error .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                            echo '<script> $(document).ready(function () {$("div.container#alertbox").prepend(\'' . $error . '\');});</script>';
                        else :

                            if ($startTime == 'Start Time' || $endTime == 'End Time') :
                                $error .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                                $error .= '<strong>You have entered incorrect time range!</strong> Slots have not been submitted.';
                                $error .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                echo '<script> $(document).ready(function () {$("div.container#alertbox").prepend(\'' . $error . '\');});</script>';
                            else :

                                if ($interval == 'Intervals') :
                                    $error .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                                    $error .= '<strong>You have not selected the intervals!</strong> Slots have not been submitted.';
                                    $error .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                    echo '<script> $(document).ready(function () {$("div.container#alertbox").prepend(\'' . $error . '\');});</script>';
                                else :

                                    if ($facilityidpicked == 'Select Facility') :
                                        $error .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                                        $error .= '<strong>You have not selected any Facility!</strong> Slots have not been submitted.';
                                        $error .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                        echo '<script> $(document).ready(function () {$("div.container#alertbox").prepend(\'' . $error . '\');});</script>';
                                    else :
                                        //Date
                                        $startDate_converted = Time::date_format_default($startDate);
                                        $endDate_converted = Time::date_format_default($endDate);
                                        $dateArray = array();

                                        //Time
                                        $startTimeMeridiem = $startTime . ":00 " . $startMeridiem;
                                        $startTime_converted = Time::to_24hours($startTimeMeridiem);

                                        $endTimeMeridiem = $endTime . ":00 " . $endMeridiem;
                                        $endTime_converted = Time::to_24hours($endTimeMeridiem);
                                        $timeArray = array();

                                        if ($startDate_converted == $endDate_converted) :
                                            $dateArray = array($startDate_converted);
                                        else :
                                            $dateArray = Time::get_date_from_range($startDate_converted, $endDate_converted);
                                        endif;

                                        if ($startTime_converted == $endTime_converted) :
                                            $timeArray = array($startTime_converted);
                                        else :
                                            $timeArray = Time::get_time_range_intervals($startTime_converted, $endTime_converted, $interval);
                                        endif;

                                        ///Tracie TODO function (array are timeArray and dateArray)
                                        $doc_email = $user->get_email();
                                        create_slots($doc_email, $facilityidpicked, $dateArray, $timeArray);

                                        $success .= '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                                        $success .= '<strong>Slots are successfuly added!</strong>';
                                        $success .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                        echo '<script> $(document).ready(function () {$("div.container#alertbox").prepend(\'' . $success . '\');});</script>';

                                        // Reset
                                        $error = "";
                                        $success = "";
                                        $startDate = $dateTomorrow;
                                        $endDate = $dateTomorrow;
                                        $startTime = "";
                                        $endTime = "";
                                        $startMeridiem = "";
                                        $endMeridiem = "";
                                        $facilityidpicked = "";
                                        $interval = "";
                                    endif;
                                endif;
                            endif;
                        endif;
                    endif;

                    // if (isset($_POST['medrec_submit'])):
                    //     
                    ?> <script>//console.log("going isset ")</script><?php
                //     $practitioneridpost =$_POST['prac_form'];
                //     $slotidpost = $_POST['slot_form'];
                //     $facilityidpost = $_POST['facility_form'];
                //     $patientidpost = $_POST['patient_form'];
                //     $medicalrecord_array = array(
                //         'facilityid'=> $facilityidpost,
                //         'practitioner' => $practitioneridpost,
                //         'slotid' => $slotidpost,
                //         'appointmenttype' => Appointment_Record::retrieve_appointmenttype($patientidpost,$slotidpost)
                //     );
                //     $mrid = check_mrid_exist($patientidpost,$slotidpost); //checks MRID but also, if available will put the mrid here
                //     if(!$mrid):
                //         $medical_record = Medical_Record::create_medical_record($patientidpost,$medicalrecord_array);
                //         $mrid = $medical_record->get_medicalrecordid();
                //         Appointment_Record::set_mrid($patientidpost,$slotidpost,$mrid);
                //     endif;
                //     header("Location:" . DOC_WEB . "/patientvisit/index.php?id=".$mrid."&pt=".$patientidpost);
                // endif;

                endif;

                //include COMPONENTS_PATH . '/navbar-loggedin.php';
                $data = "";
                $dateToday = Time::get_current_date(); //Date today NEED TO CHANGE ONLY FOR DEBUG
                $dateToday_calendar = Time::date_format_change($dateToday, Time::CALENDAR_FORMAT_DEFAULT);
                $dates = Time::get_date_from_range($dateToday, Time::get_enddate($dateToday, 6));

                $patient_per_day = Normal_Slot::patient_count_per_date("mf001", $dateToday);
                $patient_per_day += Special_Slot::patient_count_per_date("mf001", $dates[0]);

                //$slot_arr += Special_Slot::retrieve_booked_slots_by_date($user_email,$dates[2]);
                $slot_arr = array();
                $numofPatientsWeek = array();
                foreach ($dates as $date):
                    $slot_addition = Special_Slot::retrieve_booked_slots_by_date($user_email, $date);
                    $numofPatientsWeek[$date] = count($slot_addition);
                    $slot_arr = array_merge($slot_arr, $slot_addition);
                endforeach;

                $userEmail = $user->get_email();
                $practitionerid = Account_User::retrieve_user_doc_id($userEmail);
                foreach ($slot_arr as $slot):
                    $patientid = $slot->get_patient();
                    $patient = Patient::retrieve_patient_by_id($patientid);
                    $slotid = $slot->get_slotid();
                    $facilityid = $slot->get_facilityid();
                    $facilityy = Medical_Facility::retrieve_facility_by_id($facilityid);
                    $facilityname = $facilityy->get_facilityname();
                    //$mrid ='<button onclick="check("'.$patientid.'","'.$slotid.'","'.$practitionerid.'","'.$facilityid.')" class="btn btn-primary">Go to Medical Record</button>';
                    $mrid = '<button id="#listbuttons" data-name="' . $patient->get_firstname() . ' ' . $patient->get_lastname() . '" data-time="' . $slot->get_appointmentschedule()->get_time() . '" data-date="' . $slot->get_appointmentschedule()->get_date() . '" data-facilityname="' . $facilityname . '" data-patient="' . $patientid . '" data-slot="' . $slotid . '" data-prac="' . $practitionerid . '" data-facility="' . $facilityid . '" class="btn btn-primary listbttns"><i class="far fa-clipboard"></i> Copy to search</button>';
                    $data .= "{'name':'" . $patient->get_firstname() . " " . $patient->get_lastname() . "','date':'" . $slot->get_appointmentschedule()->get_date() . "','time':'" . $slot->get_appointmentschedule()->get_time() . "','mrid':'" . $mrid . "','facilityname':'" . $facilityname . "'},";
                endforeach;
                $data = "[" . $data . "]";
                ?>
                <script>
                    var apptlist = <?php echo $data ?>;
                </script>

                <?php ?>

                <div class="row bg-light py-4">

                    <div class="col">
                        <div class="container-fluid ms-3">
                            <h1 class="display-6"><?php echo $user->get_fullname(); ?> </h1>
                            <h1 class="lead"><?php echo $user->get_specialisation(); ?> </h1>
                        </div>

                    </div>

                </div>



                <!-- SIDE NAVIGATION TAB -->
                <div class="container-fluid mt-3">
                    <ul class="nav nav-tabs flex-column tabgroup" id="tabsID">
                        <li><a class="tablinks top active" href="#dashboard" data-toggle='tab' id="default">
                                <i class="far fa-window-maximize tab-icon"></i>Dashboard</a>
                        </li>

                        <li><a class="tablinks" href="#appointments" data-toggle='tab' id="appts">
                                <i class="far fa-calendar-alt tab-icon"></i>Appointments</a>
                        </li>

                        <!-- SHIFT SETTINGS: ONLY SHOWS ON SPECIALIZED DOCTORS -->
                        <?php if (!($user->get_specialisation() == 'General')): ?>

                            <li><a class="tablinks" href="#settings" data-toggle='tab'>
                                    <i class="far fa-clock tab-icon"></i>Shift Settings</a>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <div class="tab-content">
                        <div id="dashboard" class="tab-pane shadow rounded active">
                            <div class="container chart-container mt-5">

                                <h3 class="text-center mb-4">Dashboard</h3>
                                <div class="row">

                                    <div class="col">
                                        <h3 class="text-center">Date Today:</h3>
                                        <div class="border mb-3 shadow-sm" style="border-radius: 15px">
                                            <div class="display-6 text-center mb-1"><?php echo $dateToday ?></div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h3 class="text-center">Time:</h3>
                                        <div class="border mb-3 shadow-sm" style="border-radius: 15px">
                                            <div id="time" class="display-6 text-center mb-1"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="p-4 shadow" style="border-radius: 25px">
                                            <canvas id="chart2" height="250px"></canvas>

                                        </div>
                                        <small class="text-muted mt-1" style="float:right">Last updated at
                                            <?php
                                            echo date("d/m/y");
                                            echo " " . date("H:i:s");
                                            ?></small>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col">
                                        <table style="width: 100% !important" id="apptdashboard" class="display">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Facility</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>name</td>
                                                    <td>date</td>
                                                    <td>time</td>
                                                    <td>facilityname</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <small class="text-muted" style="float:right">Last updated at
                                            <?php
                                            echo date("d/m/y");
                                            echo " " . date("H:i:s");
                                            ?></small>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div id="appointments" class="tab-pane shadow rounded">
                            <div class="container mt-5">
                                <h3 class="text-center">Appointments</h3>

                                <table style="width: 100% !important" id="apptontab" class="display">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Facility</th>
                                            <th>Medical Record</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>name</td>
                                            <td>date</td>
                                            <td>time</td>
                                            <td>facilityname</td>
                                            <td>mrid</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="row mt-4">
                                    <p class="lead">Search Medical Record</p>
                                    <div class="col">
                                        <input id="namequery" class="form-control" placeholder="Name" readonly></input>
                                    </div>
                                    <div class="col">
                                        <input id="datequery" class="form-control" placeholder="Date" readonly></input>
                                    </div>
                                    <div class="col">
                                        <input id="timequery" class="form-control" placeholder="Time" readonly></input>
                                    </div>
                                    <div class="col">
                                        <input id="facility" class="form-control" placeholder="Facility" readonly></input>
                                    </div>
                                    <div class="col">


                                        <!-- Medical Record function -->
                                        <form id="medrecordpost" method="post" action="patientvisits/func/check.php">
                                            <input class="form-control" type="hidden" name="prac_form" id="practinput" value="" >
                                            <input class="form-control" type="hidden" name="slot_form" id="slotinput" value="" >
                                            <input class="form-control" type="hidden" name="facility_form" id="facilityinput" value="" >
                                            <input class="form-control" type="hidden" name="patient_form" id="patientinput" value="" >
                                            <button class="btn btn-primary" type="submit" name="medrec_submit" id="mdsubmit" value="go">Go to Medical Record</Button>
                                        </form>
                                    </div>
                                </div>



                            </div>



                        </div>

                        <!-- SHIFT SETTINGS: ONLY SHOWS ON SPECIALIZED DOCTORS -->
                        <?php if (!($user->get_specialisation() == 'General')): ?>

                            <div id="settings" class="tab-pane shadow rounded">
                                <div class="container mt-5">
                                    <h3 class="text-center mb-5">Shift</h3>
                                    <div class="container">

                                        <div class="row">
                                            <div class="col">

                                                <table style="width: 100% !important" id="shifttable" class="display">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Time</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>date</td>
                                                            <td>time</td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        <h1 class="display-6 mt-4"><strong style="margin-bottom:5px">Add Shift</strong></h1>
                                        <div class="container mt-3" id="alertbox"></div>
                                    </div>
                                    <form name="shiftpostname" method="post" action="">
                                        <div class="row my-3">
                                            <div class="col">
                                                <p class="lead" style="margin-bottom:5px">Start Date</p>
                                                <input id="startDateID" min="<?php echo $dateToday_calendar; ?>" type="date"
                                                       class="form-control" name="startDate" value="<?php echo $startDate; ?>">
                                            </div>
                                            <div class="col">
                                                <p class="lead" style="margin-bottom:5px">End Date</p>
                                                <input min="<?php echo $dateToday_calendar; ?>" type="date" class="form-control"
                                                       name="endDate" value="<?php echo $endDate; ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col">
                                                        <!-- Reason why use select is because only per hour, date input has minutes which we dont want -->
                                                        <div class="row">
                                                            <div class="col">
                                                                <select name="startTime" class="form-select"
                                                                        aria-label="Default select example">
                                                                    <option selected hidden>Start Time</option>
                                                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                                                        <option value="<?php echo $i; ?>"
                                                                        <?php
                                                                        if ($startTime == $i):
                                                                            echo 'selected';
                                                                        endif;
                                                                        ?>><?php echo $i; ?>
                                                                        </option>
                                                                    <?php endfor; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col">
                                                                <select name="startMeridiem" class="form-select"
                                                                        aria-label="Default">
                                                                    <option value="AM" selected <?php
                                                                    if ($startMeridiem == "AM"): echo 'selected';
                                                                    endif;
                                                                    ?>>AM</option>
                                                                    <option value="PM" <?php
                                                                    if ($startMeridiem == "PM"): echo 'selected';
                                                                    endif;
                                                                    ?>>PM</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">

                                                <div class="row">
                                                    <div class="col">
                                                        <select name="endTime" class="form-select" aria-label="Default">
                                                            <option selected hidden>End Time</option>
                                                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                                                <option value="<?php echo $i; ?>"
                                                                <?php
                                                                if ($endTime == $i):
                                                                    echo 'selected';
                                                                endif;
                                                                ?>><?php echo $i; ?>
                                                                </option>
                                                            <?php endfor; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <select name="endMeridiem" class="form-select"
                                                                aria-label="Default select">
                                                            <option value="AM" selected <?php
                                                            if ($endMeridiem == "AM"): echo 'selected';
                                                            endif;
                                                            ?>>AM</option>
                                                            <option value="PM" <?php
                                                            if ($endMeridiem == "PM"): echo 'selected';
                                                            endif;
                                                            ?>>PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col">
                                                <select name="intervals" class="form-select" aria-label="Default select">
                                                    <option value="Intervals" hidden selected>Intervals</option>
                                                    <option value="30" 
                                                    <?php
                                                    if ($interval == "30"):
                                                        echo 'selected';
                                                    endif;
                                                    ?>>30 minutes</option>
                                                    <option value="60"
                                                    <?php
                                                    if ($interval == "60"):
                                                        echo 'selected';
                                                    endif;
                                                    ?>>1 hour</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <select name="facilitypick" class="form-select" aria-label="Default select">
                                                    <option value="Select Facility" hidden>Select Facility</option>
                                                    <?php
                                                    $facilityobject = array();
                                                    foreach ($facilityids as $id) {
                                                        $obj = Medical_Facility::retrieve_facility_by_id($id);
                                                        $facilityobject[] = $obj;
                                                    }
                                                    /* display */
                                                    foreach ($facilityobject as $facility) :
                                                        ?>
                                                        <option value="<?php echo $facility->get_facilityid(); ?>" <?php
                                                        if ($facilityidpicked == $facility->get_facilityid()):
                                                            echo 'selected';
                                                        endif;
                                                        ?>><?php echo $facility->get_facilityname(); ?>
                                                        </option>;
                                                        <?php
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <button name="submittimeslots" type="submit" class="btn btn-primary text-center mt-3"
                                                style="float:right">
                                            Submit Time Slots
                                        </button>
                                    </form>
                                    <small class="text-muted">*Notice: Start date has to be the next day, as patient can book
                                        appointment starting from tomorrow.</small>
                                </div>
                            </div>

                        <?php endif; ?>
                    </div>
                </div>



            </div><!-- END OF SIDE NAVIGATION TAB -->



            <script>
                function strip_string(str) {
                    return str.replace(/(\r\n|\n|\r)/gm, "");
                }


                $(document).on('click', 'button.listbttns', function (e) {
                    var patient_id = $(this).attr("data-patient");
                    var slot_id = $(this).attr("data-slot");
                    var practitioner_id = $(this).attr("data-prac");
                    var facility_id = $(this).attr("data-facility");

                    var name = $(this).attr("data-name");
                    var date = $(this).attr("data-date");
                    var time = $(this).attr("data-time");
                    var facilityname = $(this).attr("data-facilityname");

                    console.log(patient_id);
                    console.log(slot_id);
                    console.log(practitioner_id);
                    console.log(facility_id);

                    $('input#practinput').val(practitioner_id);
                    $('input#slotinput').val(slot_id);
                    $('input#facilityinput').val(facility_id);
                    $('input#patientinput').val(patient_id);

                    $('input#namequery').val(name);
                    $('input#datequery').val(date);
                    $('input#timequery').val(time);
                    $('input#facility').val(facilityname);

                    $('#mdsubmit').prop('disabled', false);

                });


                /*THIS ONE ON THE BOTTOM IS ON THE DASHBOARD!!!!! 
                 */

                var ctx2 = document.getElementById("chart2").getContext("2d");
                var myChart1 = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: [<?php
                foreach ($dates as $date):
                    echo "'" . $date . "',";
                endforeach;
                ?>],
                        datasets: [{
                                label: 'Num of patients',
                                data: [<?php
                foreach ($numofPatientsWeek as $date => $count): echo $count . ",";
                endforeach;
                ?>],
                                backgroundColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)'
                                ],
                                borderWidth: 1
                            }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 10,
                                min: 0,
                                ticks: {
                                    stepSize: 1
                                }

                            }
                        }
                    }
                });
            </script>

            <!-- END OF CHARTJS -->

            <!-- GRIDJS -->

            <script type="text/javascript">
                // function Patient(name, date, time, mrid) {
                //     this.name = name;
                //     this.date = date;
                //     this.time = time;
                //     this.mrid = mrid;
                // };
                console.log(apptlist);

                $(document).ready(function () {
        <?php $shift = '[]' ?>
                    var shiftdata = <?php echo $shift ?>;
                    var shifttables = $('#shifttable').DataTable({
                        responsive: true,
                        pageLength: 3,
                        "lengthChange": false,
                        data: shiftdata,
                        columns: [{
                                data: 'date'
                            },
                            {
                                data: 'time'
                            }
                        ],
                        "language": {
                            "emptyTable": "No slots opened"
                        }
                    });

                });


                $(document).ready(function () {

                    $('#mdsubmit').prop('disabled', true);
                    var appttable = $('#apptdashboard').DataTable({
                        responsive: true,
                        pageLength: 3,
                        "lengthChange": false,
                        "order": [
                            [1, "asc"],
                            [2, "asc"]
                        ],
                        columnDefs: [{
                                width: '20%',
                                targets: 3
                            }],
                        data: apptlist,
                        columns: [{
                                data: 'name'
                            },
                            {
                                data: 'date'
                            },
                            {
                                data: 'time'
                            },
                            {
                                data: 'facilityname'
                            }
                        ],
                        "language": {
                            "emptyTable": "No appointment booked for you at the moment"
                        }
                    });

                });


                $(document).ready(function () {
                    var appttable = $('#apptontab').DataTable({
                        responsive: true,
                        pageLength: 6,
                        "lengthChange": false,
                        "order": [
                            [1, "asc"],
                            [2, "asc"]
                        ],
                        data: apptlist,
                        columns: [{
                                data: 'name'
                            },
                            {
                                data: 'date'
                            },
                            {
                                data: 'time'
                            },
                            {
                                data: 'facilityname'
                            },
                            {
                                data: 'mrid'
                            }
                        ],
                        "language": {
                            "emptyTable": "No appointment booked for you at the moment"
                        }
                    });

                });


                /*===========================
                 Dynamic Tabs
                 =============================*/

                $(window).on("popstate", function () {
                    var scrollHeight = $(document).scrollTop();
                    var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
                    $("a[href='" + anchor + "']").tab("show");
                    console.log("a[href='" + anchor + "']");
                    setTimeout(function () {
                        $(window).scrollTop(scrollHeight);
                    }, 5);
                });

                $(document).ready(function () {

                    if (location.hash) {
                        $("a[href='" + location.hash + "']").tab("show");
                    }
                    $(document.body).on("click", "a[data-toggle='tab']", function (ev) {
                        if (ev.target.hash == '#appointments') {
                            appttable.columns.adjust().draw();
                        }
                        var scrollHeight = $(document).scrollTop();
                        location.hash = this.getAttribute("href");
                        setTimeout(function () {
                            $(window).scrollTop(scrollHeight);
                        }, 5);
                        console.log("going thru false");
                    });

                });
            </script>
        </body>

        </html>
    <?php
    endif; # -- END OF USER TYPE CHECK

endif; # -- END OF SESSION CHECK
?>
