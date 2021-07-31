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
	<title> Filgrastim </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>

<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Filgrastim</b></h1>
				<h3>Filgrastim - What is it for</h3>
				<p> Filgrastim is a medicine which stimulates the bone marrow to produce white blood cells. White blood cells help your body to fight infections. </p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				<h5>Warnings</h5>
				<p>It can cause your spleen to become enlarged and it could rupture (tear). Please inform your doctor right away if you have sudden or seveere pain in your left upper stomach spreading up to your shoulder.</p>
				
				<h5>Before taking this medicine</h5>
				<p>You should not use filgrastim if you are allergic to filgrastim or pegfilgrastim, or to other medicines that contain the E.coli bacteria.</p>
				<p>Inform your doctor if you ever had:</p>
				<ul>
					<li>Sickle cell disorder</li>
					<li>Kidney disease</li>
					<li>Latex allergy</li>
					<li>Radiation treatment</li>
				</ul>
				
				<h5> What side effects can Filgrastim cause? </h5>
				<ul>
					<li> Mild to moderate bone pain may occur </li>
					<ul>
						<li> This may be relieved by taking paracetamol. </li>
					</ul>
					<li> Flu-like syndrome e.g. fever, chills, headache, body aches </li>
					<ul>
						<li> Usually occurs at start of treatment. Maintain hydration – drink lots of fluids – and take paracetamol if necessary. </li>
					</ul>
					<li> Headache </li>
					<li> Mild skin rash may occur </li>
					<ul>
						<li> More common in patients with history of sensitive skin. </li>
					</ul>
					<li> Pain and redness at injection site </li>
				</ul>
				<p><b> Inform your doctor if any of the following occurs: </b></p>
				<ul>
					<li> Fever of 38°C or higher </li>
					<li> Sores in the mouth and throat </li>
					<li> Chest pain and rapid irregular heartbeat </li>
					<li> Abnormal severe abdominal or shoulder pain </li>
					<li> Allergic reaction e.g. rash, itch, swelling, dizziness and breathing problems </li>
				</ul>
			</section>
			
			<section id="dosage">
				<h3>Dosage and How to Use</h3>
				<h5> How should it be used? </h5>
				<p> It is a subcutaneous injection with various dosing schedule. Follow the dosage schedule accordingly and give at least 24 hours before or after chemotherapy. Filgrastim is preferably given at the same time for every dose. </p>
				<ul>
					<li> ​​​Do not shake the syringe. </li>
					<li> Take the syringe out of the refrigerator 30 minutes before use and allow it to reach room temperature before injecting. </li>
					<li> Check the syringe for any particulate matter or discolouration before injecting. Do not use the injection if these are present. </li>
				</ul>
				<h5> What should I do if i miss a dose? </h5>
				<p> ​If you miss a dose of Filgrastim, inject it as soon as you can if it is within 12 hours of the missed dose. If it is over 12 hours since your missed dose, skip the missed dose and go back to your usual dosing schedule. Do not inject twice within the same day. </p>
			</section>
			
			<section id="storage">
				<h3>Storage</h3>
				<h5> How should I store it? </h5>
				<ul>
					<li> Keep away from children </li>
					<li> Keep in a cool, dry place, away from direct sunlight </li>
					<li> Store in the refrigerator. Do not freeze. </li>
					<li> ​Discard any syringes exposed to room temperature (up to 30°C) for a period longer than 6 hours. </li>
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