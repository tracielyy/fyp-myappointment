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
	<title> Indapamide </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>

<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Indapamide</b></h1>
				<h3>Indapamide - What is it for</h3>
				<p> ​Indapamide belongs to a class of medications called Diuretics. It works on your kidneys to get rid of extra water and salts in your body. </p>
				<p> This medication is used to remove excess fluid from your body. This will decrease the pressure on the blood vessels and allow the heart to work less hard. Hence, it may also be used to treat high blood pressure. </p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				
				<h5>Warnings</h5>
				<p>You should not use this medicine if you are allergic to sulfa drugs or if you are unable to urinate.</p>
				
				<h5>Before taking this medicine</h5>
				<p>You should not use indapamide if you are allergic to it, or if:</p>
				<ul>
					<li>You are unable to urinate; or</li>
					<li>You are allergic to sulfa drugs.</li>
				</ul>
				
				<h5> What side effects can Indapamide cause? </h5>
				<ul>
					<li> Passing urine more often, dry mouth, increased thirst </li>
					<ul>
						<li> Too much water loss may result in dehydration. Inform your doctor if you are always feeling thirsty, tired, or you have muscle cramps. </li>
						<li> Drinking excessive amounts of water to replace your water loss or because you feel thirsty may lead to your heart condition becoming worse. Discuss with your healthcare professional about the amount of fluids you may take each day. </li>
					</ul>
					<li> Dizziness and lightheadedness </li>
					<ul>
						<li> This may occur especially when you get up quickly from lying or sitting down. Getting up slowly or changing posture slowly may help. Take a rest by sitting or lying down if you feel dizzy. </li>
					</ul>
					<li> Affect your mineral levels in your blood </li>
					<ul>
						<li> This medication may cause potassium loss. Inform your healthcare professional if you experience symptoms of excessive potassium loss such as weakness/ tiredness, muscle cramps, loss of appetite or irregular/ fast heart beat </li>
					</ul>
				</ul>
				<p> Inform your healthcare professional if these side effects do not go away and become bothersome to you. </p>
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
					<li> ​Do not stop taking your medication without first checking with your healthcare professional. </li>
					<li> If you are taking one dose a day, take it in the morning. If you are taking the medication two times a day, take the second dose before 4pm so that you do not need to wake up often at night to pass urine. </li>
				</ul>
				<h5> What should I do if i miss a dose? </h5>
				<p> ​​​If you miss a dose, take the missed dose as soon as you remember. If it is almost time for your next dose, take only the usual dose. Do not double your dose or use extra medication to make up for the missed dose.  </p>
			</section>
			
			<section id="storage">
				<h3>Storage</h3>
				<h5> How should I store it? </h5>
				<ul>
					<li> Keep away from children </li>
					<li> Keep in a cool, dry place, away from direct sunlight </li>
					<li> Throw away all expired medications </li>
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