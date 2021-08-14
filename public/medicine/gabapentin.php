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
                <title> Gabapentin </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/listcss.css">
            </head>

            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-is-it-for">
                            <h1><b>Gabapentin</b></h1>
                            <h3>Gabapentin - What is it for</h3>
                            <p> Gabapentin is used to treat neuropathic pain (nerve pain) caused by damage to the nerves. The nerve damage may be due to conditions such as diabetes (diabetic neuropathy) or shingles (postherpetic neuralgia). Gabapentin works by reducing the amount of pain signals coming from damaged or abnormally functioning nerves. </p>
                        </section>

                        <section id="side-effect">
                            <h3>Side Effects, Warnings</h3>
                            <h5>Warnings</h5>
                            <p>Gabapentin can cause life-threatening breathing problems, especially if you already have a breathing disorder or if you use other medicines that can make you drowsy or slow your breathing. Seek emergency medical attention if you have very slow breathing.</p>
                            <p>Some people have thoughts about suicide or behavior changes while taking gabapentin. Stay alert to changes in your mood or symptoms. Report any new or worsening symptoms to your doctor.</p>

                            <h5>Before taking this medicine</h5>
                            <p>To make sure gabapentin is safe for you, tell your doctor if you have ever had:</p>
                            <ul>
                                <li>Breathing problems or lung disease</li>
                                <li>Kidney disease (or if you are on dialysis)</li>
                                <li>Diabetes</li>
                                <li>Seisure</li>
                                <li>Heart disease</li>
                                <li>Liver disease</li>
                                <li>Depression</li>
                            </ul>

                            <h5> What side effects can Gabapentin cause? </h5>
                            <p> ​Generally, side effects tend to occur at the beginning of treatment or when the dose is increased, but should wear off after several days. <u>Common side effects</u> include: </p>
                            <ul>
                                <li> Drowsiness, dizziness, fatigue, swelling of feet (more common in adults), headache </li>
                                <li> Abdominal pain, nausea/vomiting, increased appetite and constipation or diarrhoea </li>
                                <li> Swelling of feet and hands </li>
                                <li> This medication may make you more sensitive to the sun </li>
                            </ul>
                            <p> Inform your doctor immediately if the side effects above become severe and bothersome. </p>
                            <p> There are some potentially <u>serious, but rare side effects</u> that may be experienced: </p>
                            <ul>
                                <li> Behavioural changes e.g. irritability, moodiness, restlessness/hyperactive, trouble concentrating, and memory loss </li>
                                <li> Twitching or tremors </li>
                                <li> Vision disturbances </li>
                            </ul>
                            <p> Stop the medication and inform your doctor immediately if these rare side effects above occur, or if you experience any other side effects and allergic reactions (e.g. rash, swelling of the eyes and lips, difficulty breathing). </p>
                        </section>

                        <section id="dosage">
                            <h3>Dosage and How to Use</h3>
                            <h5> How should it be used? </h5>
                            <p> Gabapentin is usually taken one to three times a day. The doctor may start you or your child on a low dose and increase the dose gradually over the next few days or weeks. </p>
                            <p> Gabapentin is to be taken regularly as prescribed, according to the instructions provided. Do not stop taking gabapentin unless you are told to do so by the doctor. It may take several weeks before you or your child feels better. </p>
                            <p> Swallow the capsule whole with a glass of water. Do not crush or chew the capsule. For those unable to swallow the capsules or for doses less than a full capsule, the capsule can be opened and contents mixed in a small amount of water. Your pharmacist will advise on the amount required to be syringed out according to the dose. </p>
                            <p> It can be taken with or without food. </p>
                            <h5> What should I do if i miss a dose? </h5>
                            <p> ​Take the missed dose as soon as you remember it. However, if it is almost time for the next dose, skip the missed dose and take only the regular scheduled dose. Do not take a double dose to make up for the missed one. </p>
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