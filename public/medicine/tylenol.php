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
	<title> Tylenol </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Tylenol</b></h1>
				<h3>Tylenol - What is it for?</h3>
				<p> Tylenol is a pain reliever and a fever reducer. </p>
				<p> Tylenol is used to treat many conditions such as headache, muscle aches, arthritis, backache, toothaches, sore throats, colds, flu, and fevers. </p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				
				<h5>Warnings</h5>
				<p>You should not use this medication if you have severe liver disease.</p>
				<p>Call your doctor at once if you have nausea, pain in your upper stomach, itching, loss of appetite, dark urine, clay-colored stools, or jaundice (yellowing of your skin or eyes).</p>
				
				<h5>Before taking this medicine</h5>
				<p>Do not take this medicine without a doctor's advice if you have ever had alcoholic liver disease (cirrhosis) or if you drink more than 3 alcoholic beverages per day.</p>
				<p>Do not give this medicine to a child younger than 2 years old without the advice of a doctor.</p>
				
				<h5> What side effects can Tylenol cause? </h5>
				<p> It may cause serious allergic reaction. Get immediate medical help if you have signs of an allergic reaction to Jevtana, such as: </p>
				<ul>
					<li> Hives </li>
					<li> Difficult breathing </li>
					<li> Swelling on face/lips/tongue/throat </li>
				</ul>
				<p> Other side effects include: </p>
				<ul>
					<li> Nausea </li>
					<li> Dark urine </li>
					<li> Stomach pain </li>
					<li> Itching </li>
					<li> Loss of appetite </li>
				</ul>
			</section>
			
			<section id="dosage">
				<h3>Dosage and How to Use</h3>
				<h5> How should it be used? </h5>
				<p> Use Tylenol exactly as directed on the label, or as prescribed by your doctor. Do not use in larger or smaller amounts or for longer than recommended. </p>
				<p> Do not take more than your recommended dose. An overdose of acetaminophen can damage your liver or cause death. </p>
				<p> If you are treating a child, use a pediatric form of Tylenol. Use only the special dose-measuring dropper or oral syringe that comes with the specific pediatric form you are using. If you do not have a dose-measuring device, ask your pharmacist for one. Carefully follow the dosing directions on the medicine label. </p>
				<p> You may need to shake the liquid before each use. Follow the directions on the medicine label. </p>
				<p> The Tylenol Meltaways chewable tablet must be chewed thoroughly before you swallow it. The tablet will soften in mouth for ease of chewing. </p>
				
				<h5> What should I do if i miss a dose? </h5>
				<p> Since Tylenol is taken as needed, you may not be on a dosing schedule. If you are taking the medication regularly, take the missed dose as soon as you remember. Skip the missed dose if it is almost time for your next scheduled dose. Do not take extra medicine to make up the missed dose. </p>
			</section>
			
			<section id="storage">
				<h3>Storage</h3>
				<h5> How should I store it? </h5>
				<ul>
					<li> Keep away from children </li>
					<li> Keep in a cool, dry place </li>
					<li> Store at room temperature </li>
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