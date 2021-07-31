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
	<title> Urinary Tract Infections </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Urinary Tract Infections</b></h1>
				<h3>Urinary Tract Infections - What it is</h3>
				<p> Urinary tract infections occur when bacteria is present within the urinary tract in significant numbers. UTIs are common in women, with 1 in 5 adult women aged 20-65 experiencing a UTI at least once a year. Approximately 50% of women will experience UTIs at least once in their life. </p>
				<p> Cystitis (bladder infection) makes up the majority of these infections. Involvement of the upper urinary tract (pyelonephritis) is less common compared to that of cystitis but can be associated with more serious complications. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> The onset of UTI can be associated with one or more of the following symptoms: </p>
				<ul>
					<li> Pain on passing urine (dysuria) </li>
					<li> Urinary urgency </li>
					<li> Urinary frequency </li>
					<li> Sensation of bladder fullness or lower abdominal discomfort </li>
					<li> Fever </li>
					<li> Blood in the urine (haematuria) </li>
					<li> Flank pain and tenderness over the lower back area next to the spine (may suggest involvement of the upper urinary tract) </li>
				</ul>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<h5>Causes</h5>
				<p>Urinary tract infections typically occur when bacteria enter the urinary tract through the urethra and begin to multiply in the bladder. Although the urinary system is designed to keep out such microscopic invaders, these defenses sometimes fail. When that happens, bacteria may take hold and grow into a full-blown infection in the urinary tract.</p>
				<p>The most common UTIs occur mainly in women and affect the bladder and urethra.</p>
				<ul>
					<li><b>Infection of the bladder (cystitis).</b> This type of UTI is usually caused by Escherichia coli (E. coli), a type of bacteria commonly found in the gastrointestinal (GI) tract. However, sometimes other bacteria are responsible.</li>
					<li><b>Infection of the urethra (urethritis).</b> This type of UTI can occur when GI bacteria spread from the anus to the urethra.</li>
				</ul>
				
				<h5>Risk Factors</h5>
				<p>Urinary tract infections are common in women, and many women experience more than one infection during their lifetimes. Risk factors specific to women for UTIs include:</p>
				<ul>
					<li><b>Menopause.</b> After menopause, a decline in circulating estrogen causes changes in the urinary tract that make you more vulnerable to infection.</li>
					<li><b>Female anatomy.</b> A woman has a shorter urethra than a man does, which shortens the distance that bacteria must travel to reach the bladder.</li>
					<li><b>Certain types of birth control.</b> Women who use diaphragms for birth control may be at higher risk, as well as women who use spermicidal agents.</li>
				</ul>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> Empirical antibiotics are usually prescribed for UTIs. The patient may be prescribed alternative antibiotics after the urine culture results are available. The duration of treatment of the UTI depends on the antibiotic in use. Some common first-choice agents for the treatment of uncomplicated cystitis in women include nitrofuratoin, bactrim or beta-lactams such as cephalexins. </p>
				<p> Most patients can be treated on an outpatient basis. However, hospital admission for management of complicated UTIs may be indicated in some patients. Complicating factors include the presence of structural abnormalities (e.g. stones, indwelling catheters), metabolic disease (e.g. diabetes, pre-existing kidney disease) or patients who are immunosuppressed and therefore more prone to serious infections (e.g. HIV, patients on chemotherapy). </p>
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