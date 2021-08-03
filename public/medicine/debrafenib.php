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
	<title> Dabrafenib </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>

<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Dabrafenib</b></h1>
				<h3>Dabrafenib - What is it for</h3>
				<p> ​Dabrafenib is used to treat melanoma (a type of skin cancer) or non-small cell lung cancer that has spread to other parts of the body. It is often given with another medication called Trametinib. </p>
				<p> Your doctor will check for a gene mutation before starting you on this. </p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				<h5> Warnings </h5>
				<p>Using dabrafenib with trametinib may increase your risk of developing a certain type of skin cancer. Ask your doctor about your specific risk. Tell your doctor if you notice any new skin symptoms.</p>
				
				<h5>Before taking this medicine</h5>
				<p>You should not use dabrafenib if you are allergic to it.</p>
				<p>Inform your doctor if you have ever had:</p>
				<ul>
					<li>Bleeding problems</li>
					<li>Heart disease</li>
					<li>Liver or kidney disease</li>
					<li>Diabetes</li>
				</ul>
				<h5> What side effects can Dabrafenib cause? </h5>
				<ul>
					<li> ​Dry skin, rash and itch </li>
					<ul>
						<li> Moisturize your skin daily. </li>
						<li> Avoid too much exposure to the sun as it may make the rash worse. Use sunscreen and covered clothing if you need to be under the sun for a long period of time. </li>
					</ul>
					<li> Diarrhea </li>
					<ul>
						<li> Drink plenty of clear fluids to replace those lost (2 litres everyday). </li>
						<li> Avoid oily or spicy food and milk or dairy products. </li>
					</ul>
					<li> Nausea (especially when taken together with Trametinib) </li>
					<ul>
						<li> Take small, frequent meals throughout the day. </li>
					</ul>
					<li> Feeling tired and lack of energy </li>
					<ul>
						<li> Do not drive or operate machinery when you feel tired. </li>
					</ul>
					<li> Joint or muscle ache </li>
					<ul>
						<li> Apply a warm compress to the area that aches. </li>
					</ul>
					<li> Mild fever </li>
					<ul>
						<li> Drink more water to help cool your body down. </li>
						<li> Place a cold towel on your forehead. </li>
					</ul>		
					<li> Hair loss </li>
				</ul>
				<p><b> Please see your healthcare professional immediately if any of the following occur: </b></p>
				<ul>
					<li> Fast heartbeat, chest pain, or unusual weakness, tiredness or light-headedness </li>
					<li> Sudden onset of cough or shortness of breath </li>
					<li> Fever of 38° C and above, especially with chills, pain or difficulty in passing urine </li>
					<li> Unusual bleeding, bruising or black sticky stools </li>
					<li> Dark urine or light coloured stools, nausea, vomiting, loss of appetite, stomach pain, yellowing of your eyes or skin </li>
					<li> Very bad pain around the stomach area </li>
					<li> Changes in eyesight or eye pain </li>
					<li> New skin lesions </li>
					<li> Redness, swelling, tenderness or peeling of skin on hands or feet </li>
					<li> Symptoms of a drug allergy including one or more of the following: </li>
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
					<li> Take Dabrafenib two times a day, space about 12 hours between each dose </li>
					<li> Take it on an empty stomach, at least 1 hour before or 2 hours after food. </li>
					<li> Do not break open or crush the capsule. Swallow the capsule whole. </li>
					<ul>
						<li> Inform your doctor or pharmacist if you have difficulty swallowing. </li>
					</ul>
				</ul>
				<h5> What should I do if i miss a dose? </h5>
				<p> If you forget to take a dose, take it as soon as you remember. However if it is less than 6 hours to your next dose, skip the missed dose and take your next dose at the usual time. Do not take two doses at the same time. </p>
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