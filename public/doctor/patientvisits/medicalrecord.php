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
    <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
    <style>
    body {
        font-size: 20px !important;
    }
    </style>

    <!-- Prevent Form Resubmission -->
    <script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    function loopforpresc(value) {

        $(function() {

            $(window).bind('load', function() {

                console.log("test");
                inputpresc = '<div class="input-group mt-3"><input type="text" class="form-control" placeholder="" name="prescriptions[]" value="'+ value +'"><button class="btn btn-danger" id="removepresc"><i class="fas fa-minus"></i></button></div>';
                $('.fieldwrapper').append(inputpresc);
                console.log(inputpresc);

            });
        });

    }
    </script>
    <?php
                $presciptionsArray = array();
                $num_of_presc = 0;
                include TEMPLATES_PATH . '/bootstrap.php';
                //include_once TEMPLATES_PATH . '/navbar-loggedin.php';
                $diagnosisdesc = "";
                foreach($presciptionsArray as $key => $value) :
                    ?><script>
                    loopforpresc(<?php echo "'".$value."'"?>)
                    </script><?php
                endforeach;

                if($_SERVER["REQUEST_METHOD"] == "POST"):
                    
                        $presciptionsArray = $_POST['prescriptions'];
                        $num_of_presc = count($presciptionsArray);
                        echo  $num_of_presc;
                  

                    if (isset($_POST['diagnosis'])) :
                        $diagnosisdesc = $_POST['diagnosis'];
                        echo $diagnosisdesc;
                        
                    endif;

                    foreach($presciptionsArray as $key => $value) :
                        ?><script>
                        loopforpresc(<?php echo "'".$value."'"?>)
                        </script><?php
                    endforeach;

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
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="row mt-2">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Diagnosis
                    </div>
                    <div class="card-body">
                        
                            <textarea type="text" id="diagnosistext" class="form-control" name="diagnosis"
                                rows="15"><?php echo htmlspecialchars($diagnosisdesc);?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2 mb-2">
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="fieldwrapper">
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2">
        <button name="submitdiagnosis" type="submit" class="btn btn-primary btn-lg"
                                style="float:right;" id="savechanges">Save Changes</button>
        </div>
        </form>
    </div>
</body>
<script>

/*---------------------------
        PRESCRIPTIONS
----------------------------*/
$(document).ready(function() {
    var maxField = 10; //Input fields increment limitation
    var addButton = $('#addprescription'); //Add button selector
    var wrapper = $('.fieldwrapper'); //Input field wrapper
    var fieldHTML ='<div class="input-group mt-3"><input type="text" class="form-control" placeholder="" name="prescriptions[]" value=""><button class="btn btn-danger" id="removepresc"><i class="fas fa-minus"></i></button></div>'; //New input field html 
    var x = <?php echo $num_of_presc; ?>; //Initial field counter is 1

    //Once add button is clicked
    $(addButton).click(function() {
        //Check maximum number of input fields
        console.log(x);
        if (x < maxField) {
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
            if (x == maxField)
            {
            $(wrapper).append('<small class="text-muted" id="maxalert"> Maximum 10 prescriptions reached </small>'); //Add field html
            }
        }
       
    });

    //Once remove button is clicked
    $(wrapper).on('click', '#removepresc', function(e) {
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
});

</script>


</html>