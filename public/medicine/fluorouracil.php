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
	<title> Fluorouracil </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>

<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Fluorouracil</b></h1>
				<h3>Fluorouracil - What is it for</h3>
				<p> ​​​Topical fluorouracil is used on the skin to treat skin cancer and skin conditions that could become cancerous. Fluorouracil interferes with the growth of abnormal cells which are eventually destroyed. </p>
				<p> This medication is also used for other skin conditions as determined by your doctor. </p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				<h5>Warnings</h5>
				<p>Do not use if you are pregnant. Use effective birth control, and tell your doctor if you become pregnant during treatment.</p>
				
				<h5>Before taking this medicine</h5>
				<p>You should not use fluorouracil topical if you are allergic to fluorouracil, or if you have:</p>
				<ul>
					<li>A metabolic disorder called DPD (dihydropyrimidine dehydrogenase) deficiency.<li>
				</ul>
				
				<h5>What side effects can Fluorouracil cause?</h5>
				<p> ​Certain side effects of this medication are not unusual and may even disappear during treatment. If any of the following effects persist or are severe, consult your doctor. </p>
				<ul>
					<li> Redness, soreness, scaling and peeling of affected skin </li>
					<ul>
						<li> This is to be expected. It happens within 1 or 2 weeks of use and may last for several weeks even after you have stopped using the medication. Sometimes a pink, smooth area is left when the treated skin heals. This area will usually fade after 1 to 2 months. Do not stop using this medication without first checking with your doctor. </li>
					</ul>
					<li> Skin irritation, burning, pain and swelling at the site of application </li>
					<li> Enlarged lymph nodes may appear transiently </li>
					<li> This medication may make you more sensitive to the sun </li>
					<ul>
						<li> Avoid prolonged sun exposure, tanning booths, and sunlamps. Use a sunscreen and wear protective clothing when outdoors. </li>
					</ul>
				</ul>
				<p>  If you experience any of the following symptoms, you should stop your medication and see your healthcare professional immediately. </p>
				<ul>
					<li> Stomach pain </li>
					<li> Severe or bloody diarrhoea </li>
					<li> Vomiting </li>
					<li> Vision changes, eye pain or severe eye irritation </li>
					<li> Mouth sores </li>
					<li> Fever, chills, or sore throat; unexplained bruising or bleeding; feeling very tired or weak </li>
					<li> Symptoms of a drug allergy: </li>
					<ul>
						<li> Swollen face/eyes/lips/tongue </li>
						<li> Difficulty in breathing </li>
						<li> Itchy skin rashes over your whole body </li>
					</ul>
				</ul>
			</section>
			
			<section id="dosage">
				<h3>Dosage and How to Use</h3>
				<h5> How should it be used? </h5>
				<ul>
					<li> ​​​Before you apply this medication to the skin, clean the affected area and dry well. </li>
					<li> Wait for 10 minutes, then apply a small amount of medication to the affected skin. </li>
					<li> The medication should be applied in a thin layer to the affected area and its surrounding (determined by the doctor) once or twice daily or as advised by your doctor. </li>
					<li> The total area of skin treated at any one time should not be more than 23x23 cm (500 cm2) or 9x9 inches. </li>
					<li> Do not cover the area with a bandage unless directed by your doctor. </li>
				</ul>
				<h5> What should I do if i miss a dose? </h5>
				<p> ​If you miss a dose of this medication, apply it as soon as possible. However, if it is almost time for your next application, skip the missed application and carry on as before. Do not apply a double dose. </p>
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