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
                <title> Menorrhagia </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Menorrhagia</b></h1>
                            <h3>Menorrhagia - What it is</h3>
                            <p> In a normal menstrual cycle, the average woman loses a total of 30-40 ml of blood over three to seven days. Heavy or prolonged menstrual bleeding is known as menorrhagia. </p>
                            <p> Research criteria defines this narrowly as a monthly menstrual blood loss in excess of 80 ml. A more practical definition may be that of menstrual loss that is greater than the woman feels she can reasonably manage. The National Institute for Health and Clinical Excellence (NICE) in the UK defines heavy menstrual loss as excessive blood loss that interferes with a woman’s physical, social, emotional and/or quality of life. </p>
                            <p> Menorrhagia is a common problem in clinical practice that can have adverse effects on the quality of life for many women. </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> ​You may be experiencing menorrhagia if you have the following: </p>
                            <ul>
                                <li> Soaking through more than four to five pads/tampons per day </li>
                                <li> Bleeding associated with large clots or overflow (staining of underwear or clothes) </li>
                                <li> Needing to use double sanitary protection to control the flow </li>
                                <li> Having to wake up at night to change sanitary protection </li>
                                <li> Bleeding that lasts longer than a week </li>
                                <li> Restriction of activities due to heavy flow </li>
                                <li> Symptoms of anaemia (low blood count) such as being easily tired out, experiencing giddiness or shortness of breath with exertion </li>
                            </ul>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5> Causes </h5>
                            <p> Causes include: </p>
                            <ul>
                                <li> <b>Dysfunctional uterine bleeding</b> (excessive bleeding with no identifiable cause): 20-40 percent. </li>
                                <li> <b>Anovulatory cycles</b> (more common at extremes of reproductive age): 20 percent. </li>
                                <li> <b>Organic causes.</b> Fibroids, endometrial polyps, adenomyosis, endometritis, pelvic inflammatory disease. </li>
                                <li> <b>Endometrial hyperplasia and carcinoma.</b> This is a consideration especially in patients above 40 years old or with risk factors such as polycystic ovarian syndrome, obesity, nulliparity, early menarche, diabetes mellitus, excessive oestrogen (female hormones) either produced by the body or supplemented externally. </li>
                                <li> <b>Systemic disease.</b> Including hypothyroidism, liver or kidney failure and bleeding disorders. </li>
                            </ul>	

                            <h5>Risk Factors</h5>
                            <p>Risk factors vary with age and whether you have other medical conditions that may explain your menorrhagia. In a normal cycle, the release of an egg from the ovaries stimulates the body's production of progesterone, the female hormone most responsible for keeping periods regular. When no egg is released, insufficient progesterone can cause heavy menstrual bleeding.</p>
                            <p>Menorrhagia in adolescent girls is typically due to anovulation. Adolescent girls are especially prone to anovulatory cycles in the first year after their first menstrual period (menarche).</p>
                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> The important conditions to rule out first include pregnancy, endometrial hyperplasia (abnormal thickening of the lining of the womb) and endometrial carcinoma. </p>
                            <p> If there are organic causes of menorrhagia, such as fibroids or adenomyosis, treatment options can be offered based on your wishes and fertility concerns. </p>
                            <p> If there is suspected chronic endometritis (risk factors include recent childbirth or intrauterine procedure), this can often be treated with a course of antibiotics. </p>
                            <p> If you are found to be anaemic, iron supplementation is usually recommended. </p>
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