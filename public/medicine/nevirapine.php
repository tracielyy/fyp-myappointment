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
	<title> Nevirapine </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>

<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Nevirapine</b></h1>
				<h3>Nevirapine - What is it for</h3>
				<p> Nevirapine is an antiretroviral agent that blocks construction of virus. It is used to decrease the amount of viruses (viral load) to as low as possible, for as long as possible. </p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				
				<h5>Warnings</h5>
				<p>Do not use this medicine if you've ever had muscle movement problems after using metoclopramide or similar medicines, or if you've had a movement disorder called tardive dyskinesia. You also should not use this medicine if you've had stomach or intestinal problems (a blockage, bleeding, or a hole or tear), epilepsy or other seizure disorder, or an adrenal gland tumor (pheochromocytoma).</p>
				<p>Before you take metoclopramide, tell your doctor if you have kidney or liver disease, congestive heart failure, high blood pressure, diabetes, Parkinson's disease, or a history of depression.</p>
				<p>Do not drink alcohol. It can increase some of the side effects of metoclopramide.</p>
				
				<h5>Before taking this medicine</h5>
				<p>You should not use metoclopramide if you are allergic to it, or if you have:</p>
				<ul>
					<li>Tardive dyskinesia (a disorder of involuntary movements)</li>
					<li>Stomach or intestinal problems such as a blockage, bleeding, or perforation (a hole or tear in your stomach or intestines)</li>
					<li>Epilepsy or other seizure disorder</li>
					<li>Adrenal gland tumor (pheochromocytoma)</li>
				</ul>
				
				<h5> What side effects can Nevirapine cause? </h5>
				<ul>
					<li> Rashes </li>
					<li> Nausea and vomiting. This can be prevented by eating small frequent meals or sucking on candy. </li>
					<li> Stomach discomfort. Take the medicine after food to reduce gastric discomfort. </li>
					<li> Headache and pain. Mild painkillers (e.g. paracetamol) can be taken to reduce the pain. </li>
					<li> Diarrhea/loose stools </li>
				</ul>
				<p> Inform your doctor if the side effects become severe and bothersome. Report to your doctor immediately if you or your child experience severe abdominal pain, jaundice or rashes. </p>
			</section>
			
			<section id="dosage">
				<h3>Dosage and How to Use</h3>
				<p> Nevirapine is usually taken 2 to 3 times daily. It is usually given in combination with at least two other antiretroviral agents, to achieve significant decrease in viral multiplication. It may be started at a low dose and increased after 14 days. </p>
				<p> Nevirapine may be administered with or without food. </p>
			</section>
			
			<section id="storage">
				<h3>Storage</h3>
				<h5> How should I store it? </h5>
				<ul>
					<li> Keep away from children </li>
					<li> Keep in a cool, dry place, away from direct sunlight </li>
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