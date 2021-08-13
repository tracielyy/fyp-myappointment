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

                <!-- GRID JS -->
                <!-- <script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>
                <link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" /> -->

                <!-- DATATABLE JS -->

                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
                <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js">
                </script>
                <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">
                <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
                <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script> -->

                <!-- DATATABLE JS RESPONSIVE-->
                <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">
                <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

            </head>

            <body onload="startTime()">

                <?php
                $error = "";
                $success = "";
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

                    if (isset($_POST['submittimeslots'])) {
                        //Date
                        $startDate = $_POST['startDate'];
                        $endDate = $_POST['endDate'];

                        if ($startDate > $endDate) {
                            $error .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                            $error .= '<strong>Your Start date must be earlier than the End date!</strong> Slots have not been submitted.';
                            $error .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                            echo '<script> $(document).ready(function () {$("div.container#alertbox").prepend(\'' . $error . '\');});</script>';
                        } else {

                            if ($_POST['startTime'] == 'Start Time' || $_POST['endTime'] == 'End Time') {
                                $error .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                                $error .= '<strong>You have entered incorrect time range!</strong> Slots have not been submitted.';
                                $error .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                echo '<script> $(document).ready(function () {$("div.container#alertbox").prepend(\'' . $error . '\');});</script>';
                            } else {

                                if ($_POST['intervals'] == 'Intervals') {
                                    $error .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                                    $error .= '<strong>You have not selected the intervals!</strong> Slots have not been submitted.';
                                    $error .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                    echo '<script> $(document).ready(function () {$("div.container#alertbox").prepend(\'' . $error . '\');});</script>';
                                } else {

                                    if ($_POST['facilitypick'] == 'Select Facility') {
                                        $error .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                                        $error .= '<strong>You have not selected any Facility!</strong> Slots have not been submitted.';
                                        $error .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                        echo '<script> $(document).ready(function () {$("div.container#alertbox").prepend(\'' . $error . '\');});</script>';
                                    }else 
                                    {   
                                        //Date
                                        $startDate_converted = Time::date_format_default($startDate);
                                        $endDate_converted = Time::date_format_default($endDate);
                                        $dateArray = array();

                                        //Time
                                        $startTime = $_POST['startTime'];
                                        $startMeridiem = $_POST['startMeridiem'];
                                        $startTimeMeridiem = $startTime . ":00 " . $startMeridiem;
                                        $startTime_converted = Time::to_24hours($startTimeMeridiem);

                                        $endTime = $_POST['endTime'];
                                        $endMeridiem = $_POST['endMeridiem'];
                                        $endTimeMeridiem = $endTime . ":00 " . $endMeridiem;
                                        $endTime_converted = Time::to_24hours($endTimeMeridiem);
                                        $timeArray = array();

                                        //Interval
                                        $interval = $_POST['intervals'];

                                        if ($startDate_converted == $endDate_converted) {
                                            $dateArray = array($startDate_converted);
                                        } else {
                                            $dateArray = Time::get_date_from_range($startTime_converted, $endDate_converted);
                                        }


                                        if ($startTime_converted == $endTime_converted) {
                                            $timeArray = array($startTime_converted);
                                        } else {
                                            $timeArray = Time::get_time_range_intervals($startTime_converted, $endTime_converted, $interval);
                                        }

                                        ///Tracie TODO function (array are timeArray and dateArray)
                                        //$facilityid = $user->get_facility()->get_facilityid(); Not used
                                        $facilityidpicked = $_POST['facilitypick'];
                                        $doc_email = $user->get_email();
                                        create_slots($doc_email, $facilityidpicked, $dateArray, $timeArray);

                                        $success .= '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                                        $success .= '<strong>Slots are successfuly added!</strong>';
                                        $success .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                        echo '<script> $(document).ready(function () {$("div.container#alertbox").prepend(\'' . $success . '\');});</script>';
                                    }
                                    
                                }
                            }
                        }
                    }




                    if (isset($_POST['postmedical'])):
                        ?> <script>
                                            console.log("going isset");
                        </script><?php
                $patientid = $_POST['patientid'];
                $slotid = $_POST['slotid'];
                $practitionerid = $_POST['practitionerid'];
                $facilityid = $_POST['facilityid'];

                $medicalrecord_array = array(
                    'facilityid' => $facilityid,
                    'practitioner' => $practitionerid,
                    'slotid' => $slotid,
                    'appointmenttype' => Appointment_Record::retrieve_appointmenttype($patientid, $slotid)
                );

                $mrid = check_mrid_exist($patientid, $slotid); //checks MRID but also, if available will put the mrid here

                if (!$mrid):
                    $medical_record = Medical_Record::create_medical_record($patientid, $medicalrecord_array);
                    $mrid = $medical_record->get_medicalrecordid();
                    Appointment_Record::set_mrid($patientid, $slotid, $mrid);
                endif;

                header("Location:" . DOC_WEB . "/patientvisit/index.php?id=" . $mrid . "&pt=" . $patientid);
            endif;

        endif;

        //include COMPONENTS_PATH . '/navbar-loggedin.php';
        $data = "";
        $dateToday = Time::get_current_date(); //Date today NEED TO CHANGE ONLY FOR DEBUG
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

        // echo "<pre>";
        // echo var_dump($slot_arr);
        // echo "</pre>";
        //can use get_date_from_range -- make it to 7 days
        //echo json_encode($slot_arr);
        $userEmail = $user->get_email();
        $practitionerid = Account_User::retrieve_user_doc_id($userEmail);
        foreach ($slot_arr as $slot):
            $patientid = $slot->get_patient();
            $patient = Patient::retrieve_patient_by_id($patientid);
            $slotid = $slot->get_slotid();
            $facilityid = $slot->get_facilityid();
            //$mrid ='<button onclick="check("'.$patientid.'","'.$slotid.'","'.$practitionerid.'","'.$facilityid.')" class="btn btn-primary">Go to Medical Record</button>';
            $mrid = '<button id="#listbuttons" data-patient="' . $patientid . '" data-slot="' . $slotid . '" data-prac="' . $practitionerid . '" data-facility="' . $facilityid . '" class="btn btn-primary listbttns">Go to Medical Record</button>';
            $data = "{'name':'" . $patient->get_firstname() . "','date':'" . $slot->get_appointmentschedule()->get_date() . "','time':'" . $slot->get_appointmentschedule()->get_time() . "','mrid':'" . $mrid . "'},";
        endforeach;
        $data = "[" . $data . "]";
        //echo $data ;
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
                                                    <th>Medical Record</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>name</td>
                                                    <td>date</td>
                                                    <td>time</td>
                                                    <td>mrid</td>
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
                                            <th>Medical Record</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>name</td>
                                            <td>date</td>
                                            <td>time</td>
                                            <td>mrid</td>
                                        </tr>
                                    </tbody>
                                </table>
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
            <?php $dateTomorrow = date('Y-m-d', strtotime("+1 day")); ?>
                                        <h1 class="display-6 mt-4"><strong style="margin-bottom:5px">Add Shift</strong></h1>
                                        <div class="container mt-3" id="alertbox"></div>
                                    </div>
                                    <form name="shiftpostname" method="post" action="">
                                        <div class="row my-3">
                                            <div class="col">
                                                <p class="lead" style="margin-bottom:5px">Start Date</p>
                                                <input id="startDateID" min="<?php echo $dateTomorrow; ?>" type="date"
                                                       class="form-control" name="startDate" value="<?php echo $dateTomorrow; ?>">
                                            </div>
                                            <div class="col">
                                                <p class="lead" style="margin-bottom:5px">End Date</p>
                                                <input min="<?php echo $dateTomorrow; ?>" type="date" class="form-control"
                                                       name="endDate" value="<?php echo $dateTomorrow; ?>">
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
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                    <option value="4">4</option>
                                                                    <option value="5">5</option>
                                                                    <option value="6">6</option>
                                                                    <option value="7">7</option>
                                                                    <option value="8">8</option>
                                                                    <option value="9">9</option>
                                                                    <option value="10">10</option>
                                                                    <option value="11">11</option>
                                                                    <option value="12">12</option>
                                                                </select>
                                                            </div>
                                                            <div class="col">
                                                                <select name="startMeridiem" class="form-select"
                                                                        aria-label="Default">
                                                                    <option selected>AM</option>
                                                                    <option>PM</option>
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
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>
                                                            <option value="7">7</option>
                                                            <option value="8">8</option>
                                                            <option value="9">9</option>
                                                            <option value="10">10</option>
                                                            <option value="11">11</option>
                                                            <option value="12">12</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <select name="endMeridiem" class="form-select"
                                                                aria-label="Default select">
                                                            <option>AM</option>
                                                            <option selected>PM</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col">
                                                <select name="intervals" class="form-select" aria-label="Default select">
                                                    <option value="Intervals" hidden selected>Intervals</option>
                                                    <option value="30">30 minutes</option>
                                                    <option value="60">1 hour</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <select name="facilitypick" class="form-select" aria-label="Default select">
                                                <option value="Select Facility" hidden>Select Facility</option>
                                                   <?php
                                                   $facilityids = $user->get_facilityids();
                                                   $facilityobject = array();
                                                   foreach($facilityids as $id){
                                                     $obj = Medical_Facility::retrieve_facility_by_id($id);
                                                     $facilityobject[] =  $obj;
                                                    
                                                   }
                                                   /* display */
                                                   foreach($facilityobject as $facility){
                                                    
                                                    echo '<option value="'.$facility->get_facilityid().'">'.$facility->get_facilityname().'</option>';
                                                   }
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

            <!-- Medical Record function -->
            <form id="mrpost" method="post" name="postmedical" action="patientvisits/func/check.php">
                <input type="hidden" name="practitionerid" id="practinput" value="">
                <input type="hidden" name="slotid" id="slotinput" value="">
                <input type="hidden" name="facilityid" id="facilityinput" value="">
                <input type="hidden" name="patientid" id="patientinput" value="">
                <button type="submit" name="medrecordsubmit" id="mdrecordbttn" value=""></button>
            </form>

            <script>
                function strip_string(str) {
                    return str.replace(/(\r\n|\n|\r)/gm, "");
                }


                $(document).on('click', 'button.listbttns', function (e) {
                    var patient_id = $(this).attr("data-patient");
                    var slot_id = $(this).attr("data-slot");
                    var practitioner_id = $(this).attr("data-prac");
                    var facility_id = $(this).attr("data-facility");

                    console.log(patient_id);
                    console.log(slot_id);
                    console.log(practitioner_id);
                    console.log(facility_id);

                    $('input#practinput').val(practitioner_id);
                    $('input#slotinput').val(slot_id);
                    $('input#facilityinput').val(facility_id);
                    $('input#patientinput').val(patient_id);

                    $('input#mdrecordbttn').click();

                    // $.ajax({
                    //     type: "POST",
                    //     url: "<?php //echo htmlspecialchars($_SERVER['PHP_SELF']);          ?>",
                    //     dataType: "text",
                    //     data: {
                    //         'ajax_check_mrid': true,
                    //         'patientid': patient_id,
                    //         'slotid': slot_id,
                    //         'facilityid': facility_id,
                    //         'practitionerid': practitioner_id
                    //     },
                    //     success: function() {
                    //         console.log(patient_id);
                    //         console.log(slot_id);
                    //     },
                    //     error: function() {
                    //         console.log("smthg wrong");
                    //     }
                    // });

                    // $.ajax({
                    //     type: "POST",
                    //     url: "<?php //echo htmlspecialchars($_SERVER['PHP_SELF']);          ?>",
                    //     dataType: "text",
                    //     data: {

                    //     },
                    //     success: function() {
                    //         console.log("just posting");
                    //     },
                    //     error: function() {
                    //         console.log("smthg wrong");
                    //     }
                    // });

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
                                data: 'mrid'
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
                        pageLength: 12,
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
                                data: 'mrid'
                            }
                        ],
                        "language": {
                            "emptyTable": "No appointment booked for you at the moment"
                        }
                    });

                });


                // $(document).ready(function() {
                //     new gridjs.Grid({
                //         columns: ["Name", "Email", "Date", "Time"],
                //         data: [
                //             <?php
