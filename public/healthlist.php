<!DOCTYPE html>

<?php
    session_start();
    require_once '../resources/config.php';
    // require_once ENTITIES_PATH . '/Account_User.php';
    // require_once ENTITIES_PATH . '/Appointment_Record.php';
    // require_once ENUMS_PATH . '/User_Type.php';
    // require_once FUNCTIONS_PATH . '/PatientFunctions.php';
    // require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';
    ?>

<?php
    include TEMPLATES_PATH . '/bootstrap.php';
    include_once TEMPLATES_PATH . '/navbar.php';
    ?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health and Information Tips</title>
    <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>

    <style>
    <?php include './css/healthlist.css';
    ?>
    </style>

</head>

<body>
    <div class="container">

        <h1 class="mb-3"> Health Information and Tips</h1>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body rounded" style="background-color: #5BA33F;">
                        <b class="text-white"><i class="fas fa-star me-1"></i></i>Featured</b>
                    </div>
                </div>

                <!-- Card -->
                <div class="card mh-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-1 min-vw-1000">
                            <i class="fas fa-shield-virus fa-3x opt-icon"></i>
                            </div>
                            <div class="col">
                                <h5 class="card-title">Symptoms of COVID-19: How to identify and to treat <span
                                        class="badge bg-warning">World Health Notice</span></h5>
                                <p class="card-text line-clamp" style="margin:2px">Health problems, even minor ones, can
                                    interfere with
                                    or even
                                    overshadow other aspects of your life. Even relatively minor health issues such
                                    as aches, pains, lethargy, and indigestion take a toll on your happiness and
                                    stress levels. One way to improve your ability to cope with stress and feel
                                    better is</p>
                                <label class="card-text text-muted" style="margin:0">World Health Organization -
                                    Uploaded 5
                                    months ago</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Card -->

                <!-- Card -->
                <div class="card mh-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-1 min-vw-1000">
                                <i class="fas fa-user-md fa-3x opt-icon"></i>
                            </div>
                            <div class="col">
                                <h5 class="card-title">Why You Should Take Care of Your Body and Health <span
                                        class="badge bg-primary">Doctor's Advice</span></h5>
                                <p class="card-text line-clamp" style="margin:2px">Health problems, even minor ones, can
                                    interfere with
                                    or even
                                    overshadow other aspects of your life. Even relatively minor health issues such
                                    as aches, pains, lethargy, and indigestion take a toll on your happiness and
                                    stress levels. One way to improve your ability to cope with stress and feel
                                    better is</p>
                                <label class="card-text text-muted" style="margin:0">Dr. Sylvester Stallone - Uploaded 5
                                    months ago</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Card -->

                <!-- Card -->
                <div class="card mh-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-1 min-vw-1000">

                                <i class="fas fa-plus-square fa-3x opt-icon"></i>

                            </div>
                            <div class="col">
                                <h5 class="card-title">Why You Should Take Care of Your Body and Health <span
                                        class="badge bg-success">Health Tips</span></h5>
                                <p class="card-text line-clamp" style="margin:2px">Health problems, even minor ones, can
                                    interfere with
                                    or even
                                    overshadow other aspects of your life. Even relatively minor health issues such
                                    as aches, pains, lethargy, and indigestion take a toll on your happiness and
                                    stress levels. One way to improve your ability to cope with stress and feel
                                    better is</p>
                                <label class="card-text text-muted" style="margin:0">Dr. Sylvester Stallone - Uploaded 5
                                    months ago</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->

        <!-- FEATURED LIST END-->
        <div class="row mt-3">
            <div class="col">
                <div class="card">
                    <div class="card-body rounded" style="background-color: #3353CE;">
                        <b class="text-white"><i class="fas fa-info-circle me-2"></i>Latest</b>
                    </div>
                </div>

                <!-- Card -->
                <div class="card mh-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-1 min-vw-1000">

                                <i class="fas fa-user-md fa-3x opt-icon"></i>

                            </div>
                            <div class="col">
                                <h5 class="card-title">Why You Should Take Care of Your Body and Health <span
                                        class="badge bg-primary">Doctor's Advice</span></h5>
                                <p class="card-text line-clamp" style="margin:2px">Health problems, even minor ones, can
                                    interfere with
                                    or even
                                    overshadow other aspects of your life. Even relatively minor health issues such
                                    as aches, pains, lethargy, and indigestion take a toll on your happiness and
                                    stress levels. One way to improve your ability to cope with stress and feel
                                    better is</p>
                                <label class="card-text text-muted" style="margin:0">Dr. Sylvester Stallone - Uploaded 5
                                    months ago</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Card -->

                <!-- Card -->
                <div class="card mh-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-1 min-vw-1000">

                                <i class="fas fa-user-md fa-3x opt-icon"></i>

                            </div>
                            <div class="col">
                                <h5 class="card-title">Why You Should Take Care of Your Body and Health <span
                                        class="badge bg-success">Health Tips</span></h5>
                                <p class="card-text line-clamp" style="margin:2px">Health problems, even minor ones, can
                                    interfere with
                                    or even
                                    overshadow other aspects of your life. Even relatively minor health issues such
                                    as aches, pains, lethargy, and indigestion take a toll on your happiness and
                                    stress levels. One way to improve your ability to cope with stress and feel
                                    better is</p>
                                <label class="card-text text-muted" style="margin:0">Dr. Sylvester Stallone - Uploaded 5
                                    months ago</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Card -->

                                <!-- Card -->
                                <div class="card mh-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-1 min-vw-1000">

                                <i class="fas fa-user-md fa-3x opt-icon"></i>

                            </div>
                            <div class="col">
                                <h5 class="card-title">Why You Should Take Care of Your Body and Health <span
                                        class="badge bg-success">Health Tips</span></h5>
                                <p class="card-text line-clamp" style="margin:2px">Health problems, even minor ones, can
                                    interfere with
                                    or even
                                    overshadow other aspects of your life. Even relatively minor health issues such
                                    as aches, pains, lethargy, and indigestion take a toll on your happiness and
                                    stress levels. One way to improve your ability to cope with stress and feel
                                    better is</p>
                                <label class="card-text text-muted" style="margin:0">Dr. Sylvester Stallone - Uploaded 5
                                    months ago</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Card -->

                                <!-- Card -->
                                <div class="card mh-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-1 min-vw-1000">

                                <i class="fas fa-user-md fa-3x opt-icon"></i>

                            </div>
                            <div class="col">
                                <h5 class="card-title">Why You Should Take Care of Your Body and Health <span
                                        class="badge bg-success">Health Tips</span></h5>
                                <p class="card-text line-clamp" style="margin:2px">Health problems, even minor ones, can
                                    interfere with
                                    or even
                                    overshadow other aspects of your life. Even relatively minor health issues such
                                    as aches, pains, lethargy, and indigestion take a toll on your happiness and
                                    stress levels. One way to improve your ability to cope with stress and feel
                                    better is</p>
                                <label class="card-text text-muted" style="margin:0">Dr. Sylvester Stallone - Uploaded 5
                                    months ago</label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
        

    </div>



