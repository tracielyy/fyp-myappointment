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
                <title> Ultram </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/listcss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-is-it-for">
                            <h1><b>Ultram</b></h1>
                            <h3>Ultram - What is it for?</h3>
                            <p> Ultram is a narcotic-like pain reliever.</p>
                            <p> Ultram is used to treat moderate to severe pain. </p>
                        </section>

                        <section id="side-effect">
                            <h3>Side Effects, Warnings</h3>

                            <h5>Warnings</h5>
                            <p>You should not take Ultram if you have severe breathing problems, a blockage in your stomach or intestines, or if you have recently used alcohol, sedatives, tranquilizers, narcotic medication, or an MAO inhibitor (isocarboxazid, linezolid, methylene blue injection, phenelzine, rasagiline, selegiline, tranylcypromine, and others).</p>
                            <p>Taking Ultram during pregnancy may cause life-threatening withdrawal symptoms in the newborn.</p>

                            <h5>Before taking this medicine</h5>
                            <p>You should not take Ultram if you are allergic to tramadol, or if you have:</p>
                            <ul>
                                <li>Severe asthma or breathing problems;</li>
                                <li>Stomach or bowel obstruction (including paralytic ileus)</li>
                                <li>Recent use of alcohol, sedatives, tranquilizers, or narcotic medications</li>
                            </ul>
                            <p>Do not give Ultram to anyone younger than 18 years old who recently had surgery to remove the tonsils or adenoids.</p>

                            <h5> What side effects can Ultram cause? </h5>
                            <p> It may cause serious allergic reaction. Get immediate medical help if you have signs of an allergic reaction to Jevtana, such as: </p>
                            <ul>
                                <li> Hives </li>
                                <li> Difficult breathing </li>
                                <li> Swelling on face or throat </li>
                            </ul>
                            <p> Other side effects include: </p>
                            <ul>
                                <li> Constipation </li>
                                <li> Dizziness </li>
                                <li> Nausea </li>
                                <li> Stomach pain </li>
                                <li> Itching </li>
                            </ul>
                        </section>

                        <section id="dosage">
                            <h3>Dosage and How to Use</h3>
                            <h5> How should it be used? </h5>
                            <p> Take Ultram exactly as prescribed. Follow all directions on your prescription label. Tramadol can slow or stop your breathing, especially when you start using this medicine or whenever your dose is changed. Never take this medicine in larger amounts, or for longer than prescribed. Tell your doctor if the medicine seems to stop working as well in relieving your pain. </p>
                            <p> Ultram may be habit-forming, even at regular doses. Never share this medicine with another person, especially someone with a history of drug abuse or addiction. <b>MISUSE OF PAIN MEDICATION CAN CAUSE ADDICTION, OVERDOSE, OR DEATH, especially in a child or other person using the medicine without a prescription.</b> Selling or giving away this medicine is against the law.</p>
                            <p> Stop taking all other around-the-clock narcotic pain medications when you start taking this medicine. </p>
                            <p> Ultram can be taken with or without food, but take it the same way each time. </p>
                            <p> Do not crush, break, or open an Ultram ER extended-release tablet. Swallow the tablet whole to avoid exposure to a potentially fatal dose. </p>

                            <h5> What should I do if i miss a dose? </h5>
                            <p> Since Ultram is used for pain, you are not likely to miss a dose. Skip any missed dose if it is almost time for your next scheduled dose. Do not take extra medicine to make up the missed dose. </p>
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