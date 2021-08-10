
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

    $index = 0;
    $startfrom = $_POST['row'];
    $health_articles = Health_Info::retrieve_all_healthinfo($startfrom);

    if (count($health_articles) == 0)
    {
        echo '<div id="endOfContent"></div>';
    }
    else
    {
        foreach($health_articles as $article):
            
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
            echo '<div class="card mh-100">';
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

            $loadmore = Health_Info::retrieve_all_healthinfo($nextid);
            $count_loadmore = count($loadmore);
            if ($count_loadmore == 0)
            {
                echo '<div id="endOfContent"></div>';
            }
    }
   
        ?>                     
   
<?php 
    if ($_SERVER["REQUEST_METHOD"] == "GET"):
        header("Location:" . "/");
    endif;
?>