</body>


<script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>
<script>
import {
    css
} from '@emotion/css';

const grid = new gridjs.Grid({
    columns: ["Title", "Description", "Author", "Posted on"],
    search: true,
    data: [
        ["Why You Should Take Care of Your Body and Health",
            "Health problems, even minor ones, can interfere with or even ...", "Dr. John Mark",
            "1 year ago"
        ],
        ["Fast Remedies When Having Headache",
            "Even relatively minor health issues such as aches, pains, lethargy ...", "Dr. John Mark",
            "5 days ago"
        ],
        ["Reasons Why Sleep is the Most Important Part of the Day",
            "Sleep can have a serious impact on your overall health and well-being. Make a ...",
            "Dr. David Jones", "2 days ago"
        ],
        ["Your Posture is Affecting Your Health",
            "We've all heard the advice to eat right and exercise, but it can be difficult to fit in ...",
            "Dr. Sarah Eoin", "7 days ago"
        ],
        ["Eat This Everyday, and You Will Feel Better",
            "Indigestion take a toll on your happiness and stress ...", "Dr. John Mark", "1 year ago"
        ],
        ["What To do When You are Bloated", "Rather than eating right solely for the promise of...",
            "Dr. John Mark", "5 days ago"
        ],
        ["Why You Should Take Care of Your Body and Health",
            ">Health problems, even minor ones, can interfere with or even ...", "Dr. John Mark",
            "1 year ago"
        ],
        ["Fast Remedies When Having Headache",
            "Even relatively minor health issues such as aches, pains, lethargy ...", "Dr. John Mark",
            "5 days ago"
        ],
        ["Reasons Why Sleep is the Most Important Part of the Day",
            "Sleep can have a serious impact on your overall health and well-being. Make a ...",
            "Dr. David Jones", "2 days ago"
        ],
        ["Your Posture is Affecting Your Health",
            "We've all heard the advice to eat right and exercise, but it can be difficult to fit in ...",
            "Dr. Sarah Eoin", "7 days ago"
        ],
        ["Eat This Everyday, and You Will Feel Better",
            "Indigestion take a toll on your happiness and stress ...", "Dr. John Mark", "1 year ago"
        ],
        ["What To do When You are Bloated", "Rather than eating right solely for the promise of...",
            "Dr. John Mark", "5 days ago"
        ],
        ["Why You Should Take Care of Your Body and Health",
            ">Health problems, even minor ones, can interfere with or even ...", "Dr. John Mark",
            "1 year ago"
        ],
        ["Fast Remedies When Having Headache",
            "Even relatively minor health issues such as aches, pains, lethargy ...", "Dr. John Mark",
            "5 days ago"
        ],
        ["Reasons Why Sleep is the Most Important Part of the Day",
            "Sleep can have a serious impact on your overall health and well-being. Make a ...",
            "Dr. David Jones", "2 days ago"
        ],
        ["Your Posture is Affecting Your Health",
            "We've all heard the advice to eat right and exercise, but it can be difficult to fit in ...",
            "Dr. Sarah Eoin", "7 days ago"
        ],
        ["Eat This Everyday, and You Will Feel Better",
            "Indigestion take a toll on your happiness and stress ...", "Dr. John Mark", "1 year ago"
        ],
        ["What To do When You are Bloated", "Rather than eating right solely for the promise of...",
            "Dr. John Mark", "5 days ago"
        ]
    ],
    pagination: {
        enabled: true,
        limit: 9,
        summary: false
    }

})

grid.render(document.getElementById("wrapper"));

grid.on('rowClick', (...args) => console.log('row: ' + JSON.stringify(args), args));
grid.on('cellClick', (...args) => console.log('cell: ' + JSON.stringify(args), args));
</script>

</html>