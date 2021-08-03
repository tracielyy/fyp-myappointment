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
	<title> Gout </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Gout</b></h1>
				<h3>Gout - What it is</h3>
				<p> Gout is a form of arthritis that causes sudden, severe episodes of pain, tenderness, redness, warmth and swelling of the joints. It is the most common type of inflammatory arthritis in men over age of 40. Women are usually protected from gout until after menopause. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> An attack often occurs very suddenly with the maximum intensity of pain reached within a few hours. The joint involved can be extremely painful and is often swollen, warm and red. This rapid development of joint pain is a feature that differentiates it from most other forms of arthritis. </p>
				<p> The most common joint affected is the first joint of the big toe. Other joints that may be affected are the knee, ankle, foot, hand, wrist and elbow joints. The shoulder, hip joints and the spine are rarely affected. </p>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5>Causes</h5>
				<p> Gout is the result of deposits of needle like crystals of uric acid in the joint spaces. Uric acid, a substance that results from the breakdown of purines in the body, usually dissolves in the blood and passes through the kidneys into the urine. In people with gout, the uric acid level in the blood becomes elevated. </p>
				<p> This is called hyperuricaemia and can be due either to the increased production of uric acid eg. due to consumption of food rich in purines or decreased excretion of uric acid from the kidney eg. renal impairment. </p>
				
				<h5> Risk Factors </h5>
				<ul>
					<li> Hyperuricaemia - Most people with gout have hyperuricaemia although not all people with hyperuricaemia have gout </li>
					<li> Overweight - Excessive food intake increases the body’s production of uric acid </li>
					<li> Excessive use of alcohol - Alcohol interferes with the excretion of uric acid from the body </li>
					<li> Food with high purine content </li>
					<li> Use of certain medications such as diuretics, salicylates, cyclosporine, niacin, levodopa </li>
					<li> Start of a uric-acid lowering treatment </li>
					<li> Crash diet </li>
					<li> Joint trauma </li>
					<li> Surgery or sudden, severe illness </li>
					<li> Genes </li>
				</ul>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> The treatment of gout depends on the stage of disease. For an acute attack, the crucial step is to provide pain relief and shorten the duration of inflammation. The goal in the management of gout is to prevent recurrent or future gouty attacks with the ultimate objective of preventing joint damage. </p>
				<p> Treatment is tailored for each person and medications are used to: </p>
				<ul>
					<li> Relieve the pain and swelling during an acute episode, </li>
					<li> Prevent future episodes </li>
					<li> Prevent or treat tophi, which are nodules of crystallised uric acid formed under the skin that can become swollen and cause pain during gout attacks. </li>
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