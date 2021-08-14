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
        <html>
            <head>
                <title> Dengue Fever </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Dengue Fever</b></h1>
                            <h3>Dengue Fever - What it is</h3>
                            <p> Dengue fever is an illness caused by the dengue virus, which is carried and spread by the Aedes mosquitoes. These viruses cause the body to bleed easily and may affect other organ systems.​ </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> The usual symptoms experienced are: </p>
                            <ul>
                                <li> Fever </li>
                                <li> Headache </li>
                                <li> Muscle and joint aches </li>
                                <li> Rash - different types of rash, may be itchy and appears a few days after the onset of fever </li>
                                <li> Bleeding tendency - from nose, gums, and other parts of the body due to low platelets </li>
                                <li> Bruises from minor knocks and bumps </li>
                            </ul>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5>Causes</h5>
                            <p> Dengue fever is caused by any one of four types of dengue viruses. You can't get dengue fever from being around an infected person. Instead, dengue fever is spread through mosquito bites. </p>

                            <h5> Risk Factors </h5>
                            <ul>
                                <li> You live or travel in tropical areas.  </li>
                                <li> You have had dengue fever in the past.  </li>
                            </ul>
                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> Majority of the cases are mild and self-limiting; requiring no hospitalization. </p>
                            <h5> Bleeding Prevention & Control </h5>
                            <p> Rest in bed and reduce activities like running around and avoiding sports to reduce the risk of falls and injury, thereby preventing unnecessary bleeding. </p>
                            <h5> Symptom Relief and Fever Control </h5>
                            <ul>
                                <li> Painkillers (e.g. paracetamol) may be given to relieve pain and control fever </li>
                                <li> Avoid aspirin (or other medications such as ibuprofen and diclofenac suppositories that affect the platelet functions) </li>
                                <li> Medications may be given for nausea and vomiting </li>
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
                </main>

            </body>
        </html>
        <!-- If user not logged in, redirect user to login page -->
        <?php
    else:
        header("Location:" . LOGIN_WEB);
    endif;
else: header("Location:" . LOGIN_WEB);
endif;
?>