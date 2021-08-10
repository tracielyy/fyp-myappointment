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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

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
                                    $badge = '<span class="badge bg-warning">World Health Notice</span>';
                                    $icon = '<i class="fas fa-shield-virus fa-3x opt-icon"></i>';
                                } else if ($type == "Doctor's Advice")
                                {
                                    $badge = '<span class="badge bg-primary">Doctor\'s Advice</span>';
                                    $icon = '<i class="fas fa-user-md fa-3x opt-icon"></i>';
                                }   
                                else if($type == 'Health Tips')
                                {
                                    $badge = '<span class="badge bg-success">Health Tips</span>';
                                    $icon = '<i class="fas fa-plus-square fa-3x opt-icon"></i>';
                                }
                                echo '<div class="card mh-100 content" data-id="'.$article->get_id().'">';
                                echo '<div class="card-body">';
                                echo '<div class="row">';
                                echo '<div class="col-xs-2 col-sm-2 col-lg-1 min-vw-1000">';
                                echo $icon;
                                echo '</div>';
                                echo '<div class="col">';
                                echo '<h5 class="card-title">'.$article->get_title().'<span class="ms-2"></span>'.$badge.'</h5>';
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
                            $count_loadmore = count($loadmore);
                            if ($count_loadmore == 0)
                            {
                               echo '<div id="endOfContent"></div>';
                            }

                            ?>
                        </div>
                    </div>
                </section>


            </div>
        </div>

        <div class="row mt-4">
            <div class="col text-center">
                <button id="loadmorebttn" name="loadmore" type="submit" class="btn btn-primary btn-lg text-center"
                    value="<?php echo $loadmore?>">
                    Load More Articles
                </button>
            </div>
        </div>

        <script>
        if ($("#endOfContent").length) {

            $("#loadmorebttn").remove();

        }
        $("#loadmorebttn").on("click", function() {
            console.log("test");
            row = $('.content:last').attr("data-id");
            console.log(row);

            $.ajax({
                type: "POST",
                url: "getHealthListData.php",
                dataType: "text",
                data: {
                    row: row,
                },
                beforeSend:function(){
                    $("#loadmorebttn").text("Loading...");
                    $("#loadmorebttn").prop('disabled', true);
                },
                success: function(response) {
                    $("#loadmorebttn").text("Load More Articles");
                    $('.content:last').after(response).show().fadeIn("slow");
                    if ($("#endOfContent").length) {

                        $("#loadmorebttn").remove();

                    }
                    $("#loadmorebttn").prop('disabled', false); 
                },
                error: function() {
                    console.log("Error in ajax call");
                }
            });
        });
        </script>
    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/gridjs/dist/gridjs.umd.js"></script>


</html>