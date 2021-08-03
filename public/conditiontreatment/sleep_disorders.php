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
	<title> Sleep Disorders </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Sleep Disorders</b></h1>
				<h3>Sleep Disorders - What it is</h3>
				<p> Sleep disorders are sleep-related disturbances due to underlying medical problems, lifestyle and environmental factors which usually cause sleep disruption, leading to insufficient or poor quality sleep. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> ​Common symptoms: </p>
				<ul>
					<li> Excessive daytime sleepiness </li>
					<li> Insomnia </li>
					<li> Breathing disturbances while sleeping </li>
					<li> Abnormal behaviour during sleep </li>
				</ul>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5> Causes </h5>
				<ul>
					<li> Excessive or poor managed stress </li>
					<li> Depression and anxiety </li>
					<li> Excessive caffeine consumption </li>
					<li> Poor sleep habits and irregular sleep patterns </li>
				</ul>
				
				<h5>Risk Factors</h5>
				<ul>
					<li>Smoker</li>
					<li>Heavy Drinker</li>
					<li>High Blood Pressure</li>
				</ul>
				</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> Most sleep disorders can be managed conservatively with a combination of good sleep education, medication and behavioural modification. In certain conditions like obstructive sleep apnoea, specific therapy may include positive airway pressure therapy (pressurised air delivered via a mask) or upper airway surgery. </p>
				<p> Difficult cases are referred to physicians trained in the management of sleep disorders. Many sleep disorder clinics are staffed by health professionals in multiple disciplines, such as neurologists, respiratory physicians, ENT surgeons, psychologists and psychiatrists. </p>
				<p> Most sleep disorder clinics have sleep laboratory facilities for sleep studies. PSG and MSLT recordings are performed by qualified PSG technologists. For patients with obstructive sleep apnoea, respiratory therapists assist in special sleep studies in which positive airway pressure is applied at various settings so that optimal therapeutic pressure settings can be determined. </p>
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