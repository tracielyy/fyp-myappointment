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
                <title> Omeprazole </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/listcss.css">
            </head>

            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-is-it-for">
                            <h1><b>Omeprazole</b></h1>
                            <h3>Omeprazole - What is it for</h3>
                            <p> ​Omeprazole is used to treat various conditions of the gastrointestinal (stomach and intestines) tract including: </p>
                            <ul>
                                <li> Gastro-oesophageal reflux disease (a condition where the acid from the stomach goes back up the food tube and throat, causing pain and a burning sensation known as heartburn) </li>
                                <li> Ulcers of the gastrointestinal tract (treatment and prevention) </li>
                            </ul>
                        </section>

                        <section id="side-effect">
                            <h3>Side Effects, Warnings</h3>

                            <h5>Warnings</h5>
                            <p>Omeprazole can cause kidney problems. Tell your doctor if you are urinating less than usual, or if you have blood in your urine.</p>
                            <p>Diarrhea may be a sign of a new infection. Call your doctor if you have diarrhea that is watery or has blood in it.</p>
                            <p>Omeprazole is not to used for the immediate relief of heartburn symptoms.</p>
                            <p>Omeprazole may cause new or worsening symptoms of lupus. Tell your doctor if you have joint pain and a skin rash on your cheeks or arms that worsens in sunlight.</p>

                            <h5>Before taking this medicine</h5>
                            <p>Heartburn can mimic early symptoms of a heart attack. Get emergency medical help if you have chest pain that spreads to your jaw or shoulder and you feel sweaty or light-headed.</p>
                            <p>You should not use omeprazole if you are allergic to it, or if:</p>
                            <ul>
                                <li>You are also allergic to medicines like omeprazole, such as esomeprazole, lansoprazole, pantoprazole, rabeprazole, Nexium, Prevacid, Protonix, and others</li>
                                <li>You had breathing problems, kidney problems, or a severe allergic reaction after taking omeprazole in the past</li>
                                <li>You also take HIV medication that contains rilpivirine (such as Complera, Edurant, Odefsey, Juluca)</li>
                            </ul>

                            <h5> What side effects can Omeprazole cause? </h5>
                            <p> ​Side effects of omeprazole include headache, stomach pain, diarrhea, nausea, vomiting  or passing gas. </p>
                            <ul>
                                <li> Please consult your healthcare professional if the symptoms do not go away or when any unexplained or unusual symptoms occur. </li>
                            </ul>
                            <p> Consult your healthcare professional if you develop any blood in your stool (including blackened stool) or vomit. </p>
                            <p> The symptoms of a drug allergy include one or more of the following: </p>
                            <ul>
                                <li> Swollen face/eyes/lips/tongue </li>
                                <li> Difficulty in breathing </li>
                                <li> Itchy skin rashes over your whole body </li>
                            </ul>			
                            <p> Unusual symptoms such as muscle spasms (uncontrollable muscle movements), palpitations, dizziness and seizures may be a sign of low magnesium in the body. </p>
                            <p> If you experience any of these symptoms, you should stop your medication and see your healthcare professional immediately. </p>
                        </section>

                        <section id="dosage">
                            <h3>Dosage and How to Use</h3>
                            <ul>
                                <li> Do not stop taking your medication without checking with your healthcare professional. </li>
                                <li> Take this medication 30 to 60 minutes before a meal. Capsules should be swallowed whole with water. </li>
                                <li> Contents of the capsule should not be chewed or crushed. </li>
                                <li> If you have swallowing difficulties, you can open the capsule and swallow the content or suspend it in a slightly acidic fluid such as fruit juice. You should drink the suspension within 30 minutes. A liquid preparation is available in some hospitals. </li>
                            </ul>

                            <h5> What should I do if I miss a dose? </h5>
                            <p> ​If you forget to take a dose, take it as soon as you remember. Skip the dose if it is too near to your next dose. Then take your next dose at the usual time. Do not take two doses to make up for the missed dose. </p>
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