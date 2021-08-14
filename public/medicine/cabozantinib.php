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
                <title> Cabozantinib </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/listcss.css">
            </head>

            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-is-it-for">
                            <h1><b>Cabozantinib</b></h1>
                            <h3>Cabozantinib - What is it for</h3>
                            <p> ​​Cabozantinib is used to treat kidney, liver and thyroid cancers that have spread to other parts of the body. </p>
                        </section>

                        <section id="side-effect">
                            <h3>Side Effects, Warnings</h3>
                            <h5> Warnings </h5>
                            <p>Cabozantinib may cause a perforation (a hole or tear) or a fistula (an abnormal passageway) within your stomach or intestines. Cabozantinib can also increase your risk of serious bleeding.</p>

                            <h5>Before taking this medicine</h5>
                            <p>Tell your doctor if you have:</p>
                            <ul>
                                <li>Bleeding problems</li>
                                <li>An open wound on your skin (or a wound that is still healing)</li>
                                <li>High blood pressure</li>
                                <li>Liver disease</li>
                                <li>Pre-existing dental problem</li>
                            </ul>
                            <p> You may need to have a negative pregnancy test before starting this treatment. </p>
                            <p> Cabozantinib may harm an unborn baby. Use effective birth control to prevent pregnancy while you are using cabozantinib and for at least 4 months after your last dose. Tell your doctor if you think you might be pregnant. </p>
                            <h5> What side effects can Cabozantinib cause? </h5>
                            <ul>
                                <li> ​Nausea, decreased appetite </li>
                                <ul>
                                    <li> Take small, frequent meals throughout the day </li>
                                </ul>
                                <li> Diarrhea </li>
                                <ul>
                                    <li> Drink plenty of clear fluids to replace those lost (two litres everyday) </li>
                                </ul>
                                <li> Palms of the hands or soles of the feet may become numb, sore, red or dry </li>
                                <ul>
                                    <li> Moisturize these areas daily </li>
                                    <li> Avoid wearing tight-fitting shoes </li>
                                </ul>
                                <li> Feeling tired and lack of energy </li>
                                <ul>
                                    <li> Do not drive or operate machinery when you feel tired </li>
                                </ul>
                                <li> Mouth sores </li>
                                <ul>
                                    <li> Rinse your mouth after meals using an alcohol-free mouthwash or salt water </li>
                                    <li> Brush your teeth with a soft toothbrush </li>
                                </ul>
                                <li> Increased blood pressure </li>
                                <ul>
                                    <li> Monitor your blood pressure regularly and record it down. Inform your doctor if you notice an increasing trend. </li>
                                </ul>
                            </ul>

                            <p> If you experience any of the following symptoms, you should stop your medication and see your healthcare professional immediately.  </p>
                            <ul>
                                <li> Very bad stomach pain </li>
                                <li> Signs of infection - fever of 38° C and above, chills, cough, sore throat, pain or burning feeling on passing urine </li>
                                <li> Unusual bleeding, bruising, black sticky stools or blood in the urine </li>
                                <li> Unusual weakness, tiredness or light-headedness </li>
                                <li> Signs of blood clot e.g. warmth, pain or redness with swelling on arm or leg </li>
                                <li> Chest pain or shortness of breath </li>
                                <li> Very bad diarrhea </li>
                                <li> Painful blisters and peeling of hands or feet </li>
                                <li> Symptoms of a drug allergy including one or more of the following: </li>
                                <ul>
                                    <li> Swollen face/eyes/lips/tongue </li>
                                    <li> Difficulty in breathing </li>
                                    <li> Itchy skin rashes over your whole body </li>
                                </ul>
                            </ul>
                        </section>

                        <section id="dosage">
                            <h3>Dosage and How to Use</h3>
                            <h5> How should it be used? </h5>
                            <ul>
                                <li> ​Take Cabozantinib once a day </li>
                                <li> Cabozantinib should be taken on an empty stomach (one hour before or two hours after a meal) </li>
                                <li> Do not break or crush the tablet. Swallow the tablet whole. </li>
                                <ul>
                                    <li> Inform your doctor or pharmacist if you have difficulty swallowing </li>
                                </ul>
                            </ul>
                            <h5> What should I do if i miss a dose? </h5>
                            <p> ​If you forget to take a dose, take it as soon as you remember. However, if it is less than 12 hours to your next dose, skip the missed dose and take your next dose at the usual time. Do not take two doses at the same time. </p>
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