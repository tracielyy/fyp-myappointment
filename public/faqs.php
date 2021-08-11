<!-- This File Is Solely Used For Debugging -->
<!DOCTYPE html>
<?php
session_start();
/* Load Config File */
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
    require VENDOR_PATH . '/autoload.php';
?>

<html>

<head>
    <!-- Title -->
    <title>FYP-21-S2-24: FAQs</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Styling -->
    <?php require_once TEMPLATES_PATH . '/bootstrap.php' ?>

    <style>
    body {
        padding: 0;
        margin: 0;
        background: #F7F8FB;
    }

    .question {
        background: white;
        border: 1px solid #EDEDED;
        border-radius: 5px;
        font-weight: 600;
        margin: 10px;
        padding: 10px 20px;
        cursor: pointer;
    }

    .answer {
        padding: 0px 30px;
    }
    </style>

</head>

<body>
    <!-- Logic -->
    <?php
       require_once USER_MOD . '/Account_User.php';
       require_once USER_MOD . '/Patient.php';
       require_once USER_MOD . '/Medical_Personnel.php';
       require_once USER_MOD . '/Facility_Admin.php';
       require_once USER_MOD . '/Super_Admin.php';
       require_once FAQ_MOD . '/Faq.php';

        if (isset($_SESSION["user"])):
 
        $user = unserialize($_SESSION["user"]);
        include TEMPLATES_PATH . '/navbar-loggedin.php';
    else:
        include TEMPLATES_PATH . '/navbar.php';
    endif;
        // Code here
        $FAQarray = array();
        $FAQarray = Faq::retrieve_all_faqs();
        ?>
    <!-- Show Different Sections Of FAQs  (Make Sure Can MInimize and Maximize) -->
    <div>
        <!-- Navigation -->

        <div class="header">
            <br>
            <h1>&emsp;Frequently Asked Questions</h1>
        </div>

        <div class="container">
            <div class="row">
                <div class="col">

                    <?php
                        $html = "";

                        if (isset($_POST['row'])):

                        foreach($FAQarray as $faq):
                        
                        $html .= '<details data-id='.$faq->get_id().'>';
                        $html .= '<summary class="question">'.$faq->get_question().'</summary>';
                        $html .= '<div class="answer">';
                        $html .= $faq->get_answer();
                        $html .= '<div>';
                        $html .= '</details>';
                        $nextid = $faq->get_id();
                        endforeach;
                        echo $html;
                        $loadmore = array();
                        $loadmore = Health_Info::retrieve_all_faqs($nextid);
                        $count_loadmore = count($loadmore);
                        if ($count_loadmore == 0)
                        {
                           echo '<div id="endOfContent"></div>';
                        }
                    endif;
                    ?>

                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col text-center">
                <button id="loadmorebttn" name="loadmore" type="submit" class="btn btn-primary btn-lg text-center"
                    value="">
                    Load More FAQs
                </button>
            </div>
        </div>
        <script>
        if ($("#endOfContent").length) {

            $("#loadmorebttn").remove();

        }

        row = $('.content:last').attr("data-id");
        $("#loadmorebttn").val(row);


        $("#loadmorebttn").on("click", function() {
            console.log("test");
            row = $('.content:last').attr("data-id");
            console.log(row);

            $.ajax({
                type: "POST",
                url: "",
                dataType: "text",
                data: {
                    row: row,
                },
                beforeSend: function() {
                    $("#loadmorebttn").text("Loading...");
                    $("#loadmorebttn").prop('disabled', true);
                },
                success: function(response) {
                    $("#loadmorebttn").text("Load More Articles");

                    $('.content:last').after(response).show().fadeIn("slow");
                    if ($("#endOfContent").length) {

                        $("#loadmorebttn").remove();
                    }
                    row = $('.content:last').attr("data-id");
                    $("#loadmorebttn").val(row);
                    $("#loadmorebttn").prop('disabled', false);
                },
                error: function() {
                    console.log("Error in ajax call");
                }
            });
        });
        </script>

    </div>
    <!-- Footer -->
</body>

</html>