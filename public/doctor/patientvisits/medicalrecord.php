<!DOCTYPE html>
<html lang="en">
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
 *  MEDICAL RECORDS
 */
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <style>
    body {
        font-size: 20px !important;
    }
    </style>
    <?php
                include TEMPLATES_PATH . '/bootstrap.php';
                //include_once TEMPLATES_PATH . '/navbar-loggedin.php';
                $diagnosisdesc = "";
                if (isset($_POST['submitdiagnosis'])) :
                    $diagnosisdesc = $_POST['diagnosis'];
                    
                    
                    //echo $diagnosisdesc;
                    //echo htmlspecialchars($_POST['diagnosistxtarea']);;

                elseif (isset($_POST['submitpresc'])) :
                
                endif;
                ?>
</head>

<body>
    <div class="container mt-5">

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Patient
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                Name: Yan Ying Ling
                            </div>
                            <div class="col">
                                Date of Birth: 01-01-2000
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                Gender: Female
                            </div>
                            <div class="col">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-2">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Diagnosis
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <textarea id="diagnosistext" class="form-control" name="diagnosis" rows="15"
                                disabled="disabled"></textarea>
                            <button type="button" class="btn btn-secondary mt-3" style="float:right"
                                id="editbttn">Edit</button>
                            <button name="submitdiagnosis" type="submit" class="btn btn-primary mt-3"
                                style="float:right; display: none;" id="savechanges">Save Changes</button>
                            <button type="button" class="btn btn-danger mt-3 me-2" style="float:right; display: none;"
                                data-bs-toggle="modal" id="cancelconfirm" data-bs-target="#cancelModal">Cancel</button>
                        </form>
                    </div>

                </div>
            </div>

        </div>

        <div class="row mt-2 mb-5">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Prescriptions
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <button id="addprescription" type="button" class="btn btn-secondary mb-2"
                                    style="float:right">Add prescription</button>
                                <button id="removeprescription" type="button" class="btn btn-secondary mb-2 me-2"
                                    style="float:right">Remove prescription</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <form class="mt-1" id="prescriptionform">
                                    <button name="submitpresc" id="submitprescription" type="button"
                                        class="btn btn-secondary mt-2" style="float:right" disabled>Submit
                                        prescription</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Cancellation Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Cancellation will result in removing all the changes you just have made. <br>
                    <small class="text-muted">If you have made no changes, you can just press the "Yes, I want to
                        cancel" button</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="cancel" data-bs-dismiss="modal">Yes, I want to
                        cancel</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
/*---------------------------
            DIAGNOSIS
----------------------------*/
var txt;
$("#editbttn").click(function() {
    $("#editbttn").hide();
    $("#savechanges").show();
    $("#cancelconfirm").show();
    $("#diagnosistext").prop("disabled", false);
    txt = $("#diagnosistext").val();

});

$("#cancel").click(function() {
    $("#editbttn").show();
    $("#savechanges").hide();
    $("#cancelconfirm").hide();
    $("#diagnosistext").prop("disabled", true);
    $("#diagnosistext").val(txt);

});

$("#savechanges").click(function() {
    $("#editbttn").show();
    $("#savechanges").hide();
    $("#cancelconfirm").hide();
    $("#diagnosistext").prop("disabled", true);
});

/*---------------------------
        PRESCRIPTIONS
----------------------------*/
var inputpresc;
var num = 0;
$("#addprescription").click(function() {
    if (num < 10) {
        num++;

        inputpresc = '<div class="input-group mt-2" id="inputgrpID-' + num +
            '"> <span class="input-group-text">' + num +
            '</span> <input type="text" class="form-control" name="presc-' + num + '" id="prescID-' + num +
            '"> </input> </div>';
        $(inputpresc).insertBefore("#submitprescription");
        if (num == 1) {
            $("#submitprescription").prop("disabled", false);
        }
        if (num == 10) {
            $('<small class="text-muted" id="maxalert"> Maximum 10 prescriptions reached </small>')
                .insertBefore("#submitprescription");
        }
    }

});

$("#removeprescription").click(function() {
    if (num > 0) {
        $("#inputgrpID-" + num).remove();
        num--;
        if (num == 9) {
            $("#maxalert").remove();
        }
        if (num == 0) {
            $("#submitprescription").prop("disabled", true);
        }
    }

});
</script>


</html>