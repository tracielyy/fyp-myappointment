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
                <title> Jaundice </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Jaundice</b></h1>
                            <h3>Jaundice - What it is</h3>
                            <p> Jaundice is yellow discoloration of tissues due to accumulation of bilirubin. Normal serum bilirubin ranges from 7-32 mmol/L but jaundice may not be detected clinically until the level exceeds 40mmol/L. Various mechanisms can contribute to jaundice, such as excess bilirubin production, impaired intrahepatic conjugation and secretion, or obstruction in bile flow. </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> Symptoms may not be specific. A history of hepatitis, drug intake, injection, alcohol intake, sexual contact, and ingestion of raw shellfish and wild mushrooms are some of the causes. In addition to yellow discoloration of skin and sclera, patient may present with upper abdominal pain or discomfort, fever, weight loss, loss of appetite, nausea and vomiting, tea-colored urine, clay-colored stool or skin itchiness. </p>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5>Causes</h5>
                            <ul>
                                <li> Haemolysis </li>
                                <li> Hepatitis: Acute and Chronic </li>
                                <li> Drug induced liver damage </li>
                                <li> Liver failure: Acute and Chronic </li>
                                <li> Cirrhosis </li>
                            </ul>

                            <h5>Risk Factors</h5>
                            <p>Major risk factors for jaundice, particularly severe jaundice that can cause complications, include:</p>
                            <ul>
                                <li><b>Premature birth.</b> A baby born before 38 weeks of gestation may not be able to process bilirubin as quickly as full-term babies do. Premature babies also may feed less and have fewer bowel movements, resulting in less bilirubin eliminated through stool.</li>
                                <li><b>Blood type.</b> If the mother's blood type is different from her baby's, the baby may have received antibodies through the placenta that cause abnormally rapid breakdown of red blood cells.</li>
                            </ul>
                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> Treatment of jaundice is tailored to identifying and treating the underlying cause. For medical jaundice, the treatment is generally supportive and avoidance of further liver insult. Acute liver failure carries high mortality rate and may require liver transplant if supportive measure failed. </p>
                            <p> Patients presenting with obstructive jaundice and sepsis requires close monitoring and antibiotic treatment. Biliary decompression via percutaneous transhepatic biliary drainage (PTBD) or endoscopic drainage may be required urgently for sepsis control. Emergency surgery is rarely required in acute setting. Subsequently surgery maybe planned for gallstones/ ductal stone disease in the form of cholecystectomy, laparoscopic or open, with or without CBD exploration. </p>
                            <p> Definitive surgery, curative or palliative bypass maybe be arranged for suitable candidate with underlying malignancy after proper evaluation and study. </p>
                            <p> In general, jaundice is a hallmark of underlying syndrome that requires collative management in multidisciplinary approach. </p>
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