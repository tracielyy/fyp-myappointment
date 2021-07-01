<!DOCTYPE html>
<html lang="en">

<head>

    <?php
    // session_start();
    // session_start();
     require_once '../resources/config.php';
    // require_once USER_MOD . '/Account_User.php';
    // require_once USER_MOD . '/Patient.php';
    // require_once APPT_MOD . '/Appointment_Record.php';
    // require_once APPT_MOD . '/RetrieveAppointment.php';
    // require_once ENUMS_PATH . '/User_Type.php';
    // require_once TIME_MOD . '/CalendarICS.php';
    // require_once TIME_MOD . '/Time.php';
    ?>

    <link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />

    <style>
    <?php include './css/medicaldashboard.css';
    ?>
    </style>
    
    <?php
    include TEMPLATES_PATH . '/bootstrap.php';
    include_once TEMPLATES_PATH . '/navbar.php';
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- font awesome cdn -->
    <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
</head>

<body onload="startTime()">

    <?php

// if (isset($_SESSION["user"])):
//     $user = unserialize($_SESSION["user"]);
    
//     if (User_Type::check_user_type(User_Type::MEDICAL_PERSONNEL, $user_type)):
//         $email['credentials']['email'] = $user_email;
    
        
//         include COMPONENTS_PATH . '/navbar-loggedin.php';
  
  

?>


    <div class="row bg-light py-4">
        <div class="row bg-light">
            <div class="col-xs-3 col-md-2 px-5 mx-md-1 mx-lg-0">
                <img src="https://via.placeholder.com/100" class="rounded shadow float-start" alt="...">
            </div> <!-- col -->

            <div class="col-xs-8 col-md-6">
                <h1 class="display-6">Dr. Mark Spencer<?php //echo $user->get_fullname(); ?> </h1>
                <h1 class="lead"> Specialist <?php //echo $user->get_gender(); ?> </h1>
            </div> <!-- col -->

            <div class="col-md-2">
                <!-- col -->
                <div class="row py-1 col-lg-12 mx-auto">
                    <a href="./editprofile.php" type="button" class="btn btn-secondary float-end">Edit Profile</a>
                </div> <!-- col -->
            </div> <!-- col -->
        </div>
    </div>

    <div class="container-fluid mt-3">
        <div class="tab">
            <button class="tablinks top" onclick="openCity(event, 'Dashboard')"
                    id="defaultOpen"><i class="far fa-window-maximize tab-icon"></i>Dashboard </button>
            <button class="tablinks" onclick="openCity(event, 'Appointments')"><i
                        class="far fa-calendar-alt tab-icon"></i>Appointments</a> </a>
            <button class="tablinks" onclick="openCity(event, 'Data')"><i
                        class="fas fa-chart-bar tab-icon"></i>Data</a> </a>
            <button class="tablinks" onclick="openCity(event, 'Settings')"><i
                        class="far fa-clock tab-icon"></i>Shift Settings</a> </a>
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
                    <div class="col">
                        <div class="p-4 shadow" style="border-radius: 25px">
                            <canvas id="chart3" height="250px"></canvas>
                        </div>
                    </div>

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

    </div>




    <?php //endif; 
//endif;?>
</body>

<script>
function startTime() {
  const today = new Date();
  let h = today.getHours();
  let m = today.getMinutes();
  let s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('time').innerHTML =  h + ":" + m + ":" + s;
  setTimeout(startTime, 1000);
}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
</script>

<!-- CHART JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.min.js"
    integrity="sha512-VCHVc5miKoln972iJPvkQrUYYq7XpxXzvqNfiul1H4aZDwGBGC0lq373KNleaB2LpnC2a/iNfE5zoRYmB4TRDQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: 'Number of ',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
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
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

var ctx2 = document.getElementById("chart2").getContext("2d");
var myChart1 = new Chart(ctx2, {
    type: 'line',
    data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
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
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
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

<script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>

<script>
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
        limit: 3,
        summary: false
    }
}).render(document.getElementById("wrapper2"));
</script>
<script>
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
</script>

<script>
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>

</html>