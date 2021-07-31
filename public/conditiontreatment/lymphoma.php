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
	<title> Lymphoma </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Lymphoma</b></h1>
				<h3>Lymphoma - What it is</h3>
				<p> A special type of white blood cell, called lymphocyte, is important for your body's resistance to disease. These cells get exposed to various substances within the body in an attempt to build the immunity. They collect and filter the substances at the Lymph nodes. Lymph nodes are found anywhere in the body, particularly in the neck, armpits, groin, above the heart, around the big blood vessels inside the abdomen. Lymphocytes may also group together in the tonsils, spleen and thymus. Lymphoma is a type of cancer that develops in the lymphocytes in any of these areas. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> ​Lymphoma commonly appears as a painless lump that persists or increases in size. A doctor should be seen if there is any painless swelling in the neck, armpits or groin with or without persistent fever, drenching sweats or unexplainable weight loss.  </p>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<p> ​There is no specific cause for lymphoma, but it has been closely associated with abnormally decreased immune systems that may be present from birth or associated with viruses such as the AIDS virus.  </p>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> Treatment of lymphoma may require chemotherapy. Chemotherapy drugs are either injected into the hand veins or swallowed as pills. Each course of treatment is given at regulated intervals to kill cancer cells and allow the body to recover.  </p>
				<p> Radiation therapy is a localised treatment using high-energy rays to kill lymphoma cells wherever the rays are directed. The area covered may just be the lymph nodes or organ involved by lymphoma or, in some cases, to a wider area encompassing the lymph nodes in the neck, chest and under both armpits. It may be given alone or combined with chemotherapy. (Biological therapy uses products that boost the body's own immune system to fight cancer. It may be used alone or combined with chemotherapy. </p>
				<p> Many new developments in the field of biological therapy are emerging. Antibodies to one type of lymphoma have been developed and may be used when conventional treatment is no longer effective. The combined treatment of high dose chemotherapy is being studied for certain patients. Here chemotherapy is given at much higher doses than standard chemotherapy treatment to kill any remaining lymphoma cells. But the high dosage also kills healthy bone marrow that produces white blood cells (infection-fighting cells), red blood cells (cells that carry oxygen), and platelets (cells that prevent bleeding). To help the patient tolerates high dose chemotherapy, stem cells or bone marrow from the patient or donor is collected beforehand. After a patient receives the chemotherapy, the stem cells or bone marrow is returned to the patient through a drip in a hand vein. </p>
				<p> The stage of the lymphoma when diagnosed and whether it is slow growing or aggressive will determine the type of treatment given. </p>
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