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
	<title> Jevtana </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Jevtana</b></h1>
				<h3>Jevtana - What is it for?</h3>
				<p>Jevtana is a prescription cancer medicine that interferes with the growth and spread of cancer cells in the body. It is used together with prednisone to treat prostate cancer that is resistant to medical or surgical treatments that lower testosterone and has spread to other parts of the body (metastatic).</p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				
				<h5>Warnings</h5>
				<p>You should not use Jevtana if you have severe liver disease, low white blood cell counts, or an allergy to any medicine that contains polysorbate 80.</p>
				<p>Jevtana affects your immune system. You may get infections more easily, even serious or fatal infections, especially if you are 65 or older. Call your doctor if you have a fever, muscle pain, cough, diarrhea, or pain or burning when you urinate.</p>
				
				<h5>Before taking this medicine</h5>
				<p>You should not use Jevtana if you are allergic to cabazitaxel, or if you have:</p>
				<ul>
					<li>Severe liver disease</li>
					<li>Low white blood cell counts</li>
					<li>An allergy to any medicine that contains polysorbate 80</li>
				</ul>
				
				<h5> What side effects can Jevetana cause? </h5>
				<p> It may cause serious allergic reaction. Get immediate medical help if you have signs of an allergic reaction to Jevtana, such as: </p>
				<ul>
					<li> Hives </li>
					<li> Rash </li>
					<li> Skin redness </li>
				</ul>
				<p> Other side effects include: </p>
				<ul>
					<li> Chest pain or discomfort </li>
					<li> Numbness, burning pain in hand or feet </li>
					<li> Hair loss </li>
					<li> Abdominal pain </li>
					<li> Constipation </li>
				</ul>
			</section>
			
			<section id="dosage">
				<h3>Dosage and How to Use</h3>
				<h5> How should it be used? </h5>
				<p> Jevtana is given as an infusion into a vein. A healthcare provider will give you this injection. </p>
				<p> This medicine must be given slowly, and the infusion can take about 1 hour to complete.</p>
				<p> Jevtana is usually given once every 3 weeks. You will be given other medications to prevent certain side effects. </p>
				<p> You will most likely take prednisone (a steroid medicine) by mouth every day throughout your Jevtana treatment. Do not stop taking prednisone without your doctor's advice, or you could have unpleasant side effects caused by Jevtana. Tell your doctor if you miss any doses or you stop taking prednisone for any reason. </p>
				<p> Jevtana affects your immune system. You may get infections more easily, even serious or fatal infections, especially if you are 65 or older. Your doctor will need to examine you on a regular basis. </p>
				
				<h5> What should I do if i miss a dose? </h5>
				<p> Call your doctor for instructions if you miss an appointment for your Jevtana injection. </p>
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