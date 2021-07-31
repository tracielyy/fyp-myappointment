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
	<title> Epilepsy </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Epilepsy</b></h1>
				<h3>Epilepsy - What it is</h3>
				<p> ​A seizure is an abnormal electrical discharge of a group of brain cells. It can cause different symptoms, depending on the location of the seizure and the spread of the electrical activity through the brain. </p>
				<p> A person has epilepsy when he/she has more than one episode of seizures, or has a high risk of having recurrent seizures. </p>
				<p> People who suffered a stroke, brain injury, infection or tumour can have epilepsy. In around half the cases, a cause cannot be found. </p>
				<p> Triggers of seizures in patients with epilepsy include stress, lack of sleep, menstruation, concurrent infection and skipping medications. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> ​There are 2 main types: </p>
				<h5> Focal Seizures </h5>
				<ul>
					<li> Usually affects only one part of the body </li>
					<li> Results in sensory, motor or autonomic disturbances </li>
					<li> The patient may be conscious or unconscious </li>
				</ul>
				<h5> Generalised Seizures </h5>
				<ul>
					<li> May start as a focal seizure and spread throughout the whole brain </li>
					<li> Loss of consciousness usually lasting 30 seconds to 5 minutes </li>
					<li> Usually rhythmic muscle jerking lasting 1 to 2 minutes </li>
					<li> May cause tongue biting, incontinence and difficulty in breathing </li>
				</ul>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5>Causes</h5>
				<p> Epilepsy has no identifiable cause in about half the people with the condition. In the other half, the condition may be traced to various factors, including:</p>
				<ul>
					<li> <b>Head trauma.</b> Head trauma as a result of a car accident or other traumatic injury can cause epilepsy.</li>
					<li> <b>Genetic influence.</b> Some types of epilepsy, which are categorized by the type of seizure you experience or the part of the brain that is affected, run in families. In these cases, it's likely that there's a genetic influence. </li>
					<li> <b>Developmental disorders.</b> Epilepsy can sometimes be associated with developmental disorders, such as autism and neurofibromatosis.</li>
				</ul>
				<h5> Risk Factors </h5>
				<p> Common Risk Factors: </p>
				<ul>
					<li> <b>Age.</b> The onset of epilepsy is most common in children and older adults, but the condition can occur at any age.  </li>
					<li> <b>Family History.</b> If you have a family history of epilepsy, you may be at an increased risk of developing a seizure disorder. </li>
					<li> <b>Head Injuries.</b> Head injuries are responsible for some cases of epilepsy.</li>
					<li> <b>Dementia.</b> Dementia can increase the risk of epilepsy in older adults. </li>
				</ul>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> Anti-epileptic medications are the first-line of treatment. Different types of medication may be prescribed. The more common side effects include sleepiness and dizziness. </p>
				<p> Patients with focal seizures and are not responding to medications may consider surgery. </p>
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