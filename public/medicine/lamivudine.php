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
	<title> Lamivudine </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>

<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Lamivudine</b></h1>
				<h3>Lamivudine - What is it for</h3>
				<p> ​Lamivudine is an antiretroviral agent that blocks construction of virus. It is used to decrease the amount of viruses (viral load) to as low as possible, for as long as possible. </p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>
				
				<h5>Warnings</h5>
				<p>You should not take Epivir-HBV (for treating hepatitis B) if you also take other medicine that contains lamivudine or emtricitabine.</p>
				<p>Lamivudine can cause severe or life-threatening effects on your liver or pancreas. Call your doctor at once if you have: severe pain in your upper stomach spreading to your back, nausea, loss of appetite, dark urine, clay-colored stools, or jaundice (yellowing of the skin or eyes).</p>
				<p>If you've ever had hepatitis B, it may become active or get worse after you stop using lamivudine. You may need frequent liver function tests for several months.</p>
				
				<h5>Before taking this medicine</h5>
				<p>You should not take Epivir-HBV (for treating hepatitis B) if you also take other medicine that contains lamivudine or emtricitabine, which includes Atripla, Biktarvy, Cimduo, Combivir, Complera, Descovy, Emtriva, Epzicom, Genvoya, Odefsey, Stribild, Symfi, Triumeq, Trizivir, and Truvada.</p>
				<p>Tell your doctor if you have ever had:</p>
				<ul>
					<li>Liver disease (especially hepatitis B or C, or a liver transplant)</li>
					<li>Pancreatitis</li>
					<li>Kidney disease</li>
					<li>Diabetes</li>
				</ul>
				
				<h5> What side effects can Lamivudine cause? </h5>
				<p> Common side effects are: </p>
				<ul>
					<li> Nausea and vomiting. This can be prevented by eating small frequent meals or sucking on candy. </li>
					<li> Stomach discomfort. Take the medicine after food to reduce gastric discomfort. </li>
					<li> Headache and pain. Mild painkillers (e.g. paracetamol) can be taken to reduce the pain. </li>
					<li> Fatigue/tiredness </li>
					<li> Diarrhea </li>
					<li> Skin rash </li>
				</ul>
				<p> Inform your doctor if any of the above side effects lasts for more than a few days or if they become serious or bothersome. </p>
				<p> <u>Rare but serious side effects</u> may manifest as the following symptoms. Contact your doctor as soon as possible, if you notice any of the following: </p>
				<ul>
					<li> Numbness or tingling sensations of fingers, toes or feet </li>
					<li> Visual changes </li>
				</ul>
				<p> Inform your doctor if you notice any other unusual symptoms. Always discuss with your doctor or pharmacist if you or your child has any problems or difficulties during or after taking Lamivudine. </p>
			</section>
			
			<section id="dosage">
				<h3>Dosage and How to Use</h3>
				<p> Missing doses makes Lamivudine less effective and may also make the virus resistant to Lamivudine and other possible antiretroviral agents. </p>
				<p> If you forgot take the medication within 4 hours, administer it as soon as you remember and then continue to take it as per normal. Otherwise, skip the missed dose and administer the next dose at the usual time. Do not double or increase the dose. </p>
				<p> If you or your child vomits within 15 minutes of administration, give another dose if possible. </p>
			</section>
			
			<section id="storage">
				<h3>Storage</h3>
				<h5> How should I store it? </h5>
				<ul>
					<li> Keep away from children </li>
					<li> Keep in a cool, dry place, away from direct sunlight </li>
					<li> Store at room temperature </li>
					<li> Discard the oral solution one month after opening. </li>
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