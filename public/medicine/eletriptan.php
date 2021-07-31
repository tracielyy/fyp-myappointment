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
	<title> Eletriptan </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>

<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Eletriptan</b></h1>
				<h3>Eletriptan - What is it for</h3>
				<p> ​​Eletriptan is a selective serotonin receptor agonist. It works by reducing inflammation and reversing the widening of blood vessels in the brain, thereby stopping a migraine headache. It is often used to treat a migraine attack after it begins. It will not prevent a migraine. </p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				<h5>Warnings</h5>
				<p>You should not use eletriptan if you have ever had heart disease, coronary artery disease, blood circulation problems, Wolff-Parkinson-White syndrome, uncontrolled high blood pressure, severe liver disease, a heart attack or stroke, or if your headache seems to be different from your usual migraine headaches.</p>
				
				<h5>Before taking this medicine</h5>
				<p>You should not use eletriptan if you are allergic to it, or if you have:</p>
				<ul>
					<li>Heart problems, or a stroke</li>
					<li>Heart disorder called Wolff-Parkinson-White syndrome</li>
					<li>Uncontrolled high blood pressure</li>
					<li>Headache that seems different from your usual migraine headaches</li>
				</ul>
				
				<h5> What side effects can Eletriptan cause? </h5>
				<ul>
					<li> ​Dizziness, drowsiness, weakness </li>
					<ul>
						<li> Do not drive or use machinery. </li>
						<li> Do not drink alcohol when taking this medication. </li>
					</ul>
					<li> Pain or tightness in your throat or jaw </li>
					<ul>
						<li> If pain is intense and does not go away, please seek medical attention. </li>
					</ul>
					<li> Nausea, abdominal pain </li>
					<li> Dry mouth </li>
				</ul>
				<p> The symptoms of a drug allergy include one or more of the following: </p>
				<ul>
					<li> Swollen face/eyes/lips/tongue </li>
					<li> Difficulty in breathing </li>
					<li> Itchy skin rashes over your whole body </li>
				</ul>
				<p> Signs and symptoms of serotonin syndrome such as: </p>
				<ul>
					<li> Feeling agitated and restless, other mental changes such as hallucination </li>
					<li> Heavy sweating, shivering </li>
					<li> Fast heart rate, irregular heartbeat </li>
					<li> Rigid or twitching muscles </li>
					<li> Nausea, vomiting or diarrhoea </li>
				</ul>
				<p> Signs and symptoms of a heart attack: </p>
				<ul>
					<li> Discomfort in the middle of your chest that lasts for a few minutes or goes away and comes back </li>
					<li> Shortness of breath with or without chest discomfort </li>
					<li> Chest pain or chest discomfort that feels like an uncomfortable heavy pressure, squeezing, fullness or pain </li>
				</ul>
				<p> If you experience any of these symptoms, you should stop your medication and see your healthcare professional immediately. </p>
			</section>
			
			<section id="dosage">
				<h3>Dosage and How to Use</h3>
				<h5> How should it be used? </h5>
				<ul>
					<li> ​​Follow your doctor’s instructions as to how many tablets to take when the attack starts. </li>
					<li> If your migraine improves but comes back, wait at least 2 hours before taking the next dose. Do not take more than 80mg per day.  </li>
					<li> Do not use eletriptan too frequently (limit to 10 days of use per month), as it can cause medication overuse headache (migraine/headache may worsen). Inform your doctor if you need to take eletriptan to treat more than 3 headaches in a 1 month period. </li>
					<li> You may take this medication with or without food. </li>
					<li> If the tablets did not give you enough help with your migraine, consult your doctor. </li>
				</ul>
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