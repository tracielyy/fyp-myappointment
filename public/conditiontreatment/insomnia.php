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
	<title> Insomnia </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Insomnia</b></h1>
				<h3>Insomnia - What it is</h3>
				<p> Insomnia is the complaint of inadequate or poor quality sleep that interferes with normal daytime functioning. </p>
				<p> For some people, insomnia means difficulty in falling asleep or waking up frequently during the night with problems getting back to sleep. For others, it is waking up too early in the morning and/or experiencing unrefreshing sleep. It can be transient or chronic. </p>
				<p> Everyone has a rough night or two, or short-term (transient) insomnia. Chronic insomnia, though, lasts for more than a month. </p>
				<p> You should consider seeking medical advice if your sleep has been disturbed at least several times over the past month, has gone on for weeks and months, or if it interferes with the way you feel or function during the day. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> Blepharitis symptoms may include one or more of the following: </p>
				<ul>
					<li> Aggravating dry eyes </li>
					<li> Chronic eye irritation with itchy or gritty sensation </li>
					<li> Crusting of the eyelids </li>
					<li> Flaking of the skin around the eyelids </li>
					<li> Recurrent or chronic red eyes </li>
				<ul>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5>Causes</h5>
				<p> Insomnia is a symptom of another problem. It can be caused by any of a number of factors. </p>

				<h5> Pyschological Factors </h5>
				<ul>
					<li> Tendency to insomnia </li>
					<li> Persistent stress </li>
					<li> Psychophysiological (learned) insomnia </li>
				</ul>
				<h5> Lifestyle </h5>
				<ul>
					<li> Stimulants </li>
					<li> Alcohol </li>
					<li> Work hours </li>
					<li> Exercise </li>
					<li> Sleeping pills </li>
				</ul>
				
				<h5>Risk Factors</h5>
				<p> Risk of insomnia is greater if: </p>
				<ul>
					<li><b>Over age 60.</b> Because of changes in sleep patterns and health, insomnia increases with age.</li>
					<li><b>Mental health disorder or physical health condition.</b> Many issues that impact your mental or physical health can disrupt sleep.</li>
					<li><b>Stress.</b> Stressful times and events can cause temporary insomnia. And major or long-lasting stress can lead to chronic insomnia.</li>
				</ul>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> Treatment will depend on the cause of the insomnia. A combination of behavioural approaches and medications are usually offered. </p>
				<p> Nearly everyone can benefit from an improved sleep hygiene. People with sleep disorders should work with their doctors to diagnose the problem and treat conditions that may be responsible. </p>
				<p> If your doctor diagnoses primary insomnia, consider behavioural therapy first, then discuss the proper use of prescription sleeping pills. </p>
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