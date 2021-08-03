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
	<title> Anaemia </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Anaemia</b></h1>
				<h3>Anaemia - What it is</h3>
				<p> Red blood cells are the cells in the blood that are responsible for carrying oxygen to the various organs. The red blood cells carry oxygen by attaching to the hemoglobin molecules. If there is any significant reduction in the blood hemoglobin level, the condition is clinically known as anaemia. A useful approach to anaemia is to understand the normal red cell life span. </p>
				<p> Production of red cells begins in the bone marrow with the mother red cells undergoing a maturation process. After the red cells have completed this process, they will be released into the bloodstream. These circulating red cells will remain in the blood for about 120 days before they are destroyed (predominantly in the spleen). Therefore, anaemia could effectively be a problem of production or a loss/destruction. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<ul>
					<li> Fatigue </li>
					<li> Shortness of breath </li>
					<li> Irregular heartbeats </li>
					<li> Dizziness or lightheadedness </li>
					<li> Cold hands and feet </li>
				</ul>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5>Causes</h5>
				<p> Red cells in the marrow can be reduced in many ways: eg. by marrow damage (ie. aplasia, infiltration by other tumors, drug induced damage), decreased stimulation (ie. renal disease, certain endocrine disorders), lack of certain nutrients (ie. iron deficiency anaemia , folate deficiency anaemia), some hereditary disorders (ie. thalassemia, sickle cell anaemia, G6PD deficiency, sideroblastic anaemia), etc. </p>
				
				<h5> Risk Factors </h5>
				<ul>
					<li> Pregnancy </li>
					<li> Chronic conditions </li>
					<li> Intestinal disorders </li>
					<li> A diet lacking in certain vitamins and minerals </li>
				</ul>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p>  Once a diagnosis is made, the aims of management are two-prong i.e. to eliminate the causative factors as well as to restore the red cell functions if possible. This would often necessitate the use of various nutritional supplements, pharmaceutical agents and/or surgical procedures. In certain situations, the physician may even need to restore the red cell functions by giving blood/red cell transfusions. </p>
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