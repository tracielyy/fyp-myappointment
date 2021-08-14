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
                <title> Sirolimus </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/listcss.css">
            </head>

            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-is-it-for">
                            <h1><b>Sirolimus</b></h1>
                            <h3>Sirolimus - What is it for</h3>
                            <p> ​​Sirolimus is a medicine that suppresses the immune system. It is used to treat certain types of cancer. </p>
                            <p> It is also used to treat vascular malformations, which are abnormalities in the development of blood vessels. These malformations vary in size and location within the body. </p>
                        </section>

                        <section id="side-effect">
                            <h3>Side Effects, Warnings</h3>

                            <h5>Warnings</h5>
                            <p>You should not use sirolimus if you have ever had a lung transplant or liver transplant.</p>
                            <p>Sirolimus may cause your body to overproduce white blood cells. This can lead to cancer, severe brain infection causing disability or death, or a viral infection causing kidney transplant failure.</p>

                            <h5>Before taking this medicine</h5>
                            <p>Talk with your doctor about the risks and benefits of using this medicine. Sirolimus can affect your immune system, and may cause overproduction of certain white blood cells. This can lead to cancer, severe brain infection causing disability or death, or a viral infection causing kidney transplant failure.</p>
                            <p>Tell your doctor if you ever had:</p>
                            <ul>
                                <li>Liver disease</li>
                                <li>High Cholesterol</li>
                                <li>Family history of skin cancer</li>
                            </ul>
                            <p>Do not use sirolimus if you are pregnant. Use effective birth control to prevent pregnancy while you are taking sirolimus, and for at least 12 weeks after your last dose.</p>

                            <h5> What side effects can Sirolimus cause? </h5>
                            <ul>
                                <li> Nausea and/or vomiting may sometimes occur when you first start taking Sirolimus </li>
                                <li> Acne </li>
                                <li> Mild to moderate headache </li>
                                <li> Slight indigestion or diarrhea may sometimes occur </li>
                                <li> Increased cholesterol or lipid levels. Your blood cholesterol should be monitored regularly. Discuss a proper follow-up plan with your doctor.</li>
                                <li> Body aches and tiredness on some days. Try to avoid heavy-duty work or long outings on such days. </li>
                            </ul>
                            <p> Inform your doctor if you experience any of the following: </p>
                            <ul>
                                <li> Allergic reactions – rashes, swelling, breathing difficulty </li>
                                <li> Rapid heartbeat or chest pain </li>
                                <li> Signs of infection – fever or chills, cough, sore throat, pain or difficulty in urinating </li>
                                <li> Signs of bleeding – bruising, black tarry stools, blood in the urine </li>
                                <li> Unusual weakness, tiredness or light-headedness </li>
                            </ul>
                        </section>

                        <section id="dosage">
                            <h3>Dosage and How to Use</h3>
                            <h5> How should it be used? </h5>
                            <p> ​It is a tablet taken by mouth, with a glass of plain water. It should be taken consistently with or without food. Take the exact number of tablets instructed by your doctor. </p>
                            <h5> What should I do if I miss a dose? </h5>
                            <p> ​​If you miss a dose of Sirolimus, take it as soon as you remember. If it is too near your next dose, skip the missed dose and go back to your usual dosing. Do not double the dose. </p>
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