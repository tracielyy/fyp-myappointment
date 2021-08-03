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
	<title> Bowel Incontinence </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Bowel Incontinence</b></h1>
				<h3>Bowel Incontinence - What it is</h3>
				<p> Incontinence is the loss of the ability to control gas or stool. It can vary from mild difficulty with gas control to total loss of control over liquid and solid stools. It is a common problem, but patients do not often bring it up because of embarrassment. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<ul>
					<li> Watery, loose stool (diarrhea) </li>
					<li> Stool leaks out due to physical activity/daily life exertions. </li>
					<li> Bloating and gas </li>
				<ul>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5>Causes</h5>
				<ul>
					<li> Injury during childbirth </li>
					<li> Operations, infections or trauma to the anal region </li>
					<li> Diarrhoea may be associated with loss of control due to liquid stools. </li>
				</ul>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> Mild problems may be treated with changes in your diet and constipating medications. You may be started on simple exercise that strengthens the anal muscles. </p>
				<p> In other cases, biofeedback can be used to help patients sense when stool is ready to be evacuated and help strengthen the muscles. Injuries to the anal muscles may be repaired with surgery. Diseases, which cause inflammation in the colon and rectum, such as colitis, may contribute to anal control problems. Treating these diseases also may eliminate or improve symptoms of incontinence. </p>
				<p> In the past, patients with no hope of regaining bowel control required a colostomy. This is rarely required nowadays. Artificial anal muscle, currently still under research, may soon find a place in treating patients with difficult control problems. </p>
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