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
                <title> Facial Paralysis </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Facial Paralysis</b></h1>
                            <h3>Facial Paralysis - What it is</h3>
                            <p> The facial nerve is the nerve that controls movement of the muscles of the face. It is divided into five main branches which are responsible for important facial functions such as lifting of the eyebrows, eye closure and smiling. Paralysis results in severe impairment of the function and appearance of the face. In addition, taste sensation to the front of the tongue and tear production can be affected.  </p>
                            <p> The facial nerve originates in the brain and exits the skull below the ear, passing through the parotid salivary gland as it divides into branches that enter the facial muscles. </p>
                            <p> Facial paralysis can thus occur for a variety of reasons when the nerve is injured or interrupted along its course. These include salivary gland tumours, brain tumours, trauma or infections. It can also occur in children due to congenital abnormal development of the facial nerve.  </p>
                            <p> Even after recovery from facial paralysis, disorganised regeneration of the nerve can lead to troubling sequelae such as unwanted co-contractions of the muscles.  </p>
                            <p> We provide a specialised facial nerve clinic to diagnose and treat facial nerve conditions in adults and children. Our plastic surgeon sub-specialising in facial nerve disorders has undergone intensive fellowship training in this area and is able to offer an individualised treatment plan to suit your specific needs.  </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> Patients who experience sudden facial paralysis should go to the Accident & Emergency Department immediately. After confirmation of the diagnosis, appropriate initial steps include oral steroids and antiviral treatment if indicated.  </p>
                            <p> In our outpatient clinic, further non-urgent investigations such as hearing tests, computed tomography (CT scan), magnetic resonance imaging scan (MRI scan) as well as electrophysiological tests may need to be performed in certain patients. Other causes of facial paralysis such as tumour, trauma and inner ear infection must be ruled out. </p>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5>Causes</h5>
                            <p> Bell’s palsy is the leading cause of facial paralysis. The nerve becomes inflamed and swollen within its tight canal and is unable to function. This may be caused by a virus. There is some evidence that the culprit is often herpes simplex virus (HSV), the same virus that causes cold sores and genital herpes. Other viruses may also cause the condition, including herpes zoster virus, cytomegalovirus, and Epstein-Barr virus. Both genders and all races are affected equally; however diabetes and pregnancy increase the risk of developing Bell’s palsy. </p>
                            <p> Patients generally report being suddenly unable to move their face, with symptom onset over 48-72 hours. </p>
                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> “Synkinesis” refers to involuntary linked contractions between facial muscle groups. It can occur in the course of nerve recovery due to disorganised or abnormal facial nerve regeneration. For example, patients may notice that their eye spontaneously closes when they smile. They may also have sensations such as twitching, spasms and tightness around the eye or mouth.   Our rehabilitation program by dedicated physiotherapists is divided into phases depending on the stage of muscle recovery. We aim to gently strengthen the facial muscles while preventing or reducing the development of synkinesis.   </p>
                            <p> Botulinum toxin (Botox) injection is an important adjunct to weaken muscles that are in spasm or hyperactive. Effects last for up to 6 months for the first 2 years and thereafter, the duration of effect may be prolonged to 1 year. Surgical procedures such as blepharoplasty, brow lift, reduction of synkinetic muscles and smile reconstruction can improve symmetry and appearance. </p>
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