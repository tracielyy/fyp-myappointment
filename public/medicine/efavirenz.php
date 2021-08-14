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
                <title> Efavirenz </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/listcss.css">
            </head>

            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-is-it-for">
                            <h1><b>Efavirenz</b></h1>
                            <h3>Efavirenz - What is it for</h3>
                            <p> ​Efavirenz is an antiretroviral agent that blocks the viral replication. It is used to decrease the amount of viruses (viral load) to as low as possible, for as long as possible. </p>
                        </section>

                        <section id="side-effect">
                            <h3>Side Effects, Warnings</h3>
                            <h5>Warnings</h5>
                            <p>You should not use efavirenz if you also take elbasvir and grazoprevir (Zepatier) to treat hepatitis C.</p>

                            <h5>Before taking this medicine</h5>
                            <p>You should not use efavirenz if you are allergic to it, or if you also take elbasvir and grazoprevir (Zepatier) to treat hepatitis C.</p>
                            <p>Tell your doctor if you have ever had:</p>
                            <ul>
                                <li>Liver disease (including hepatitis B or C)</li>
                                <li>Seizure</li>
                                <li>Mental illness or psychosis</li>
                                <li>Heart disease</li>
                            </ul>

                            <h5> What side effects can Efavirenz cause? </h5>
                            <p> Common side effects that may occur are: </p>
                            <ul>
                                <li> ​Dizziness. To get up slowly from a sitting or lying position. Try to avoid tasks or actions that require alertness such as riding a bicycle, rollerblading or other sport activities. </li>
                                <li> Headache. Take painkillers such as paracetamol to relieve pain. </li>
                                <li> Stomach discomfort, nausea and/or vomiting. Eating small frequent meals or sucking on hard, sugar-free candy may help to reduce these side effects. </li>
                                <li> Nightmares/Vivid dreams. Dreams usually occur initially when your child first started taking the medication. It usually stops after a few days or weeks. They may last longer in some people. If they become bothersome, try taking the dose earlier in the evening. </li>
                                <li> Insomnia. Take it earlier in the night if it affects your sleep. </li>
                                <li> Feeling tired or fatigue </li>
                                <li> Loose stools or diarrhoea. </li>
                                <li> Mild to moderate skin rash. Taking antihistamines may improve tolerability. </li>
                                <li> High cholesterol level, high triglyceride level. Changes in body fat may occur (Redistribution/accumulation of body fat in areas such as the back of the neck, breasts or abdomen) </li>
                            </ul>
                            <p> Inform your doctor if the side effects become severe and bothersome. Report to your doctor immediately if you/your child experiences any of the following symptoms: </p>
                            <ul>
                                <li> Severe rash associated with blistering/ulceration or fever. </li>
                                <li> Signs of low mood (depression), feeling unhappy or inattentive/unresponsive most of the time. </li>
                                <li> Darkening of the urine, yellowing of the skin and eyes. </li>
                            </ul>
                            <p> Efavirenz may affect the cholesterol level and liver enzymes level. Your doctor will routinely monitor the levels prior to the treatment initiation and while you/your child is on this medication. </p>
                        </section>

                        <section id="dosage">
                            <h3>Dosage and How to Use</h3>
                            <h5> How should it be used? </h5>
                            <p> ​Efavirenz is usually taken once daily. It is usually given in combination with other antiretroviral agents to achieve significant decrease in viral multiplication. </p>
                            <p> Efavirenz should be taken on an empty stomach (1 hour before or 2 hours after meals), preferably at bedtime. Do not crush or chew the tablet. </p>

                            <h5> What should I do if i miss a dose? </h5>
                            <p> ​Missing doses make Efavirenz less effective and may also make the virus resistant to Efavirenz and other possible antiretroviral agents. This could make the virus harder to treat. </p>
                            <p> If you forgot to take or give your child the medication, give the dose as soon as you remember and then continue as per normal. Otherwise, if it is close to the time for your/your child’s next dose, skip the missed dose and give the next dose at the usual time. Do not double or increase the dose. </p>
                            <p> If you/your child vomits within 15 minutes of adminstration, give another dose if possible. </p>
                        </section>

                        <section id="storage">
                            <h3>Storage</h3>
                            <h5> How should I store it? </h5>
                            <ul>
                                <li> Store at room temperature </li>
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