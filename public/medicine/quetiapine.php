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
	<title> Quetiapine </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Quetiapine</b></h1>
				<h3>Quetiapine - What is it for?</h3>
				<p>Quetiapine is an antipsychotic medicine that is used to treat schizophrenia in adults and children who are at least 13 years old.</p>
				<p> Quetiapine is used to treat bipolar disorder (manic depression) in adults and children who are at least 10 years old. </p>
				<p> Quetiapine is also used together with antidepressant medications to treat major depressive disorder in adults. </p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				
				<h5>Warnings</h5>
				<p>Some young people have thoughts about suicide when first taking an antidepressant. Stay alert to changes in your mood or symptoms. Report any new or worsening symptoms to your doctor.</p>
				<p>Quetiapine is not approved for use in older adults with dementia-related psychosis.</p>
				
				<h5>Before taking this medicine</h5>
				<p>Quetiapine may increase the risk of death in older adults with dementia-related psychosis and is not approved for this use.</p>
				<p>Quetiapine is not approved for use by anyone younger than 10 years old.</p>
				<p>Tell your doctor if you ever had:</p>
				<ul>
					<li>Liver disease</li>
					<li>Heart problems</li>
					<li>High/Low blood pressure</li>
					<li>Abnormal thyroid tests or prolactin levels</li>
					<li>Low white blood cell counts</li>
					<li>Seizure</li>
				</ul>
				
				<h5> What side effects can Quetiapine cause? </h5>
				<p> It may cause serious allergic reaction. Get immediate medical help if you have signs of an allergic reaction to Jevtana, such as: </p>
				<ul>
					<li> Hives </li>
					<li> Difficult breathing </li>
					<li> Swelling on face or throat </li>
				</ul>
				<p> Other side effects include: </p>
				<ul>
					<li> Speech Problem </li>
					<li> Dizziness </li>
					<li> Fast Heartbeats </li>
					<li> Increased Appetite </li>
					<li> Stuffy Nose </li>
				</ul>
			</section>
			
			<section id="dosage">
				<h3>Dosage and How to Use</h3>
				<h5> How should it be used? </h5>
				<p> Follow all directions on your prescription label and read all medication guides or instruction sheets. Use the medicine exactly as directed. </p>
				<p> High doses or long-term use of quetiapine can cause a serious movement disorder that may not be reversible. The longer you use quetiapine, the more likely you are to develop this disorder, especially if you are an older adult. Symptoms of this disorder include tremors or other uncontrollable muscle movements.</p>
				<p> Swallow the tablet whole and do not crush, chew, or break it. </p>
				<p> Drink plenty of liquids while you are taking quetiapine. </p>
				<p> Blood pressure may need to be checked often in a child or teenager taking quetiapine. </p>
				<p> You should not stop using quetiapine suddenly. Stopping suddenly may make your condition worse. </p>
				<p> This medicine may affect a drug-screening urine test and you may have false results. Tell the laboratory staff that you use quetiapine. </p>
				
				<h5> What should I do if i miss a dose? </h5>
				<p> Take the medicine as soon as you can, but skip the missed dose if it is almost time for your next dose. Do not take two doses at one time. </p>
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