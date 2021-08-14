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
                <title> Vancomycin </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/listcss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-is-it-for">
                            <h1><b>Vancomycin</b></h1>
                            <h3>Vancomycin - What is it for?</h3>
                            <p> Vancomycin is an antibiotic. Oral (taken by mouth) vancomycin fights bacteria in the intestines. </p>
                            <p> Vancomycin is used to treat an infection of the intestines caused by Clostridium difficile, which can cause watery or bloody diarrhea. This medicine is also used to treat staph infections that can cause inflammation of the colon and small intestines. </p>
                            <p> Oral vancomycin works only in the intestines and is not normally absorbed into the body. vancomycin will not treat other types of infection. An injectable form of this mediicne is available to treat serious infections in other parts of the body. </p>
                        </section>

                        <section id="side-effect">
                            <h3>Side Effects, Warnings</h3>

                            <h5>Warnings</h5>
                            <p>Oral vancomycin works only in the intestines and will not treat infections in other parts of the body. Follow all directions on your medicine label and package. Tell each of your healthcare providers about all your medical conditions, allergies, and all medicines you use.</p>

                            <h5>Before taking this medicine</h5>
                            <p>To make sure vancomycin is safe for you, tell your doctor if you have ever had:</p>
                            <ul>
                                <li>An intestinal disorder such as inflammatory bowel disease, Crohn's disease, or ulcerative colitis</li>
                                <li>Kidney disease</li>
                                <li>Hearing problems</li>
                            </ul>

                            <h5> What side effects can Vancomycin cause? </h5>
                            <p> It may cause serious allergic reaction. Get immediate medical help if you have signs of an allergic reaction to Jevtana, such as: </p>
                            <ul>
                                <li> Hives </li>
                                <li> Difficult breathing </li>
                                <li> Swelling on face/lips/tongue/throat </li>
                            </ul>
                            <p> Other side effects include: </p>
                            <ul>
                                <li> Nausea </li>
                                <li> Stomach pain </li>
                                <li> Low Potassium </li>
                            </ul>
                        </section>

                        <section id="dosage">
                            <h3>Dosage and How to Use</h3>
                            <h5> How should it be used? </h5>
                            <p> Take vancomycin exactly as prescribed by your doctor. Follow all directions on your prescription label and read all medication guides or instruction sheets. </p>
                            <p> Taking more of vancomycin will not make it more effective, and may cause serious or life-threatening side effects. </p>
                            <p> Shake the oral solution (liquid) before you measure a dose. Use the dosing syringe provided, or use a medicine dose-measuring device (not a kitchen spoon). </p>
                            <p> Use this medicine for the full prescribed length of time, even if your symptoms quickly improve. Skipping doses can increase your risk of infection that is resistant to medication. Vancomycin will not treat a viral infection such as the flu or a common cold. </p>
                            <p> If you use this medicine long-term, you may need frequent medical tests. </p>

                            <h5> What should I do if i miss a dose? </h5>
                            <p> Take the medicine as soon as you can, but skip the missed dose if it is almost time for your next dose. Do not take two doses at one time. </p>
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