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
	<title> Ranitidine </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>

<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Ranitidine</b></h1>
				<h3>Ranitidine - What is it for</h3>
				<p> ​​Ranitidine works by reducing the amount of acid produced in the stomach. It is used to treat various conditions of the gastrointestinal (stomach and intestines) tract including: </p>
				<ul>
					<li> Gastro-oesophageal reflux disease (a condition where the acid from the stomach goes back up the food tube and throat, causing pain and a burning sensation known as heartburn) </li>
					<li> Ulcers of the gastrointestinal tract (treatment and prevention)    </li>
				</ul>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				
				<h5>Warnings</h5>
				<p>Using ranitidine may increase your risk of developing pneumonia. Symptoms of pneumonia include chest pain, fever, feeling short of breath, and coughing up green or yellow mucus. Talk with your doctor about your specific risk of developing pneumonia.</p>
				<p>Ask a doctor or pharmacist if it is safe for you to take this medicine if you have kidney disease, liver disease, or porphyria.</p>
				<p>Heartburn is often confused with the first symptoms of a heart attack. Seek emergency medical attention if you have chest pain or heavy feeling, pain spreading to the arm or shoulder, nausea, sweating, and a general ill feeling.</p>
				
				<h5>Before taking this medicine</h5>
				<p>If you have been taking prescription-strength ranitidine: Before you stop taking the medicine, ask your doctor about safer treatment options.</p>
				<p>If you have been taking over-the-counter (OTC) ranitidine: Stop taking the medicine, and ask your doctor or pharmacist about other approved OTC stomach acid reducers.</p>
				<p>Before using any OTC medicine to reduce stomach acid, ask a doctor or pharmacist if the medicine is safe for you if you have other medical conditions or allergies.</p>
				
				<h5> What side effects can Ranitidine cause? </h5>
				<p> ​Side effects of ranitidine include stomach pain, constipation, nausea, headaches and rarely blurred vision. </p>
				<ul>
					<li> Please consult your healthcare professional if the symptoms do not go away or when any unexplained or unusual symptoms occur. </li>
				</ul>
				<p> Consult your healthcare professional if you develop any blood in your stool (including blackened stool) or vomiting, severe stomach pain, confusion, unexplained fever or feeling faint, especially when standing up. </p>
				<p> Very rarely, it might affect your liver function. If you experience symptoms like dark urine or light coloured stools, nausea, vomiting, loss of appetite, stomach pain, yellowing of your eyes or skin, please contact your healthcare professional immediately. </p>
				<p> The symptoms of a drug allergy include one or more of the following: </p>
				<ul>
					<li> Swollen face/eyes/lips/tongue </li>
					<li> Difficulty in breathing </li>
					<li> Itchy skin rashes over your whole body </li>
				</ul>
				<p> If you experience any of these symptoms, you should stop your medication and see your healthcare professional immediately. </p>
			</section>
			
			<section id="dosage">
				<h3>Dosage and How to Use</h3>
				<h5> How should it be used? </h5>
				<ul>
					<li> ​Do not stop taking your medication without checking with your healthcare professional. </li>
					<li> You may take this medication with or without food. </li>
				</ul>
				<h5> What should I do if I miss a dose? </h5>
				<p> ​If you forget to take a dose, take it as soon as you remember. Skip the dose if it is too near to your next dose. Then take your next dose at the usual time. Do not take two doses to make up for the missed dose. </p>
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