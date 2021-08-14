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
                <title> Acetazolamide </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/listcss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-is-it-for">
                            <h1><b>Acetazolamide</b></h1>
                            <h3>Acetazolamide - What is it for?</h3>
                            <p>Acetazolamide is mainly used to treat glaucoma, a condition in which increased pressure in the eye that may lead to gradual loss of vision.
                                Acetazolamide helps to reduce the amount of fluid in the eye, which decreases pressure inside the eye.</p>
                            <p> It is also used to treat certain types of seizures, and to treat or prevent altitude sickness. </p>
                        </section>

                        <section id="side-effect">
                            <h3>Side Effects, Warnings</h3>
                            <h5> Warnings </h5>
                            <p>You should not use acetazolamide if you have cirrhosis, severe liver or kidney, an electrolyte imbalance, adrenal gland failure, or an allergy to acetazolamide or sulfa drug.</p>

                            <h5> Before taking this medicine </h5>
                            <p> You should not use acetazolamide if you are allergic to it, or if you have: </p>
                            <ul>
                                <li> Severe liver disease, or cirrhosis </li>
                                <li> Severe kidney disease </li>
                                <li> Electrolyte imbalance </li>
                            </ul>

                            <p> To make sure acetazolamide is safe for you, tell your doctor if you have: </p>
                            <ul>
                                <li> Severe breathing problems </li>
                                <li> Angle closure glaucoma </li>
                                <li> If you take aspirin in high doses </li>
                            </ul>

                            <h5> What side effects can Acetazolamide cause? </h5>
                            <p> Generally, the <u>common side effects</u> tend to occur at the beginning of treatment. Such side effects may include: </p>
                            <ul>
                                <li> Mild nausea, vomiting, indigestion, abdominal pain, diarrhea and loss of appetite, taste disturbance - If the medicine upsets the stomach, take it with food or milk </li>
                                <li> Headache, Drowsiness, dizziness, fatigue and unsteadiness - Be careful when you are giving your child over-the-counter medicines. Medicines for cold and allergy may add on to the drowsiness. Be sure to supervise your child when he/she is involved in activities such as cycling or swimming. </li>
                                <li> Double vision and blurred vision </li>
                                <li> Muscle weakness, poor muscle coordination or numbness in the hands and feet (“pins and needles” sensation) </li>
                                <li> Unusual behavioural changes such as hyperactivity, irritable or confusion </li>
                            </ul>

                            <p> Inform your doctor if any of the above side effects lasts for more than a few days or if they become serious or bothersome.</p>

                            <p> <u>Rare but serious side effects</u> may sometimes occur. Contact your doctor as soon as possible if you notice any of the following:</p>

                            <ul>
                                <li> Skin rash </li>
                                <li> Persistent blurred vision and/or eye pain </li>
                                <li> Difficulty or pain when passing urine </li>
                                <li> Yellowing of the skin or eyes, dark urine </li>
                                <li> Any unusual bleeding or bruises, prolonged fever, cough or mouth sores </li>
                            </ul>

                            <p> Inform your doctor if any of the above side effects lasts for more than a few days or if they become serious or bothersome. </p>
                        </section>

                        <section id="dosage">
                            <h3>Dosage and How to Use</h3>
                            <h5> How should it be used? </h5>
                            <p> Acetazolamide may be taken 2 to 3 times a day. The doctor may start your child on a low dose and then slowly increase the dose. Follow the instructions on the label carefully and ask your pharmacist or doctor if you are unsure. </p>
                            <p> Do not take this medication more often than directed and do not stop unless instructed by the doctor. Stopping this medicine too rapidly can increase the risk of seizures.</p>
                            <p> <u> Tablets </u> </p>
                            <p> Swallow the tablets whole with a glass of water. If your child is unable to swallow the tablet, you may crush it and add it to small amount of food (i.e. yogurt, ice-cream, syrup) before giving it to your child. Acetazolamide may be taken with or without food.</p>

                            <h5> What should I do if i miss a dose? </h5>
                            <p> Take the missed dose as soon as you remember. Skip the missed dose if it is almost time for your next scheduled dose. Do not take extra medicine to make up the missed dose. </p>
                        </section>

                        <section id="storage">
                            <h3>Storage</h3>
                            <h5> How should I store it? </h5>
                            <ul>
                                <li> Keep away from children </li>
                                <li> Keep in a cool, dry place, away from direct sunlight </li>
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