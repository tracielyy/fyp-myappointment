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
	<title> Kevzara </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/listcss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-is-it-for">
				<h1><b>Kevzara</b></h1>
				<h3>Kevzara - What is it for?</h3>
				<p>Kevzara reduces the effects of a substance in the body that can cause inflammation.</p>
				<p> Kevzara is used to treat moderate to severe rheumatoid arthritis in adults. Itis sometimes given together with other arthritis medicines. </p>
				<p> Kevzara is usually given after other medications have been tried without successful treatment of symptoms. </p>
			</section>
			
			<section id="side-effect">
				<h3>Side Effects, Warnings</h3>

				<h5>Warnings</h5>
				<p>Kevzara affects your immune system. You may get infections more easily, even serious or fatal infections. Tell your doctor if you have a fever, chills, tiredness, cough, diarrhea, stomach pain, weight loss, skin sores, or painful urination.</p>
				<p>Kevzara may cause you to have a tear in your stomach or intestines. This is more likely if you have diverticulitis or a stomach ulcer, or if you also take steroids, methotrexate, or an NSAID (nonsteroidal anti-inflammatory drug). Call your doctor right away if you have a fever and ongoing stomach pain.</p>
				<p>Before and during your treatment with Kevzara, you will need frequent blood tests. Your treatment may be delayed or stopped based on the results of these tests.</p>
				
				<h5>Before taking this medicine</h5>
				<p>You should not use Kevzara if you are allergic to sarilumab.</p>
				<p>Tell your doctor if you have ever had tuberculosis or if anyone in your household has tuberculosis. Also tell your doctor if you have recently traveled. Tuberculosis and some fungal infections are more common in certain parts of the world, and you may have been exposed during travel.</p>
				<p>To make sure Kevzara is safe for you, tell your doctor if you have ever had:</p>
				<ul>
					<li>An active or chronic infection</li>
					<li>Diabetes</li>
					<li>A weak immune system</li>
					<li>Hepatitis or other liver problems</li>
					<li>Cancer</li>
					<li>HIV or AIDS</li>
				</ul>
				
				<h5> What side effects can Kevzara cause? </h5>
				<p> It may cause serious allergic reaction. Get immediate medical help if you have signs of an allergic reaction to Jevtana, such as: </p>
				<ul>
					<li> Hives </li>
					<li> Chest pain </li>
					<li> Difficult breathing </li>
				</ul>
				<p> Other side effects include: </p>
				<ul>
					<li> Fever </li>
					<li> Short of breath </li>
					<li> Body ache </li>
					<li> Abdominal pain </li>
					<li> Diarrhea </li>
				</ul>
			</section>
			
			<section id="dosage">
				<h3>Dosage and How to Use</h3>
				<h5> How should it be used? </h5>
				<p> Kevzara is injected under the skin, usually given once every 2 weeks. A healthcare provider may teach you how to properly use the medication by yourself. </p>
				<p> Follow all directions on your prescription label and read all medication guides or instruction sheets. Use the medicine exactly as directed. </p>
				<p> Read and carefully follow any Instructions for Use provided with your medicine. Ask your doctor or pharmacist if you don't understand all instructions. </p>
				<p> Prepare an injection only when you are ready to give it. Do not use if the medicine looks cloudy or has particles in it. Call your pharmacist for new medicine. </p>
				<p> Kevzara affects your immune system. You may get infections more easily, even serious or fatal infections. Your doctor will need to examine you on a regular basis. </p>
				<p> Before injecting your dose, take the medicine out of the refrigerator and let it reach room temperature (for 30 minutes if using the prefilled syringe, or for 60 minutes if using the injection pen). Once at room temperature, the medicine must be used within 14 days. </p>
				<h5> What should I do if i miss a dose? </h5>
				<p> Call your doctor for instructions if you miss a dose. </p>
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