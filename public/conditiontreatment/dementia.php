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
	<title> Dementia </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Dementia</b></h1>
				<h3>Dementia - What it is</h3>
				<p> Dementia describes a group of symptoms such as memory loss, impaired judgment, confusion and behavioural changes, which are severe enough to cause loss of function. </p>
				<p> Dementia is not part of normal aging, though the elderly are more prone. Dementia occurs when the brain function gradually fails, affecting day-to-day activities. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<ul>
					<li> ​Memory loss affecting work </li>
					<li> Difficulty doing daily tasks </li>
					<li> Problems with language </li>
					<li> Confusion about time/place </li>
					<li> Poor/decreased judgement and problems with abstract thinking </li>
					<li> Losing/forgetting things </li>
					<li> Changes in personality </li>
					<li> Loss of initiative </li>
					<li> Changes in mood behaviour </li>
				<ul>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5>Causes</h5>
				<p><b> Alzheimer's Disease (AD)</b></p>
				<ul>
					<li> AD is the most common form of dementia. Changes in the brain occur gradually. Signs include short term memory, changes in judgment, reasoning and inability to perform daily tasks. </li>
				</ul>
				<p><b> Vascular Dementia (VaD) </b></p>
				<ul>
					<li> VaD is linked to strokes and may be preventable. The lack of blood circulation in the brain results in localised damage to brain areas involved in attention, planning and behaviour. </li>
				</ul>
				<p><b> Frontotemporal Dementia (FTD) </b></p>
				<ul>
					<li> In the early stages, FTD mainly affects personality, behaviour and speech. Persons with FTD may behave rashly while their memory and sense of direction remain relatively intact. </li>
				</ul>
				<h5> Risk Factors </h5>
				<ul>
					<li> Diabetes </li>
					<li> High blood pressure (Hypertension) </li>
					<li> High cholesterol (Hypercholesterolemia) </li>
					<li> Age </li>
					<li> Family history </li>
					<li> Stroke </li>
					<li> Parkinson disease </li>
				</ul>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> ​Some causes of dementia may be reversible but at present, there is no cure for the common causes such as Alzheimer's Disease and Vascular Dementia. </p>
				<p> Medications are used to manage the signs. Appropriate care facilities, behavioural therapies, counselling, and education are available to improve care for patients and their families. </p>
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