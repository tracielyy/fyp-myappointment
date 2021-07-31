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
	<title> Viral Hepatitis </title>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
</head>
<body>
	<main>
	<script type="text/javascript" src="../js/medjs.js"></script>
		<div>
			<section id="what-it-is">
				<h1><b>Viral Hepatitis</b></h1>
				<h3>Viral Hepatitis - What it is</h3>
				<p> Viral hepatitis is caused by viruses which specifically target the liver tissue. This infection of the liver causes inflammation of the liver and subsequent damage. The type of damage and severity depends on the type of virus. </p>
				<p> Viral hepatitis can be broadly classified into two groups: acute which lasts for less than six months and chronic hepatitis which lasts for more than six months. </p>
			</section>
			
			<section id="symptom">
				<h3>Symptoms</h3>
				<p> <b>Acute hepatitis:</b> In the acute phase common symptoms are the same as that of flu which is associated with loss of appetite, nausea, vomiting, teacolored urine, yellowness of skin may develop depending on the severity of hepatitis. </p>
				<p> <b>Chronic hepatitis:</b> This may not cause any symptoms. However if chronic hepatitis persists it may lead to hardening of the liver or liver cirrhosis. Cirrhosis may progress to liver failure or liver cancer and cause jaundice, abdominal distension, swelling of legs, nausea, vomiting. Sometimes patients with chronic hepatitis may have flares of hepatitis activity causing jaundice, loss of appetite, nausea and vomiting. </p>
			</section>
			
			<section id="causes">
				<h3>Causes and Risk Factors</h3>
				<p> ​Acute hepatitis is most commonly caused by Hepatitis A,B,C and E although it can also be caused by other non-liver specific viruses. </p>
				<p> Common causes of chronic hepatitis are Hepatitis B and Hepatitis C virus. </p>
				<p> <b>Hepatitis B:</b> This is the commonest cause of chronic viral hepatitis in Singapore. It is transmitted by contact with infected blood. The common modes being transmission from mother to child during childbirth, having unprotected sex from partners infected with Hepatitis B, transfusion of infected blood products and the use of infected needles. </p>
				<p> <b>Hepatitis C:</b> This virus is most commonly transmitted by infected needles and by transfusion of infected blood products. Sexual transmission is less common. </p>
			</section>
			
			<section id="treatment">
				<h3>Treatment</h3>
				<p> Treatment of viral hepatitis depends on type of virus and also the nature of presentation. </p>
				<p> <b>Acute hepatitis:</b> The liver will recover spontaneously with supportive treatment. Liver failure may develop in rare cases. Treatment depends on the type of virus. </p>
				<p> <b>Chronic Hepatitis B:</b> Not all the patients with hepatitis B will require treatment, regular monitoring will identify patients who will need treatment to prevent damage to liver. If there is significant inflammation in the liver the physician may decide to start treatment, in some cases liver biopsy may be needed. Both oral medicines and injections are used to treat Hepatitis B. The oral medicines are lamivudine, telbivudine, adefovir,entecavir and tenofovir.</p>
				<p> <b>Chronic Hepatitis C:</b> The duration of treatment and the type of medicine required depends on the genotype of virus. The common medicines used in the treatment are Interferon and Ribavirin. </p>
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