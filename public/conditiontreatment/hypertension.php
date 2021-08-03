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
	<title> Hypertension </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Hypertension</b></h1>
				<h3>Hypertension - What it is</h3>
				<p> Hypertension, also known as high blood pressure, is one of the major risk factors for cerebrovascular disease such as stroke and coronary heart disease. </p>
				<p> Your blood pressure is determined by the amount of blood your heart pumps and the amount of resistance to blood flow in your arteries. High blood pressure indicates that the heart is working harder than it should and the arteries are under great strain. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> Hypertension usually occurs without any symptoms. However, if left untreated and uncontrolled, hypertension can lead to damage of the heart and blood vessels, and cause stroke, heart attack or kidney failure. </p>
				<p> When blood pressure is extremely high, you may experience headaches, dizziness or changes in vision. </p>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5>Causes</h5>
				<p> In most cases of hypertension in adults, there is no known cause. This type of hypertension is called primary or essential hypertension and it has usually developed over many years. </p>
				<p> In 5 to 10 percent of cases, hypertension is caused by other underlying medical conditions. </p>
				
				<h5> Risk Factors </h5>
				<ul>
					<li> Age </li>
					<li> Family history </li>
					<li> Smoking </li>
					<li> Alcohol </li>
					<li> Overweight </li>
					<li> Too much salt in diet </li>
					<li> High blood cholesterol </li>
				</ul>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> Marginally elevated blood pressure may improve with changes in lifestyle such as weight loss, more exercise and reduction in salt intake. If these measures are not successful, then drug treatment may be needed. </p>
				<p> However, once medication has started, it is essential to continue with the treatment on a long-term basis, which is likely to be life-long for most people. </p>
				<p> It is also important to complement the treatment with a healthy lifestyle. Drugs used to treat high blood pressure include: </p>
				<ul>
					<li> Diuretics </li>
					<li> Calcium channel blockers </li>
					<li> Angiotension-converting enzyme (ACE) inhibitors or Angiotensin II receptor blockers </li>
					<li> Beta blockers </li>
					<li> Alpha blockers </li>
					<li> Central acting agents </li>
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