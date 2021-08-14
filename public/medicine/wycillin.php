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
                <title> Wycillin </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/listcss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-is-it-for">
                            <h1><b>Wycillin</b></h1>
                            <h3>Wycillin - What is it for?</h3>
                            <p> Wycillin is an antibiotic that fights bacteria in your body. </p>
                            <p> Wycillin is used to treat many different types of infections caused by bacteria, including syphilis (a sexually transmitted disease). </p>
                            <p> Do not use this medication for any other infection that has not been checked by your doctor. </p>
                        </section>

                        <section id="side-effect">
                            <h3>Side Effects, Warnings</h3>

                            <h5>Warnings</h5>
                            <p>You should not use this medication if you are allergic to penicillin. Tell your doctor if you have ever had an allergic reaction to a cephalosporin antibiotic</p>
                            <p>Before using Wycillin, tell your doctor if you have asthma or a history of allergies, or kidney disease.</p>
                            <p>Use this medication for the full prescribed length of time. Call your doctor if your infection does not improve, or if it gets worse while using Wycillin.</p>

                            <h5>Before taking this medicine</h5>
                            <p>If you have any of these other conditions, you may need a dose adjustment or special tests to safely use Wycillin:</p>
                            <ul>
                                <li>Asthma or a history of allergies</li>
                                <li>Kidney disease</li>
                            </ul>
                            <p>Wycillin can make birth control pills less effective. Ask your doctor about using a non-hormone method of birth control (such as a condom, diaphragm, spermicide) to prevent pregnancy while using this medicine. Hormonal forms of contraception (such as birth control pills, injections, implants, skin patches, and vaginal rings) may not be effective enough to prevent pregnancy during your treatment.</p>

                            <h5> What side effects can Wycillin cause? </h5>
                            <p> It may cause serious allergic reaction. Get immediate medical help if you have signs of an allergic reaction to Jevtana, such as: </p>
                            <ul>
                                <li> Hives </li>
                                <li> Difficult breathing </li>
                                <li> Swelling on face/lips/tongue/throat </li>
                            </ul>
                            <p> Other side effects include: </p>
                            <ul>
                                <li> White patches in your mouth or throat </li>
                                <li> Blurred vision </li>
                                <li> Ringing in your ears </li>
                                <li> Mild Skin Rash </li>
                                <li> Dizziness </li>
                            </ul>
                        </section>

                        <section id="dosage">
                            <h3>Dosage and How to Use</h3>
                            <h5> How should it be used? </h5>
                            <p> Wycillin is given as an injection into a muscle. Your doctor, nurse, or other healthcare provider will give you this injection. You may be shown how to inject your medicine at home. Do not self-inject Wycillin if you do not fully understand how to give the injection and properly dispose of used needles and syringes. </p>
                            <p> Wycillin must be injected slowly into a muscle of the buttock or upper thigh. </p>

                            <h5> What should I do if i miss a dose? </h5>
                            <p> Use the missed dose as soon as you remember. If it is almost time for your next dose, wait until then to use the medicine and skip the missed dose. Do not use extra medicine to make up the missed dose. </p>
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