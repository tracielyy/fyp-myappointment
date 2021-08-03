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
	<title> Cataract </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Cataract</b></h1>
				<h3>Cataract - What it is</h3>
				<p> Cataract is a condition when the natural lens in your eye becomes progressively cloudy. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> The most common symptoms of a cataract are: </p>
				<ul>
					<li> Cloudy or blurry vision </li>
					<li> Colours seem faded </li>
					<li> Poor night vision </li>
					<li> Frequent prescription changes in your eyeglasses or contact lenses (“power” keeps changing) </li>
					<li> Glare and haloes </li>
				<ul>
				<p> These symptoms can also be a sign of other eye problems. If you have any of these symptoms, check with your ophthalmologist. </p>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5>Causes</h5>
				<ul> 
					<li> Ageing </li>
					<li> Smoking </li>
					<li> UV radiation (sunlight) exposure </li>
					<li> Poorly controlled diabetes </li>
					<li> Take certain types of medication such as corticosteroid, etc. </li>
					<li> Are born with it (congenital) Experienced previous trauma to the eye </li>
				</ul>	
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<h5> How is cataract surgery performed </h5>
				<p> Cataract surgery is the main surgery performed at SNEC. The main technique used to remove a cataract is phacoemulsification. </p>
				<p> In phacoemulsification: </p>
				<ul>
					<li> A small opening between 1.8mm and 2.75mm is first created on the cornea. </li>
					<li> An ultrasonic device is then introduced through this opening into the eye. This device breaks the cloudy lens up into small pieces and facilitates removal from the eye. </li>
					<li> After the cataract lens is entirely removed, an artificial lens implant is inserted to the same position. </li>
					<li> Most of the time, the wound does not require any stitching. </li>
					<li> This method of cataract surgery takes less than 30 minutes. </li>
					<li> This is a day surgery procedure hence there is no need to stay overnight in the hospital. </li>
					<li> An anaesthetist is with you during surgery to provide sedation that will help you feel comfortable and relaxed during the surgery. You may not be totally asleep during surgery, but will remain comfortable. Anaesthetic eye drops and injections will be given to minimise pain. It is important to cooperate by not talking or moving your head and body during the surgery.  </li>
				</ul>
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