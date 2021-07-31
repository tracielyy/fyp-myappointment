<!DOCTYPE html>
<?php
session_start();
/* Load Config File */
require_once '../../resources/config.php';
require '../../vendor/autoload.php';
require_once EMAIL_MOD . '/Email.php';
require_once UTIL_MOD . '/StringUtils.php';
require_once UTIL_MOD . '/Regex.php';
require_once AUTH_MOD . '/Authentication.php';
require_once USER_MOD . '/Account_User.php';

if (isset($_SESSION["user"])):
    # "Unboxin" User Information
    $user = unserialize($_SESSION["user"]);
    $user_email = $user->get_email();
    $user_type = $user->get_usertype();
    $email['credentials']['email'] = $user_email;
	
    include_once TEMPLATES_PATH . '/navbar-loggedin.php';
    include TEMPLATES_PATH . '/bootstrap.php';
    if (User_Type::check_user_type(User_Type::PATIENT, $user_type)):
?>
<head>
	<title> Bethanechol </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>

<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Bethanechol</b></h1>
				<h3>Bethanechol - What is it for</h3>
				<p>​Bethanechol is used to treat urinary retention (trouble urinating) that may be caused by surgery, drugs or other factors. It helps the bladder muscle to squeeze better, and improve your ability to urinate.</p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				<h5>Warnings</h5>
				<p> Follow all directions on your medicine label and package. Tell each of your healthcare providers about all your medical conditions, allergies, and all medicines you use. </p>
				
				<h5> Before taking this medicine </h5>
				<p> You should not use bethanechol if you are allergic to it, or if you have: </p>
				<ul>
					<li> Coronary artery disease (clogged arteries) </li>
					<li> Asthma </li>
					<li> Overactive thyroid </li>
					<li> Blockage in your digestive tract (stomach or intestines) </li>
					<li> Parkinson's desease </li>
					<li> If you recently had surgery on your bladder or intestines </li>
					<li> Epilepsy or other seizure disorder </li>
				</ul>
				<p>You should not use bethanechol if you are allergic to it, or if you have:</p>
				<h5> What side effects can Bethanechol cause? </h5>
				<ul>
					<li> Drowsiness </li>
					<ul>
						<li> This may affect your ability to drive and use machinery. Do not drive or do things which requires you to be alert. </li>
					</ul>
					<li> Facial flushing where the skin on your face feels hot </li>
					<li> Nausea, vomiting </li>
					<ul>
						<li> Take small but frequent meals, and avoid fatty or spicy food. </li>
					</ul>
					<li> Headache </li>
					<ul>
						<li> You may take Paracetamol to relieve the headache. </li>
					</ul>
					<li> Sweating </li>
				</ul>
				
				<p> Consult your healthcare professional immediately if you experience slow heart rate of less than 50 beats per minute, shortness of breath or fainting. </p>
				<p> The symptoms of a drug allergy include one or more of the following: </p>
				<ul>
					<li> Swollen face/eyes/lips/tongue </li>
					<li> Difficulty in breathing </li>
					<li> itchy skin rashes </li>
				</ul>
				<p> If you experience any of these symptoms, you should stop your medication and see your healthcare professional immediately. </p>
			</section>
			
			<section id="dosage">
				<h3>Dosage and How to Use</h3>
				<h5> How should it be used? </h5>
				<ul>
					<li> Do not stop taking your medication without checking with your healthcare professional. </li>
					<li> This medication should be taken one hour before meals or two hours after meals to reduce nausea and vomiting </li>
					<li> This medication is usually taken three to four times daily. </li>
				</ul>
				<h5> What should I do if i miss a dose? </h5>
				<p> If you forget to take a dose, take it as soon as you remember. Then take your next dose at the usual time. Do not take two doses to make up for the missed dose. </p>
			</section>
			
			<section id="storage">
				<h3>Storage</h3>
				<h5> How should I store it? </h5>
				<ul>
					<li> Keep away from children </li>
					<li> Keep in a cool, dry place, away from direct sunlight </li>
				</ul>
			</section>
		</div>
		<nav class="section-nav">
			<ol>
				<li><a href="#what-is-it-for">What is it for</a></li>
				<li><a href="#side-effect">Side Effect, Warnings</a></li>
				<li><a href="#dosage">Dosage and How to Use</a></li>
				<li><a href="#storage">Storage</a></li>
			</ol>
		</nav>
	<main>
		<!-- If user not logged in, redirect user to login page -->
		<?php
		else:
			header("Location:login.php");
		endif;
		else: header("Location:login.php");
	endif;
	?>
</body>
</html>