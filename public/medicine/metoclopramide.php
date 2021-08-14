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
                <title> Metoclopramide </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/listcss.css">
            </head>

            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-is-it-for">
                            <h1><b>Metoclopramide</b></h1>
                            <h3>Metoclopramide - What is it for</h3>
                            <p> ​​Metoclopramide is a medicine that is used to prevent and treat nausea or vomiting. It is usually given as an add-on to other medicines. It is sometimes used to treat conditions like bloatedness and indigestion. </p>
                        </section>

                        <section id="side-effect">
                            <h3>Side Effects, Warnings</h3>

                            <h5>Warnings</h5>
                            <p>Do not use this medicine if you've ever had muscle movement problems after using metoclopramide or similar medicines, or if you've had a movement disorder called tardive dyskinesia. You also should not use this medicine if you've had stomach or intestinal problems (a blockage, bleeding, or a hole or tear), epilepsy or other seizure disorder, or an adrenal gland tumor (pheochromocytoma).</p>
                            <p>Before you take metoclopramide, tell your doctor if you have kidney or liver disease, congestive heart failure, high blood pressure, diabetes, Parkinson's disease, or a history of depression.</p>
                            <p>Stop using metoclopramide and call your doctor at once if you have tremors or uncontrolled muscle movements, fever, stiff muscles, confusion, sweating, fast or uneven heartbeats, rapid breathing, depressed mood, thoughts of suicide or hurting yourself, hallucinations, anxiety, agitation, seizure, or jaundice (yellowing of your skin or eyes).</p>

                            <h5>Before taking this medicine</h5>
                            <p>You should not use metoclopramide if you are allergic to it, or if you have:</p>
                            <ul>
                                <li>Stomach or intestinal problems such as a blockage, bleeding, or perforation (a hole or tear in your stomach or intestines)</li>
                                <li>Tardive dyskinesia (a disorder of involuntary movements)</li>
                                <li>Epilepsy or other seizure disorder</li>
                                <li>Adrenal gland tumor (pheochromocytoma)</li>
                            </ul>

                            <h5> What side effects can Metoclopramide cause? </h5>
                            <ul>
                                <li> Drowsiness </li>
                                <li> Mild diarrhea </li>
                                <li> Mild, transient decrease of blood pressure (manifesting in light-headedness) upon standing up </li>
                            </ul>
                            <p> Inform your doctor if any of the following occurs: </p>
                            <ul>
                                <li> Involuntary muscle twitching of the limbs or face and nervous tics </li>
                                <li> Abnormal movement of the eyes, head and neck </li>
                                <li> Tremors or restlessness </li>
                                <li> Movements become slow and difficult </li>
                                <li> Unusually slow heartbeat which makes you tired or uncomfortable </li>
                            </ul>
                        </section>

                        <section id="dosage">
                            <h3>Dosage and How to Use</h3>
                            <p> ​It is usually taken on a when-needed basis to a maximum of 30 mg (three 10 mg tablets) per day. Higher doses should be taken only upon recommendation by a doctor.  </p>
                            <p> It can be taken 30 minutes before meals to reduce bloatedness and aid digestion. Each dose should be at least 6 hours apart. </p>
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