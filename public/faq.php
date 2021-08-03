<?php
session_start();
require_once '../resources/config.php';
require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Patient.php';
require_once APPT_MOD . '/Appointment_Record.php';
require_once ENUMS_PATH . '/User_Type.php';

/*
 * HOME PAGE (LANDING PAGE)
 */

include TEMPLATES_PATH . '/bootstrap.php';
$pageName = "homepage";
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://use.fontawesome.com/releases/v5.13.1/js/all.js"></script>
        <!-- BOOTSTRAP CDN -->
        <link rel="stylesheet" href="./css/homepage.css">
		
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
		
        <title>Frequently Asked Questions</title>
    </head>

    <body>
        <?php
        if (isset($_SESSION["user"])):
            $user = unserialize($_SESSION["user"]);
            include TEMPLATES_PATH . '/navbar-loggedin.php';
        else:
            include TEMPLATES_PATH . '/navbar.php';
        endif;
        ?>
       
		<!--Header-->
		<div class="header">
			<br>
			<h1>&emsp;Frequently Asked Questions</h1>
		</div>

		<details>
			<summary class="question">How do I register for an account?</summary>
				<div class="answer">
				You may click <a href="<?php echo REGISTER_WEB; ?>"> here</a> to register for an account.
				</div>
		</details>

		<details>
			<summary class="question">What do I do if I forgot my password for my account?</summary>
				<div class="answer">
				You may click <a href="<?php echo FORGOT_PW_WEB; ?>"> here</a> to change your password.<br><br>
				Please drop us an email at <a href = "mailto: support.fyp.21.s2.24@gmail.com"> support.fyp.21.s2.24@gmail.com </a> there are any issues.		
				</div>
		</details>
		
		<details>
			<summary class="question">What is your operating hours?</summary>
				<div class="answer">
					<b>Our Operating Hour:</b> <br>
					Monday - Friday: 8AM - 6PM <br>
					Saturday - Sunday: 8AM - 5.30PM
				</div>
		</details>
		
		<details>
			<summary class="question">What do I do if I could not make it to an appointment?</summary>
				<div class="answer">
					You may reschedule the appointment to another date where you will be available, or you may cancel the appointment.
				</div>
		</details>
		
		<details>
			<summary class="question">What do I do if I missed an appointment?</summary>
				<div class="answer">
					Please go to the counter and talk to a medical staff for assistance.					
				</div>
		</details>
		
		<details>
			<summary class="question">I would like to enquire about services by MyAppointment. Who can I get in touch with?</summary>
				<div class="answer">
				You may get in touch with us by dropping us an email at <a href = "mailto: support.fyp.21.s2.24@gmail.com"> support.fyp.21.s2.24@gmail.com </a>
				</div>
		</details>
    </body>
</html>