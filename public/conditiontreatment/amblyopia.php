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
	<title> Amblyopia </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Amblyopia</b></h1>
				<h3>Amblyopia - What it is</h3>
				<p> Amblyopia, commonly known as lazy eye, is a condition where vision does not develop properly during early childhood. </p>
				<p> After the age of seven to eight years, the development of the child’s brain area that processes vision is almost complete. If the brain has not received clear images from the weak eye prior to that, it would be difficult to improve vision in that eye after the visual part of the brain development is complete. The eye is then said to be “amblyopic” or “lazy”. If left untreated, visual impairment can become permanent. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> Children with amblyopia often do not complain of poor vision, and the problem may only be detected when vision testing is done. Occasionally, parents may notice a squint (where one eye appears to be misaligned), or a droopy upper eyelid in their children. </p>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5>Causes</h5>
				<p> The main causes of amblyopia are uncorrected high refractive error (astigmatism, hyperopia, myopia), large differences in refractive power between the two eyes, and / or squint (strabismus). A minority are due to conditions that obstruct vision, such as droopy eyelids and childhood cataracts. </p>
				
				<h5> Risk Factors </h5>
				<p> Your child is at a higher risk if he or she has: </p>
				<ul>
					<li> High amounts of astigmatism, long-sightedness (hyperopia) or short-sightedness (myopia)       </li>
					<li> Large differences in spectacle power between the two eyes </li>
					<li> Obstruction of vision by congenital defects such as droopy eyelid (ptosis), cataracts or other lesions in the eye </li>
					<li> Strabismus or squints where the eye that is misaligned is not used </li>
				</ul>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> To correct amblyopia, the child needs to be encouraged to use the lazy eye. This is usually done by patching the good eye, often for several hours a day. </p>
				<p> Patching therapy may take months or even years, and is often more effective when it is started at a younger age. The basis of patching is to allow the lazy eye to be used more often that the other eye so that the lazy eye gets a chance to develop normal vision. If spectacles are required, the child must wear it at all times. </p>
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