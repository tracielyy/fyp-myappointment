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
                <title> Epiblepharon </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Epiblepharon</b></h1>
                            <h3>Epiblepharon - What it is</h3>
                            <p> Epiblepharon refers to an in-turning of the eyelashes in the presence of a normal eyelid position. This condition is typically seen in children and young adults of Asian descent. Affected patients have an abnormal congenital horizontal fold of skin near the upper or lower eyelid. This causes the eyelid lashes to be directed towards the eye surface. The constant rubbing of the lashes against the cornea can cause irritation of the cornea and lead to red eyes. In some cases, the cornea may even become scratched and scarred. </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> This condition often have the following symptoms:    </p>
                            <ul>
                                <li> Itch and tearing </li>
                                <li> Redness of the eyes </li>
                                <li> Constant and frequent rubbing of eyes </li>
                                <li> Photophobia (glare from bright lights) </li>
                            </ul>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5>Causes</h5>
                            <p> Epiblepharon is characterised by a congenital horizontal fold of skin near the normal eyelid margin that is caused by the abnormal insertion of muscle fibres. This causes the lashes to be redirected into a vertical position and contact the cornea or conjunctiva.</p>

                            <h5> Risk Factors </h5>
                            <p> Common Risk Factors: </p>
                            <ul>
                                <li> Children and young adults of East Asian descent, typically with parents who have either a weak or absent upper eyelid creases ('double eyelids'). </li>
                                <li> Tighter eyelids in East Asian orbits  </li>
                            </ul>
                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> Epiblepharon can be mild or severe and the treatment depends on the severity and the presence of corneal damage caused by the constant rubbing of the lashes against the cornea. Some children with mild epiblepharon can grow out of the condition. This occurs with maturation of the facial structures. Mild cases can be treated with lubricating eye drops or ointment. In severe cases, surgery may be required. </p>
                            <p> Surgery involves removing a small area of excess skin and muscle just below the lid margin to help the lashes rotate outwards. There may be a faint line where the incision is made, but this usually becomes less obvious with time. </p>
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