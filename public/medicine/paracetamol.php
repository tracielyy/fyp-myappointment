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
                <title> Paracetamol </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/listcss.css">
            </head>

            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-is-it-for">
                            <h1><b>Paracetamol</b></h1>
                            <h3>Paracetamol - What is it for</h3>
                            <p> ​Paracetamol is used to relieve fever, headache, body-aches and pain in general. </p>
                        </section>

                        <section id="side-effect">
                            <h3>Side Effects, Warnings</h3>

                            <h5>Warnings</h5>
                            <p>Do not use more of this medication than is recommended. An overdose of paracetamol can cause serious harm. The maximum amount of paracetamol for adults is 1 gram (1000 mg) per dose and 4 grams (4000 mg) per day. Taking more paracetamol could cause damage to your liver. If you drink more than three alcoholic beverages per day, talk to your doctor before taking paracetamol and never use more than 2 grams (2000 mg) per day.</p>
                            <p>Do not use this medication without first talking to your doctor if you drink more than three alcoholic beverages per day or if you have had alcoholic liver disease (cirrhosis). You may not be able to use paracetamol .</p>
                            <h5>Before taking this medicine</h5>
                            <p>Ask a doctor or pharmacist if it is safe for you to take paracetamol if you have:</p>
                            <ul>
                                <li>Liver disease</li>
                                <li>History of alcoholism</li>
                            </ul>

                            <h5> What side effects can Paracetamol cause? </h5>
                            <p> ​Paracetamol is well tolerated with few reported side effects. Inform your healthcare professional if you experience any side effects that you think might be related to Paracetamol. </p>
                            <p> Patients who overdoes on Paracetamol may have an increased risk of liver problems. Signs of liver problems include: </p>
                            <ul>
                                <li> Nausea, sudden weight loss, loss of appetite </li>
                                <li> Yellowing of eyes and skin </li>
                                <li> Unexplained bruising or bleeding </li>
                            </ul>
                            <p> Paracetamol may also cause allergic reactions. The symptoms of a drug allergy include one or more of the following: </p>
                            <ul>
                                <li> Swollen face/eyes/lips/tongue </li>
                                <li> Difficulty in breathing </li>
                                <li> Itchy skin rashes over your whole body </li>
                            </ul>			
                            <p> If you experience any of these rare symptoms, you should stop taking Paracetamol and see your healthcare professional immediately.  </p>
                        </section>

                        <section id="dosage">
                            <h3>Dosage and How to Use</h3>
                            <p> As there are different strengths and dosage forms for Paracetamol, please make sure to take it as recommended on the label. Do not take two products containing paracetamol at the same time. </p>
                            <p> Do check with your healthcare professional if you are unsure of how to take the medicine. </p>
                            <p> Do not exceed the Paracetamol dosage recommended on the label as taking too much Paracetamol may cause serious liver problem. </p>
                            <p> Paracetamol can be taken with or without food </p>
                            <h5> What should I do if I miss a dose? </h5>
                            <p> ​Paracetamol may be taken when needed to relieve fever or pain, it is not always necessary to take it regularly. Please check with your healthcare professional if you are unsure. </p>
                        </section>

                        <section id="storage">
                            <h3>Storage</h3>
                            <h5> How should I store it? </h5>
                            <ul>
                                <li> Keep away from children </li>
                                <li> Keep in a cool, dry place, away from direct sunlight </li>
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