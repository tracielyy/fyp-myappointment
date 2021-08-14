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
                <title> Xofluza </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/listcss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-is-it-for">
                            <h1><b>Xofluza</b></h1>
                            <h3>Xofluza - What is it for?</h3>
                            <p> Xofluza (baloxavir marboxil) is used to treat flu symptoms caused by influenza virus in people who have had symptoms for no more than 48 hours. It will not treat the common cold. </p>
                            <p> Xofluza is for adults and children who are at least 12 years old and weigh at least 88 pounds (40 kilograms). </p>
                            <p> Xofluza should not be used in place of getting a yearly flu shot. The Centers for Disease Control and Prevention recommends an annual flu shot to help protect you each year from new strains of influenza virus. </p>
                        </section>

                        <section id="side-effect">
                            <h3>Side Effects, Warnings</h3>

                            <h5>Warnings</h5>
                            <p>Xofluza may contain inactive ingredients, which can cause allergic reactions or other problems.</p>
                            <p>Follow all directions on your medicine label and package. Tell each of your healthcare providers about all your medical conditions, allergies, and all medicines you use.</p>

                            <h5>Before taking this medicine</h5>
                            <p>Tell your doctor if you are pregnant or breast-feeding.</p>
                            <p>It is not known whether this medicine will harm an unborn baby. However, having influenza during pregnancy can cause severe complications that could harm both mother and baby. The benefit of using Xofluza to treat flu symptoms may outweigh any risks.</p>

                            <h5> What side effects can Xofluza cause? </h5>
                            <p> It may cause serious allergic reaction. Get immediate medical help if you have signs of an allergic reaction to Jevtana, such as: </p>
                            <ul>
                                <li> Hives </li>
                                <li> Difficult breathing </li>
                                <li> Swelling on face/lips/tongue/throat </li>
                            </ul>
                            <p> Other side effects include: </p>
                            <ul>
                                <li> Cough </li>
                                <li> Nausea </li>
                                <li> Diarrhea </li>
                                <li> Runny/Stuffy nose </li>
                                <li> Headache </li>
                            </ul>
                        </section>

                        <section id="dosage">
                            <h3>Dosage and How to Use</h3>
                            <h5> How should it be used? </h5>
                            <p> Take Xofluza exactly as prescribed by your doctor. Follow all directions on your prescription label and read all medication guides or instruction sheets. </p>
                            <p> Xofluza is usually given as a single dose of 1 or more tablets to be taken at one time. </p>
                            <p> Take Xofluza when you first notice flu symptoms (fever, chills, muscle aches, sore throat, runny or stuffy nose). This medicine may not be effective if you have been sick for longer than 48 hours. </p>
                            <p> You may take Xofluza with or without food. </p>
                            <p> Do not take this medicine with dairy products such as milk or yogurt, or with calcium-fortified juice. </p>

                            <h5> What should I do if i miss a dose? </h5>
                            <p> Xofluza is used as a single dose and does not have a daily dosing schedule. </p>
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