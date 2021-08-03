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
	<title> Inguinal Hernia </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Inguinal Hernia</b></h1>
				<h3>Inguinal Hernia - What it is</h3>
				<p> An inguinal hernia occurs in the groin, and is the most common type of hernia. It is a protrusion of abdominal contents (fat or intestines) through a weakness in the abdominal wall muscles, and usually presents as a groin swelling.. In men, the swelling may extend downwards into the scrotum, near the testis. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> A hernia may become more apparent or bigger when the person is standing or straining; and may disappear when lying down. It may be associated with discomfort, a pulling sensation, or even pain. If left untreated, a hernia may become larger and irreducible, which can be further complicated by obstruction or strangulation of the bowel that is being trapped inside the hernia sac. This happens when the lumen of the bowel passing through the hernia opening (neck) becomes blocked or the blood supply to the bowel inside the hernia is cut off by the tight narrowing at the hernia neck. </p>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5>Causes</h5>
				<p> There are numerous situations which trigger an inguinal hernia. They include the following:</p>
				<ul>
					<li>Being born prematurely</li>
					<li>Having a job which involves heavy lifting</li>
					<li>Having a chronic cough</li>
					<li>Persistent sneezing</li>
				</ul>
				
				<h5> Risk Factors </h5>
				<p>Potential factors that may increase your risk of Inguinal hernia includes:</p>
				<ul>
					<li>Older male</li>
					<li>Strenuous exertion</li>
					<li>Family history</li>
				</ul>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> If you suspect you have a hernia, or if you have an abdominal swelling, please see a doctor. A hernia is usually diagnosed by physical examination of the abdomen. For a less obvious swelling, an ultrasound or a CT scan may help confirm the diagnosis. X-rays or a CT scan may also be performed to look for acute complications such as bowel obstruction or strangulation. Surgery may be advised to prevent or treat the complications. </p>
				<p> Surgery can be performed to repair a hernia and prevent complications from occurring. This involves returning the abdominal contents to the abdominal cavity, and reinforcing the weakened area of the abdominal wall. This is usually done with an insertion of a mesh over the weakened area. The mesh causes the body to form strong scar tissue in the region it is placed, strengthening the abdominal wall in the region. </p>
				<p> Hernia repair surgery may be performed by incising over the length of the abdominal hernia in an open approach, or via a laparoscopic (keyhole) approach where a camera and instruments are inserted through 5-10mm incisions into the abdominal cavity. Procedural time may vary, depending on the size and complexity of your hernia. The details of hernia surgery will be discussed when you consult a surgeon. </p>
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