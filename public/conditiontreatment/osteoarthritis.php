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
	<title> Osteoarthritis </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Osteoarthritis</b></h1>
				<h3>Osteoarthritis - What it is</h3>
				<p> The most common form of arthritis, Osteoarthritis (OA) affects an estimated 40% of the adult population. Of these, only 10% seek medical advice and only 1% are severely disabled. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> In OA, you will have no problem in the morning on arising but as the day progresses your discomfort will increase. </p>
				<p> In the evening, there will be a dull ache in the area of the affected joint. </p>
				<p> Other symptoms include: </p>
				<ul>
					<li> Pain </li>
					<li> Swelling of the affected joints </li>
					<li> Changes in surrounding joints </li>
					<li> Warmth - The arthritic joint may feel warm to the touch </li>
					<li> Crepitation - A sensation of grating or grinding in the affected joint caused by the rubbing of damaged cartilage surfaces </li>
					<li> Cysts - In OA of the hand, small cysts may develop, which may cause the ridging or dents in the nail plate of the affected finger </li>
				</ul>
				<p> The changes associated with degenerative arthritis tend to involve similar joints. Whereas in post-traumatic degenerative arthritis where there is a history of acute or chronic trauma, the changes tend to be isolated to the specific joints injured. </p>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5> Causes </h5>
				<p> ​Osteoarthritis (OA) means inflammation of the joints although it is better known as a degenerative disease due to the inflammation of the joints with thinning of the articular cartilage. The cartilage in our joints allows for the smooth movement of joints. When it becomes damaged due to injury, infection or gradual effects of ageing, joints movement is hindered. As a result, the tissues within the joint become irritated causing pain and swelling within the joint. </p>
				
				<h5>Risk Factors</h5>
				<p>Factors that can increase your risk of osteoarthritis include:</p>
				<ul>
					<li> Old age </li>
					<li> Gender </li>
					<li> Previous joint injury </li>
					<li> Weight </li>
					<li> Bone deformities </li>
					<li> Other diseases that affect the joints </li>
					<li> Genetics </li>
				</ul>
				</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> The goals for treatment for osteoarthritis are: </p>
				<ul>
					<li> Pain relief </li>
					<li> Maintenance of function </li>
					<li> Prevention of associated deformities </li>
					<li> Patient education </li>
				</ul>
				<p> The treatment for OA depends on the severity of the disease and the patient’s own lifestyle expectations. </p>
				<p> Early cases of OA can generally be treated with: </p>
				<ul>
					<li> Rest and lifestyle modification, such as weight loss and cessation of smoking </li>
					<li> Use of aid (e.g. a walking stick). Use of good shoes is also helpful for relieving symptoms in some cases of OA </li>
					<li> Exercise and physiotherapy to strengthen muscles and improve joint flexibility </li>
					<li> Medication </li>
				</ul>
				<p> In OA of the hand, rest can be accomplished by selectively immobilising the joint in a splint. Splinting is initially done for a period of 3 - 4 weeks, during which the splint is worn continuously. </p>
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