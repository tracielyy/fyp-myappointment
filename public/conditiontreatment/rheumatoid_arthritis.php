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
                <title> Rheumatoid Arthritis </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Rheumatoid Arthritis</b></h1>
                            <h3>Rheumatoid Arthritis - What it is</h3>
                            <p> Rheumatoid arthritis (RA) is the most common autoimmune rheumatic disorder and aff ects around 1% of the population, which is equivalent to about 45,000 people in Singapore. A chronic inflammatory disorder, it affects the joints and less frequently, the skin, eyes, lungs and other organs. </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> ​Rheumatoid Arthritis (RA) causes joint stiffness, pain and swelling and possibly other organ damage. The joints affected and severity of joint or other organ inflammation varies between people. </p>
                            <p> Sometimes, a person may not realise for a long period of time that he has Rheumatoid Arthritis because the symptoms may be as subtle as persistent tiredness and mild joint stiffness. </p>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5>Causes</h5>
                            <p> ​Rheumatoid arthritis is an autoimmune disease. Normally, your immune system helps protect your body from infection and disease. In rheumatoid arthritis, your immune system attacks healthy tissue in your joints. It can also cause medical problems with your heart, lungs, nerves, eyes and skin. </p>
                            <p> Doctors don't know what starts this process, although a genetic component appears likely. While your genes don't actually cause rheumatoid arthritis, they can make you more likely to react to environmental factors — such as infection with certain viruses and bacteria — that may trigger the disease. </p>

                            <h5>Risk Factors</h5>
                            <p>Factors that may increase your risk of rheumatoid arthritis include:</p>
                            <ul>
                                <li><b>Gender.</b> Women are more likely than men to develop rheumatoid arthritis.</li>
                                <li><b>Age.</b> Rheumatoid arthritis can occur at any age, but it most commonly begins in middle age.</li>
                                <li><b>Family History.</b> If a member of your family has rheumatoid arthritis, you may have an increased risk of the disease.</li>
                                <li><b>Smoking.</b> Cigarette smoking increases your risk of developing rheumatoid arthritis, particularly if you have a genetic predisposition for developing the disease. Smoking also appears to be associated with greater disease severity.</li>
                            </ul>

                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> Upon confirmation of the diagnosis of Rheumatoid Arthritis, the attending doctor will determine the type of medication suitable for the patient based on individual requirements. Examples of medications used to treat Rheumatoid Arthritis are NSAIDS (Diclofenac), prednisolone, hydroxychloroquine, methotrexate and TNF - blockers such as etanercept and infl iximab. The attending doctor may also arrange for the patient to meet the rheumatology nurse clinician, physiotherapist and occupational therapists, if appropriate. </p>
                            <p> Rheumatoid Arthritis causes mainly joint but possibly other organ inflammation as well. Referral for evaluation should be considered as soon as possible if Rheumatoid Arthritis is suspected so that appropriate treatment can be given to prevent permanent organ damage. At the moment, there is no cure for Rheumatoid Arthritis but rapid research developments have given rise to treatments that have enabled people affected by Rheumatoid Arthritis to live normal lives. </p>
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