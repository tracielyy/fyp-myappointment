<!DOCTYPE html>

<?php
    session_start();
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
    require VENDOR_PATH . '/autoload.php';
    require_once USER_MOD . '/Account_User.php';
    require_once USER_MOD . '/Patient.php';
    require_once USER_MOD . '/Medical_Personnel.php';
    require_once USER_MOD . '/Facility_Admin.php';
    require_once USER_MOD . '/Super_Admin.php';

    require_once APPT_MOD . '/Appointment_Record.php';
    require_once ENUMS_PATH . '/User_Type.php';

    require_once ENUMS_PATH . '/Health_Info_Type.php';
    require_once HINFO_MOD . '/Health_Info.php';
    require_once UTIL_MOD . '/StringUtils.php';
    ?>

<?php
    include TEMPLATES_PATH . '/bootstrap.php';
    if (isset($_SESSION["user"])):

    $user = unserialize($_SESSION["user"]);
        include TEMPLATES_PATH . '/navbar-loggedin.php';
    else:
        include TEMPLATES_PATH . '/navbar.php';
    endif;
    $all_health_articles = Health_Info::retrieve_all_healthinfo();
    $healtharticles_count = count($all_health_articles);
    $healtharticles_divisible_by_six = $healtharticles_count % 6;



    ?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health and Information Tips</title>
    <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
    <script src="https://pagination.js.org/dist/2.1.5/pagination.min.js"></script>

    <style>
    <?php include './css/healthlist.css';
    ?>
    </style>

</head>

<body>
    <div class="container my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Article List</li>
            </ol>
        </nav>
        <h1 class="mb-3 display-4">Health Information and Tips</h1>

        <div class="row">
            <div class="col">

                <section name="LATEST">
                    <div class="row mt-3">
                        <div class="col">
                            <div class="card">
                                <div class="card-body rounded" style="background-color: #3353CE;">
                                    <b class="text-white"><i class="fas fa-info-circle me-2"></i>Latest</b>
                                </div>
                            </div>

                            <!-- Card -->
                            <?php 
                            
                                     
                            foreach($all_health_articles as $article):
                            
                                $desc = substr($article->get_descriptions(),0,500);
                                $desc = trim(preg_replace('/\s+/', ' ', $desc));
                                $type = $article->get_type();
                                if ($type == 'World Health Notice')
                                {
                                    $badge = '<span class="badge bg-warning ms-2">World Health Notice</span>';
                                    $icon = '<i class="fas fa-shield-virus fa-3x opt-icon"></i>';
                                } else if ($type == "Doctor's Advice")
                                {
                                    $badge = '<span class="badge bg-primary ms-2">Doctor\'s Advice</span>';
                                    $icon = '<i class="fas fa-user-md fa-3x opt-icon"></i>';
                                }   
                                else if($type == 'Health Tips')
                                {
                                    $badge = '<span class="badge bg-success ms-2">Health Tips</span>';
                                    $icon = '<i class="fas fa-plus-square fa-3x opt-icon"></i>';
                                }
                                echo '<div class="card mh-100">';
                                echo '<div class="card-body">';
                                echo '<div class="row">';
                                echo '<div class="col-1 min-vw-1000">';
                                echo $icon;
                                echo '</div>';
                                echo '<div class="col">';
                                echo '<h5 class="card-title">'.$article->get_title().$badge.'</h5>';
                                echo '<p class="card-text line-clamp" style="margin:2px">'.$desc.'</p>';
                                echo '<a href="article.php?id='.$article->get_id().'" class="card-link stretched-link"><small class="text-muted">Click here to read more...</small></a>';
                                echo '<br><small class="text-muted">Created on '.$article->get_createdon()->get_date().'</small>';
                                if($article->get_createdon()->get_date() != $article->get_updatedon()->get_date())
                                {
                                    echo '<small class="text-muted"> • Updated on '.$article->get_updatedon()->get_date().'</small>';
                                }
                                echo '</div></div></div></div>';
                                $nextid = $article->get_id();
                            endforeach;
                            $loadmore = array();
                            $loadmore = Health_Info::retrieve_all_healthinfo($nextid);
                            echo var_dump($loadmore);
                            ?>

                </section>


            </div>
        </div>
        <?php
        if(true): ?>
        <div class="row mt-4">
            <div class="col text-center">
                <form>
                    <button class="btn btn-primary btn-lg text-center">
                        Load More Articles
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
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