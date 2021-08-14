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
                <title> Pneumonia </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Pneumonia</b></h1>
                            <h3>Pneumonia - What it is</h3>
                            <p> Pneumonia is inflammation of the lungs that can be caused by bacteria. The air sacs in the lungs are filled with pus and when the infection is severe, oxygen has trouble reaching the blood. More than half of the cases of pneumonia are caused by bacteria. Other causes include viruses, mycoplasma and others (parasites and fungi). </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> ​Patients may complain of a recent onset of the following: </p>
                            <ul>
                                <li> Cough </li>
                                <li> Fever (sometimes associated with shaking chills) and sputum </li>
                                <li> Sputum production (may be rusty or greenish in colour) </li>
                                <li> Chest pain that is aggravated by breathing and coughing </li>
                            </ul>
                            <p> The patient may appear to be breathing rapidly and have rapid pulse rate. The lips and tongue may appear to have a bluish tinge due to lack of oxygen. </p>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5>Causes</h5>
                            <p> Many germs can cause pneumonia. The most common are bacteria and viruses in the air we breathe. Your body usually prevents these germs from infecting your lungs. But sometimes these germs can overpower your immune system, even if your health is generally good. </p>
                            <p> Pneumonia is classified according to the types of germs that cause it and where you got the infection.</p>

                            <h5>Risk Factors</h5>
                            <p>Pneumonia can affect anyone. But the two age groups at highest risk are:</p>
                            <ul>
                                <li>Children who are 2 years old or younger</li>
                                <li>People who are age 65 or older</li>
                            </ul>
                            <p>Other risk factors include:</p>
                            <ul>
                                <li><b>Chronic disease.</b> You're more likely to get pneumonia if you have asthma, chronic obstructive pulmonary disease (COPD) or heart disease.</li>
                                <li><b>Smoking.</b> Smoking damages your body's natural defenses against the bacteria and viruses that cause pneumonia.</li>
                                <li><b>Weakened or suppressed immune system.</b> People who have HIV/AIDS, who've had an organ transplant, or who receive chemotherapy or long-term steroids are at risk.</li>
                            </ul>
                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> In the young, healthy patients, early treatment with antibiotics (usually taken orally) can cure and speed recovery from pneumonia. There is no effective treatment for viral pneumonia. In certain cases, viral pneumonias may become secondarily infected with bacteria and such cases require antibiotics as well. The type of antibiotics used to fight the pneumonia are determined by the most likely germ causing the pneumonia as well as the doctor's judgment. </p>
                            <p> In more severe cases, hospitalization and antibiotics given directly to the blood stream is required. In addition other supportive treatment like oxygen therapy may be needed. Patients with severe pneumonia may require admission to the intensive care unit because of its life threatening. With prompt treatment, most types of non-severe bacterial pneumonia can be cured within 1-2 weeks, but viral and certain other types of pneumonia may last longer. </p>
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