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
//         include COMPONENTS_PATH . '/navbar-loggedin.php';
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
                    $appointmenttype = 
                    $mrid ="<button onclick='check('".$patientid."','".$slotid."','".$practitionerid."','".$facilityid."','".$appointmenttype."')' class='btn btn-primary'>Go to Medical Record</button>";
                    $data = '{"name":"'.$patient->get_firstname().'","date":"'.$slot->get_appointmentschedule()->get_date().'","time":"'. $slot->get_appointmentschedule()->get_time() .'","mrid":"'.$mrid.'"},';
                endforeach;
                $data = "[".$data."]";
                // echo $data ;
                ?>
    <script>
    var apptlist = <?php echo $data ?>
    </script>

    <div class="row bg-light py-4">

        <div class="col">
            <div class="container-fluid ms-3">
                <h1 class="display-6"><?php echo $user->get_fullname(); ?> </h1>
                <h1 class="lead"><?php echo $user->get_gender(); ?> </h1>
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

            <li><a class="tablinks" href="#settings" data-toggle='tab'>
                    <i class="far fa-clock tab-icon"></i>Shift Settings</a>
            </li>
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
                                <?php echo date("d/m/y"); echo " ".date("H:i:s"); ?></small>
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
                                <?php echo date("d/m/y"); echo " ".date("H:i:s"); ?></small>
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

            <div id="settings" class="tab-pane shadow rounded">
                <div class="container mt-5">
                    <h3 class="text-center">Shift</h3>
                    <p>Shows to set the timing of work</p>
                </div>
            </div>
        </div>



    </div><!-- END OF SIDE NAVIGATION TAB -->

    <script>
    function strip_string(str) {
        return str.replace(/(\r\n|\n|\r)/gm, "");
    }

    function check(patientid, slotid, practitionerid, facilityid, appointmenttype) {
        $.ajax({
            type: "POST",
            url: "func/check.php",
            data: {
                ajax_check_mrid: true,
                patientid: patientid,
                slotid: slotid,
                facilityid : facilityid,
                appointmenttype: appointmenttype
            },
            success: function(mrid_status) {
                var mrid_exist = strip_string(mrid_status);
                console.log("success");
            },
            error: function() {

            }
        });
    }


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

    $(document).ready(function() {
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
            ]
        });

    });


    $(document).ready(function() {
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
            ]
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
    //     ?>
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

    $(window).on("popstate", function() {
        var scrollHeight = $(document).scrollTop();
        var anchor = location.hash || $("a[data-toggle='tab']").first().attr("href");
        $("a[href='" + anchor + "']").tab("show");
        console.log("a[href='" + anchor + "']");
        setTimeout(function() {
            $(window).scrollTop(scrollHeight);
        }, 5);
    });

    $(document).ready(function() {

        if (location.hash) {
            $("a[href='" + location.hash + "']").tab("show");
        }
        $(document.body).on("click", "a[data-toggle='tab']", function(ev) {
            if (ev.target.hash == '#appointments') {
                appttable.columns.adjust().draw();
            }
            var scrollHeight = $(document).scrollTop();
            location.hash = this.getAttribute("href");
            setTimeout(function() {
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