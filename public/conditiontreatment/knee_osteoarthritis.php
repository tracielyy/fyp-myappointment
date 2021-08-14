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
                <title> Knee Osteoarthritis </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Knee Osteoarthritis</b></h1>
                            <h3>Knee Osteoarthritis - What it is</h3>
                            <p> A healthy knee has linings of cartilage and lubricating joint fluid (i.e. synovial fluid) to protect and cushion between the main leg bones, allowing for pain-free knee movements. However, in osteoarthritis, the cartilage lining gradually wears out and the synovial fluid loses its shock-absorbing qualities. This produces the symptoms of osteoarthritis. </p>
                            <p> Doctors grade the severity of osteoarthritis by a combination of the symptoms including pain, stiffness, swelling, or loss of range of motion and X-ray appearance of the knee. </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> Symptoms can be worse in the mornings or after a period of inactivity. Pain may also increase after weight-bearing activities. This is an irreversible condition and the symptoms may worsen with time although with appropriate treatment, your symptoms can improve. </p>
                            <p> You should seek medical attention if your knee pain is sudden in onset and severe, or associated with significant swelling, redness, heat of the knees or other systemic signs of infection such as high fevers. </p>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5>Causes</h5>
                            <p> While knee osteoarthritis may affect as many as 45% of people at some point in their lifetimes,1 no one knows the exact cause. Experts do know that several risk factors increase the likelihood of developing knee arthritis. </p>
                            <ul>
                                <li><b>Obesity.</b> Knees are weight-bearing joints, and a person who is obese is twice as likely to develop knee osteoarthritis than someone who is not.</li>
                                <li><b>Joint Trauma.</b> A broken bone, serious injury, or surgery may cause damage to the knee joint that eventually leads to knee osteoarthritis. </li>
                                <li><b>Family history.</b> Similar to height and hair color, the likelihood of a person developing knee osteoarthritis is influenced by genetics.</li>
                            </ul>

                            <h5>Risk Factors</h5>
                            <p>Risk factors of knee osteoarthritis:</p>
                            <ul>
                                <li>Repetitive knee trauma</li>
                                <li>Muscle weakness</li>
                                <li>Obesity</li>
                                <li>Kneeling</li>
                                <li>Squatting</li>
                                <li>Miniscal injuries</li>
                            </ul>
                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> The doctor will assess your symptoms and examine your knee, alongside factors such as your exercise routine, footwear, and lower limb muscle strength and flexibility. X-rays or an Magnetic Resonance Imaging (MRI) scan of the knee may be ordered if necessary. If you are diagnosed with knee osteoarthritis, there are a wide range of treatment options to reduce your pain and improve function. </p>
                            <p> Non-surgical treatments include lifestyle modifications such as weight loss if you are overweight, aerobic and strengthening exercises under physiotherapy instruction, or use of walking aids such as a cane, hiking stick, or walking frame. </p>
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