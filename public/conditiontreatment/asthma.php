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
	<title> Asthma </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Asthma</b></h1>
				<h3>Asthma - What it is</h3>
				<p> Asthma is a reversible narrowing of the airways in the lungs. It is characterised by 3 airway problems, reversible obstruction, caused by increased reaction of the airways to various stimuli (triggers) and inflammation. It can be life-threatening if not properly managed. </p>
				<p> Asthma can develop at any age, though it is more common in children and teenagers. Some people get asthma for the first time when they are older. More than 13 million people have asthma and 1.5 million of them are 65 years or older. </p>
				<p> there is a tendency for asthma to be inherited, such that asthma may seem to "run in the family". If you have a blood relative with asthma or allergies, you are at higher risk of getting asthma. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<ul>
					<li> Wheezing sound </li>
					<li> Difficulty in breathing </li>
					<li> Chest tightness </li>
				</ul>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5>Causes</h5>
				<ul>
					<li> Changes in temperature and weather </li>
					<li> Tobacco smoke and wood smoke </li>
					<li> Infections (viral or bacterial) including common cold and influenza</li>
					<li> Perfume, paint and any strong odors or fumes </li>
				</ul>
				
				<h5> Risk Factors </h5>
				<ul>
					<li> Smoking </li>
					<li> Obesity </li>
					<li> Allergies </li>
					<li> Air Pollution </li>
				</ul>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> Asthma is a treatable health condition. Although at present there is no cure, with good management, people with asthma can lead normal, active lives. </li>
				<ul>
					<li> Assess severity of asthma and to monitor the response to treatment using objecting tests of lung function </li>
					<li> Use of medication to reverse and prevent airway inflammation that contributes to the airway narrowing </li>
					<li> Take preventive measures to avoid or eliminate factors that induce or trigger asthma flare-ups </li>
				</ul>
			</section>
		</div>
		<nav class="section-nav">
			<ol>
				<li><a href="#what-it-is">What it is</a></li>
				<li><a href="#symptom">Symptoms</a></li>
				<li><a href="#causes">Causes and Risk Factors</a></li>
				<li><a href="#treatment">Treatment</a></li>
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