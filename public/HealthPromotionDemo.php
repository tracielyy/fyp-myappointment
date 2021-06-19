<!DOCTYPE html>
<html lang="en">

<?php
session_start();
require_once '../resources/config.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once ENTITIES_PATH . '/Appointment_Record.php';
require_once ENUMS_PATH . '/User_Type.php';
require_once FUNCTIONS_PATH . '/PatientFunctions.php';
require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';

include COMPONENTS_PATH . '/bootstrap.php';


?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Why You Should Take Care of Your Body and Health</title>
</head>

<body>
    <?php

if (isset($_SESSION["user"])):
  $user = unserialize($_SESSION["user"]);
  include COMPONENTS_PATH . '/navbar-loggedin.php';
else:
  include COMPONENTS_PATH . '/navbar.php';
endif;

?>
    <div class="container mt-5">

        <div class="row">
            <h1 class="display-5"> <b>Why You Should Take Care of Your Body and Health </b> </h1>
            <p class="text-muted">By Elizabeth Scott,MS | Medically Reviewed by Daniel B. Block MD, Februrary 13, 2020
        </div>
        <div class="row mt-5">
            <div class="col-6">
                <img class="mb-4" src="https://via.placeholder.com/500x300" alt="...">
                <p> Health problems, even minor ones, can interfere with or even overshadow other aspects of your life.
                    Even
                    relatively minor health issues such as aches, pains, lethargy, and indigestion take a toll on your
                    happiness
                    and stress levels. One way to improve your ability to cope with stress and feel better is to make a
                    commitment to healthier habits.</p>

                <p> Poor health habits can add stress to your life and also play a role in how well you are able to cope
                    with
                    stress. The stress that comes from poor health is significant. Health challenges also affect other
                    areas
                    of
                    your life. Health problems can make daily tasks more challenging, create financial stress, and even
                    jeopardize your ability to earn a living.</p>

                <p> Stress itself can exacerbate health issues from the common cold to more serious conditions and
                    diseases,
                    so
                    maintaining healthy habits can pay off in the long run. </p>

                <h1 class="display-6"> Eat a Healthy Diet for the Right Reasons </h1>

                <p>Rather than eating right solely for the promise of looking better in your jeans, you should also make a
                commitment to eating foods that will boost your energy levels and keep your system running smoothly.
                This is because what you eat can not only impact your short-term and long-term health, it can affect
                your stress levels.</p>

                <p>It's much harder to cope with stress if you are hungry or malnourished. Hunger can make you more
                emotionally reactive to stressors, leaving you irritable or even angry in the face of minor daily
                annoyances. Watching what you eat can be a stress management tool as well as a health preserver.</p>

                <p>Another reason it's a good idea to maintain a healthy diet is that your diet can have an effect on your
                mood. While the effects of an unhealthy diet are cumulative and become more apparent in the long-term,
                you are also less likely to feel well in the short-term if you are eating a diet heavy on sugar-laden,
                fatty, or nutritionally empty foods.</p>

                <p>Some of the more immediate effects poor diet include feeling:</p>

            </div>

            <div class="col-6">
            </div>
        </div>
    </div>
</body>

</html>