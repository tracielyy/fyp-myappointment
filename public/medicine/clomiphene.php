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
	<title> Clomiphene </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>

<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Clomiphene</b></h1>
				<h3>Clomiphene - What is it for</h3>
				<p> ​Clomiphene is used as a fertility medication in some women who are unable to become pregnant. They work by changing the hormone balance of the body, causing ovulation to occur and thus prepares the body for conception. </p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				<h5> Warnings </h5>
				<p>Do not use clomiphene if you are already pregnant.</p>
				<p>You should not use clomiphene if you have: liver disease, unexplained abnormal vaginal bleeding, an uncontrolled adrenal gland or thyroid disorder, an ovarian cyst (unrelated to polycystic ovary syndrome), or if you are pregnant.</p>
				
				<h5>Before taking this medicine</h5>
				<p>You should not use clomiphene if you are allergic to it, or if you have:</p>
				<ul>
					<li>Past or present liver disease</li>
					<li>An ovarian cyst that is not related to polycystic ovary syndrome</li>
					<li>A pituitary gland or other brain tumor</li>
					<li>If you are pregnant</li>
					<li>An untreated or uncontrolled problem with your thyroid or adrenal gland</li>
				</ul>
				
				<h5> What side effects can Clomiphene cause? </h5>
				<ul>
					<li> ​Bloating, nausea and/or vomiting </li>
					<ul>
						<li> Take the medication after food to reduce these side effects </li>
					</ul>
					<li> Hot flushes </li>
					<ul>
						<li> Wear loose fitting clothes or go to areas with air-conditioning </li>
					</ul>
					<li> Dizziness/light-headedness and tiredness </li>
					<ul>
						<li> Avoid activities that require mental alertness and avoid using heavy machineries. </li>
					</ul>
					<li> Headache, muscle aches or joint pains </li>
					<ul>
						<li> You may take paracetamol to relieve such symptoms. </li>
					</ul>
				</ul>
				<p> Rare but serious side effects include blood clot formation. However, blood clot formation is rare and the doctor would have evaluated and weighed its benefits over risks before making the recommendation. </p>
				<p> The symptoms of blood clot include one or more of the following: </p>
				<ul>
					<li> Any unusual sudden cough, breathlessness or difficulty in breathing </li>
					<li> Severe pain in the chest which may reach the left arm </li>
					<li> Severe pain in legs or swelling in either of your legs </li>
					<li> Weakness or numbness in any part of your body </li>
					<li> Change in your speech, including slurring of words </li>
					<li> Change in your senses of hearing, smell or taste </li>
					<li> Vision changes such as loss of vision/ blurred vision/ spots/ flashes </li>
				</ul>
				<p> Other rare but serious side effects that you may or may not experience </p>
				<ul>
					<li> Dark urine or light coloured stools, nausea, vomiting, loss of appetite, stomach pain, yellowing of your eyes or skin </li>
					<li> Ovaries get more stimulated, which can cause very bad stomach pain </li>
					<li> Sudden and severe headaches </li>
				</ul>
				<p> The symptoms of a drug allergy include one or more of the following </p>
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
					<li> ​Take this medication only as directed by your doctor. </li>
					<li> This medication is usually taken when you start having your full flow menstruation and after obtaining a negative pregnancy test.) </li>
					<li> Take for 5 days (normally from Day 2 to Day 6 of menstruation), preferably at <u>the same time each day.</u> </li>
					<li> You can take this medication with or without food. However, if you experience any stomach discomfort after taking the medication on an empty stomach, take it after food. </li>
	
				</ul>
				<h5> What should I do if i miss a dose? </h5>
				<p> ​​If you forget to take a dose, take it as soon as you remember. Then take your next dose at the usual time. Do not take two doses to make up for the missed dose. </p>
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