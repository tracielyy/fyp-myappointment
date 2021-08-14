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
                <title> Blepharitis </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Blepharitis</b></h1>
                            <h3>Blepharitis - What it is</h3>
                            <p> Blepharitis is an inflammation of the eyelids. It usually affects the edges (margins) of the eyelids. It is not a serious condition but may be uncomfortable and irritating. </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> Blepharitis symptoms may include one or more of the following: </p>
                            <ul>
                                <li> Aggravating dry eyes </li>
                                <li> Chronic eye irritation with itchy or gritty sensation </li>
                                <li> Crusting of the eyelids </li>
                                <li> Flaking of the skin around the eyelids </li>
                                <li> Recurrent or chronic red eyes </li>
                            </ul>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5>Causes</h5>
                            <p> Blepharitis occurs because of a dysfunction of the oil glands (meibomian glands) that are present along the eyelid margins. </p>
                            <p> The meibomian glands are responsible for producing an oily substance that makes up part of your tears. A problem in these glands can lead to excess production of this oily substance or a blockage in the glands, which can cause the eyelids to become irritated and inflamed. </p>
                            <p> Blepharitis is often caused by bacterial infection. Everyone has bacteria on the surface of their skin, but in some people, bacteria thrive in the skin at the base of the eyelashes. Large amounts of bacteria around the eyelashes can cause dandruff-like scales and debris to form along the lashes and eyelid margins. </p>

                            <h5> Risk Factors </h5>
                            <ul>
                                <li> Dandruff </li>
                                <li> Dry skin </li>
                                <li> Acne </li>
                                <li> Diabetes </li>
                                <li> Poor Hygiene </li>
                            </ul>
                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> Blepharitis is often a chronic or ongoing condition, but it can be controlled with the following treatments. Your ophthalmologist will recommend an appropriate treatment for you. </p>
                            <ul>
                                <li> Warm compresses </li>
                                <li> Eyelid scrubs </li>
                                <li> Antibiotics </li>
                                <li> Steroid eye drops </li>
                            </ul>
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