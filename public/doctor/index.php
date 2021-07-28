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
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />

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
                <script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>
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
                ?>


                <div class="row bg-light py-4">
                    <div class="row bg-light">
                        <div class="col-xs-3 col-md-2 px-5 mx-md-1 mx-lg-0">
                            <img src="https://via.placeholder.com/100" class="rounded shadow float-start" alt="...">
                        </div> <!-- col -->

                        <div class="col-xs-8 col-md-6">
                            <h1 class="display-6"><?php echo $user->get_fullname(); ?> </h1>
                            <h1 class="lead"><?php echo $user->get_gender(); ?> </h1>
                        </div> <!-- col -->

                        <div class="col-md-2">
                            <!-- col -->
                            <div class="row py-1 col-lg-12 mx-auto">
                                <a href="./editprofile.php" type="button" class="btn btn-secondary float-end">Edit Profile</a>
                            </div> <!-- col -->
                        </div> <!-- col -->
                    </div>
                </div>

                <!-- SIDE NAVIGATION TAB -->
                <div class="container-fluid mt-3">
                    <div class="tab">
                        <button class="tablinks top" onclick="openTab(event, 'Dashboard')" id="defaultOpen">
                            <i class="far fa-window-maximize tab-icon"></i>Dashboard 
                        </button>
                        <button class="tablinks" onclick="openTab(event, 'Appointments')">
                            <i class="far fa-calendar-alt tab-icon"></i>Appointments
                        </button>
                        <button class="tablinks" onclick="openTab(event, 'Data')">
                            <i class="fas fa-chart-bar tab-icon"></i>Data
                        </button>
                        <button class="tablinks" onclick="openTab(event, 'Settings')">
                            <i class="far fa-clock tab-icon"></i>Shift Settings
                        </button> 
                        <button class="tablinks" onclick="openTab(event, 'Post')">
                            <i class="far fa-clipboard tab-icon"></i>Post Forum
                        </button> 
                    </div>

                    <div id="Dashboard" class="tabcontent shadow rounded">
                        <div class="container chart-container mt-5">
                            <h3 class="text-center mb-4">Dashboard</h3>
                            <div class="row">

                                <div class="border mb-3 shadow-sm" style="border-radius: 15px">
                                    <div id="time" class="display-6 text-center mb-1"></div>
                                </div>

                                <div class="col">
                                    <div class="p-4 shadow" style="border-radius: 25px">
                                        <canvas id="chart2" height="250px"></canvas>
                                    </div>
                                </div>
                                <!--        <div class="col">
                                            <div class="p-4 shadow" style="border-radius: 25px">
                                                <canvas id="chart3" height="250px"></canvas>
                                            </div>
                                        </div> -->

                            </div>
                            <div class="row mt-5">
                                <div class="col">
                                    <div id="wrapper2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="Appointments" class="tabcontent shadow rounded">
                        <div class="container mt-5">
                            <h3 class="text-center">Appointments</h3>
                            <div id="wrapper"></div>
                        </div>
                    </div>

                    <div id="Data" class="tabcontent shadow rounded">
                        <div class="container mt-5">
                            <h3 class="text-center">Data</h3>
                            <div>
                                <canvas id="myChart" width="400" height="120"></canvas>
                            </div>
                        </div>
                    </div>

                    <div id="Settings" class="tabcontent shadow rounded">
                        <div class="container mt-5">
                            <h3 class="text-center">Shift</h3>
                            <p>Shows to set the timing of work</p>
                        </div>
                    </div>

                    <div id="Post" class="tabcontent shadow rounded">
                        <div class="container mt-5">
                            <h3 class="text-center">Post Medical Information</h3>
                            <p>Shows to post</p>
                        </div>
                    </div>

                </div><!-- END OF SIDE NAVIGATION TAB -->
            </body>

            <script>
                /*THIS IS NOT ON THE DASHBOARD!!!!! 
                 */
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['hello', 'world', 'waddup', '3333', '1444'],
                        datasets: [{
                                label: 'Number of ',
                                data: [<?php //echo $numofPatients;                            ?>2, 0, 0, 0, 0, 0],
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)',
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)'
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
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                /*THIS IS NOT ON THE DASHBOARD!!!!! 
                 */



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
                                label: 'Number of Patients per date',
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

                var ctx3 = document.getElementById("chart3").getContext("2d");
                var myChart2 = new Chart(ctx3, {
                    type: 'doughnut',
                    data: {
                        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                        datasets: [{
                                label: '# of Votes',
                                data: [12, 19, 3, 5, 2, 3],
                                backgroundColor: [
                                    'rgba(255, 99, 132,1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                    },
                    options: {
                        maintainAspectRatio: false

                    }
                });
            </script>

            <!-- END OF CHARTJS -->

            <!-- GRIDJS -->


            <script>
                new gridjs.Grid({
                    columns: ["Name", "Email", "Date", "Time"],
                    data: [
        <?php
        $pnum = 0;
        $p_perday = 3; //Special_Slot::patient_count_per_date("mf001", "21-07-2021");
        foreach ($slot_arr as $slot):
            $patientid = $slot->get_patient();
            $patient = Patient::retrieve_patient_by_id($patientid);
            $str = "[\"" . $patient->get_firstname() . "\",\"" . $patient->get_email() . "\",\"" . $slot->get_appointmentschedule()->get_date() . "\",\"" . $slot->get_appointmentschedule()->get_time() . "\"],";
            echo $str;
        endforeach;
        ?>],

                    pagination: {
                        enabled: true,
                        limit: 3,
                        summary: false
                    }
                }).render(document.getElementById("wrapper2"));

                new gridjs.Grid({
                    columns: ["Name", "Email", "Date", "Time"],
                    data: [
                        ["John", "john@example.com", "24-June-2021", "14:00"],
                        ["Mark", "mark@gmail.com", "24-June-2021", "14:00"],
                        ["Eoin", "eoin@gmail.com", "24-June-2021", "14:00"],
                        ["Sarah", "sarahcdd@gmail.com", "24-June-2021", "14:00"],
                        ["Afshin", "afshin@mail.com", "24-June-2021", "14:00"],
                        ["John", "john@example.com", "24-June-2021", "14:00"],
                        ["Mark", "mark@gmail.com", "24-June-2021", "14:00"],
                        ["Eoin", "eoin@gmail.com", "24-June-2021", "14:00"],
                        ["Sarah", "sarahcdd@gmail.com", "24-June-2021", "14:00"],
                        ["John", "john@example.com", "24-June-2021", "14:00"],
                        ["Mark", "mark@gmail.com", "24-June-2021", "14:00"],
                        ["Eoin", "eoin@gmail.com", "24-June-2021", "14:00"],
                        ["Sarah", "sarahcdd@gmail.com", "24-June-2021", "14:00"]
                    ],

                    pagination: {
                        enabled: true,
                        limit: 6,
                        summary: false
                    },

                    sort: true,
                    search: true,
                }).render(document.getElementById("wrapper"));

                function openTab(evt, tabName) {
                    var i, tabcontent, tablinks;
                    tabcontent = document.getElementsByClassName("tabcontent");
                    for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }
                    tablinks = document.getElementsByClassName("tablinks");
                    for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                    }
                    document.getElementById(tabName).style.display = "block";
                    evt.currentTarget.className += " active";
                }

                // Get the element with id="defaultOpen" and click on it
                document.getElementById("defaultOpen").click();
            </script>

        </html>
    <?php
    endif; # -- END OF USER TYPE CHECK

endif; # -- END OF SESSION CHECK
?>