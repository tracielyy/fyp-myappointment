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
	<title> Nasal Polyps </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Nasal Polyps</b></h1>
				<h3>Nasal Polyps - What it is</h3>
				<p> Nasal polyps are the result of chronic inflammation within the nasal cavity. They are benign growths and are not cancerous. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> ​The polyps result in fleshy lumps within the sinuses and nasal cavity that may completely obstruct the nasal passage, giving rise to the sensation of nasal obstruction. </p>
				<p> Other symptoms include: </p>
				<ul>
					<li> Decreased sense of smell </li>
					<li> Complete loss of smell </li>
					<li> Excess nasal secretions </li>
				</ul>
				<p> If the polyps lead to obstruction of the sinuses, it may lead to sinusitis, which is an infection of the sinuses. </p>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<p> ​The exact cause is not known but any condition that causes chronic inflammation in the nasal cavity or sinuses may increase the risk of nasal polyps. </p>
				<p> There may an allergic component to the disease as some people with nasal polyposis test positive for an environmental allergen. </p>
				<p> Asthma and asprin sensitivity are other conditions that are often associated with nasal polyps. </p>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> Anyone with nasal polyps should stop smoking. Early polyps may be treated with oral or nasal medications. Other medications may include antihistamines or antibiotics to treat a chronic or recurring infection. </p>
				<p> Surgery may be necessary if medication does not shrink or eliminate the polyps, or if there is suspicion of cancerous growths. The type of surgery will depend on the extent as well as the size of the polyps. </p>
				<p> Generally, surgery is in the form of endoscopic surgery. The surgery is usually performed under general anaesthesia and involves the use of a scope inserted into the nose to help the surgeon as he guides small instruments into the nostril and sinus cavities to remove the polyps, and clear any obstruction which may prevent the flow of secretions from the sinuses. </p>
				<p> After surgery, your doctor may recommend the use of nasal steroids to prevent the recurrence of polyps as well as nasal washes to prevent the accumulation of dried mucus or crusting in the nose. </p>
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