//     $pnum = 0;
//     $p_perday = 3; //Special_Slot::patient_count_per_date("mf001", "21-07-2021");
//     foreach ($slot_arr as $slot):
//         $patientid = $slot->get_patient();
//         $patient = Patient::retrieve_patient_by_id($patientid);
//         $str = "[\"" . $patient->get_firstname() . "\",\"" . $patient->get_email() . "\",\"" . $slot->get_appointmentschedule()->get_date() . "\",\"" . $slot->get_appointmentschedule()->get_time() . "\"],";
//         echo $str;
//     endforeach;
//     
        ?>
                //         ],

                //         pagination: {
                //             enabled: true,
                //             limit: 3,
                //             summary: false
                //         }
                //     }).render(document.getElementById("wrapper2"));

                //     new gridjs.Grid({
                //         columns: ["Name", "Email", "Date", "Time",{
                //     name: "Medical Records",
                //     formatter: (cell) => {
                //         return gridjs.html(`<a href="./patientvisits/index.php?id=035f05cbf562436d8f30&pt=S1499902G">Go to Medical Record</a>`)
                //         // return `Update button`
                //     }
                // }],
                //         data: [
                //             ["John", "john@example.com", "24-June-2021", "14:00"],
                //             ["Mark", "mark@gmail.com", "24-June-2021", "14:00"],
                //             ["Eoin", "eoin@gmail.com", "24-June-2021", "14:00"],
                //             ["Sarah", "sarahcdd@gmail.com", "24-June-2021", "14:00"],
                //             ["Afshin", "afshin@mail.com", "24-June-2021", "14:00"],
                //             ["John", "john@example.com", "24-June-2021", "14:00"],
                //             ["Mark", "mark@gmail.com", "24-June-2021", "14:00"],
                //             ["Eoin", "eoin@gmail.com", "24-June-2021", "14:00"],
                //             ["Sarah", "sarahcdd@gmail.com", "24-June-2021", "14:00"],
                //             ["John", "john@example.com", "24-June-2021", "14:00"],
                //             ["Mark", "mark@gmail.com", "24-June-2021", "14:00"],
                //             ["Eoin", "eoin@gmail.com", "24-June-2021", "14:00"],
                //             ["Sarah", "sarahcdd@gmail.com", "24-June-2021", "14:00"],
                //         ],

                //         pagination: {
                //             enabled: true,
                //             limit: 6,
                //             summary: false
                //         },

                //         sort: true,
                //         search: true,
                //     }).render(document.getElementById("wrapper"));

                // });



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
