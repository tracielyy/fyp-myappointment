<!DOCTYPE html>
<?php
$pageName = 'medicinesearch';
session_start();
/* Load Config File */
require_once '../resources/config.php';
require '../vendor/autoload.php';
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
    if (User_Type::check_user_type(User_Type::PATIENT, $user_type) || User_Type::check_user_type(User_Type::MEDICAL_PERSONNEL, $user_type) 
	|| User_Type::check_user_type(User_Type::FACILITY_ADMIN, $user_type) || User_Type::check_user_type(User_Type::SUPER_ADMIN, $user_type) ):
?>

<html>
<head>
	<!-- Title -->
	<title>Medicine</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/alphaListNav.css">
	<link rel="stylesheet" type="text/css" href="css/searchbarcss.css">
	<style>
	#searchbar{
		width: 90%;
		left: 0;
		font-size: 16px;
		padding: 12 px 20px 12px 40px;
		border: 1px solid #ddd;
		margin-bottom: 12px;
		position: relative;
	}
	
	#btnlist li{
		min-width: 43%;
		float:left;
	}
	#btnlist li a{
		text-decoration: none;
	}
	
	#btnlist li a:hover:not(.header){
		background-color: #eee;
	}
	
	#btn li a:hover{
		font-size: 30px;
		background: #f6f6f6;
	}
	
	.main{
		width: 100%;
		border-top-style: groove;
	}
	.btn{
		margin: auto;
	}
	</style>
</head>
<body>
	<!-- HTML Page Design -->
<div>
<script type="text/javascript" src="js/alphaListNav.js"></script>
	<!-- Find Medicine -->
	<div class="center row m-4">
		<div class="container col-md-10 col-lg-6 col-xl-4 col-xxl-4">
			<div class="my-5 col-sm-12">
				<div class="w-auto shadow card p-3 col-xs-12 col-lg-9">
					<div class="card-body m-1">
						<h2>Search Medicine</h2>
						<div class="px-1">
							<div class="main">
								<h5> <br>Learn about use of medicines, side effects and proper storage. </h5>
								<!--<input id="searchbar" onkeyup="search_function()" type="text" name="search" placeholder="Search by name of medicine">-->
								<input type="search" id="searchbar" onkeyup="search_function()" placeholder="Search by name of medicine">
								<div class="container">
								<h6>or, search alphabetically </h6>
									<div id="btnlist">
										<ul id="medlist" class="medlist">
										<!-- PHP to get list of medicine from medicine folder-->												
										<?php
										if($directory = opendir('medicine/')){
											while (false !== ($med = readdir($directory))){
												$displayName = str_replace("_", " ", $med);
												if($med  != "." && $med  != ".."){
													echo "<li class=\"btn\"><a href=\"/medicine/".$med."\">".ucwords(basename($displayName ,".php").PHP_EOL, " ")."</a></li>";
												}
											}
										}
										?>								
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>   	
	<!-- If user not logged in, redirect user to login page -->
	<?php
		else:
			header("Location:login.php");
		endif;
	else: header("Location:login.php");
	endif;
	?>
	<script>
		// Create new instance of AlphaListNav
		const listElem = document.getElementById('medlist');
		const cityList = new AlphaListNav(listElem, {
			includeAll: true,
			includeNums: false,
			initLetter: '',
			includeOther: true,

		});	
		
		function search_function() {
			let input = document.getElementById('searchbar').value
			input = input.toLowerCase();
			let x = document.getElementsByClassName('btn');
			  
			for (i = 0; i < x.length; i++) { 
				if (!x[i].innerHTML.toLowerCase().includes(input)) {
					x[i].style.display="none";
				}
				else {
					x[i].style.display="";                 
				}
			}
		}
	</script>
</body>
</html>