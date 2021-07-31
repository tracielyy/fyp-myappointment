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
	<title> Cerebral Aneurysm </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Cerebral Aneurysm</b></h1>
				<h3>Cerebral Aneurysm - What it is</h3>
				<p> A cerebral (brain) aneurysm is a bulging or ballooning out of a part of a blood vessel wall due to a weak point in the latter’s wall. As the aneurysm grows, the vessel wall becomes thinner and weaker. It can become so thin that it spontaneously leaks or ruptures, releasing blood into the space around the brain called the subarachnoid space. This results in a subarachnoid haemorrhage (SAH). Blood can also leak into the cerebrospinal fluid (brain fluid) or into the brain substance itself, resulting in an intracerebral haematoma (blood clot). This blood can irritate, damage or destroy nearby brain cells. In more serious cases, the bleeding may cause brain damage, paralysis, coma or even death. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> Most people with brain aneurysms do not experience any symptoms prior to the onset of the rupture. The rupture occurs suddenly. Up to 40% of the people experience ‘sentinel headaches’ days to weeks before the rupture and these are thought to be ‘warning leak symptoms’. At the time of aneurysm rupture, the following may occur: </p>
				<ul>
					<li> Sudden onset of severe headache (often described as the "worst" headache of their lives) </li>
					<li> Stiff neck </li>
					<li> Nausea and vomiting </li>
					<li> Vision and speech impairment </li>
					<li> Numbness and weakness in any part of the body </li>
					<li> Seizure </li>
					<li> Sensitivity to light </li>
					<li> Loss of consciousness </li>
				<ul>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5>Causes</h5>
				<p> Aneurysms can occur in all age groups, with the peak age of presentation of a ruptured aneurysm between 50-60 years. Women have a higher incidence of this occurrence than men. </p>
				<p> The exact mechanisms by which aneurysms develop are still not fully understood. Previously thought to be congenital defects in the vessel wall, there is little evidence for such inherited weakness. It is now thought to be a degenerative condition with a number of contributory factors. </p>
				
				<h5> Risk Factors </h5>
				<ul>
					<li> Age: There is a higher incidence with increasing age </li>
					<li> Smoking </li>
					<li> Atherosclerosis: Asian’s have a higher incidence of intracranial atherosclerosis (build-up of fatty deposits in the brain arteries) which weakens the vessels wall </li>
					<li> Excessive alcohol consumption </li>
					<li> Recreational drug use (e.g. cocaine) </li>
					<li> High blood pressure </li>
					<li> Injury or trauma to blood vessels </li>
				</ul>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> In the management of a patient with a ruptured aneurysm, the immediate goal is to prevent a second bleed as re-bleeding has a 60-80% risk of death and severe disability. The risk of a re-bleed is approximately 1.5% per day, reaching a cumulative risk of 20% at the end of the first 2 weeks, and 50-60% at the end of 6 months. </p>
				<p> The best treatment option is often individualised, depending on the site, shape and location of the aneurysm. The patient’s age and clinical condition are also factors to consider. </p>
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