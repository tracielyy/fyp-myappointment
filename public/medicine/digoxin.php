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
	<title> Digoxin </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>

<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Digoxin</b></h1>
				<h3>Digoxin - What is it for</h3>
				<p> Digoxin is used primarily to slow down rapid heart rate. It is also used in heart failure to improve heart function by strengthening the force of your heartbeat. </p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				<h5>Warnings</h5>
				<p>You should not use digoxin if you have ventricular fibrillation (a heart rhythm disorder of the ventricles, or lower chambers of the heart that allow blood to flow out of the heart).</p>
				
				<h5>Before taking your medicine</h5>
				<p>You should not use digoxin if you are allergic to it, or if you have ventricular fibrillation (a heart rhythm disorder of the ventricles, or lower chambers of the heart that allow blood to flow out of the heart).</p>
				<p>Inform your doctor if you had:</p>
				<ul>
					<li>A serious heart condition such as "sick sinus syndrome" or "AV block" (unless you have a pacemaker)</li>
					<li>Slow heartbeats that have caused you to faint</li>
					<li>Kidney disease</li>
					<li>Electrolyte imbalance</li>
				</ul>
				
				<h5> What side effects can Digoxin cause? </h5>
				<ul>
					<li> ​Diarrhoea, nausea and vomiting </li>
					<li> Headache, dizziness </li>
					<li> Problems with your vision – sudden change (blurring) in eyesight, yellow vision </li>
					<ul>
						<li> Do not drive or operate tools or heavy machinery if your vision is affected. Your doctor may also recommend an eyesight test to monitor for such side-effects. </li>
					</ul>
				</ul>
				<p> While these side-effects are rare, it is important to consult your healthcare professional if these side-effects become severe. These may indicate that the dose of digoxin may be too high for you. </p>
				<p> It is very important that you are on regular follow-up with your doctor to monitor for any possible side-effects. Inform your healthcare professional if you have any of the following side-effects. </p>
				<p> Digoxin may worsen existing abnormal heart rhythm, or cause new irregular heartbeats. Symptoms of abnormal heart rhythm include one or more of the following:</p>
				<ul>
					<li> Irregular heartbeat e.g. slowed heartbeat or palpitations (feels like your heart is racing, pounding, skipping a beat) </li>
					<li> Severe dizziness or fainting spells </li>
				</ul>
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
					<li> Do not stop taking your medication without checking with your healthcare professional. </li>
					<li> You may take this medication with or without food. </li>
				</ul>
				<h5> What should I do if i miss a dose? </h5>
				<p> ​If you forget to take a dose, take it as soon as you remember. If it is almost time for your next dose, skip the missed dose. Then take your next dose at the usual time. Do not take two doses to make up for the missed dose. </p>
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