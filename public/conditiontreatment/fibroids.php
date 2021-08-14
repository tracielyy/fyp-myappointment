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
                <title> Fibroids </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Fibroids</b></h1>
                            <h3>Fibroids - What it is</h3>
                            <p> Fibroids are growths arising from the muscle wall of the uterus. It is a round and firm structure amid the soft muscle layer. When cut open, the pale and dense cut surface gives us the impression that it is a growth of densely packed fibrous tissue. The growth attracts the common name of fibroid because of these characteristics. </p>
                            <p> In medical term, fibroid is known as leiomyoma. It reflects the true nature that the growth is a benign (not cancerous) tumour developed from abnormal muscle cells of the uterus, not fibrous tissue. </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> Fibroids are typically silent in at least 60 percent of women. They are discovered on a routine examination of the pelvis or when an ultrasound scan of the pelvis is carried out for some other reasons. In the other 40 percent of women, fibroids may cause one or more of the following symptoms: </p>
                            <ul>
                                <li> Heavy menstrual flow </li>
                                <li> Prolonged menstrual flow </li>
                                <li> Symptoms and signs of anaemia from heavy menstrual flow </li>
                                <li> Abdominal distension or pain </li>
                                <li> Changes in urinary habits: frequent urination and sensation of not emptying the bladder completely; or difficulty in passing urine </li>
                                <li> Constipation </li>
                                <li> Backache </li>
                                <li> Swollen leg from deep vein thrombosis </li>
                            </ul>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5>Causes</h5>
                            <p> Each fibroid develops from a single muscle cell in which certain genes have been damaged or altered. The genetic changes lead to a more rapid cell division than usual in response to stimulation of hormones and growth factors. The cell division is also uncontrollable which results in a large number of abnormal muscle cells and the formation of a visible growth. </p>
                            <p> It is quite common for muscle cells from different parts of the uterus to develop these genetic changes over a period of time. This results in the forming of many fibroids on the same uterus. </p>
                            <p> The cause of genetic changes is currently unknown. It is clear that there is no fibroid gene that can be passed from mother to daughters in a direct genetic inheritance manner. There is also no association of fibroids with dietary habits or history of childbearing. </p>
                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> The majority of women have small or moderate size fibroids. In general, these women do not experience any problem from the fibroids and do not require treatment. In other women, the decision on initiation and choice of treatment of fibroids depends on individual circumstances. The treatment available includes the following: </p>
                            <h5> Treatment of heavy menstrual flow </h5>
                            <p> Menstrual flow can be reduced with medication such as tranexamic acid, danazol, progesterone hormone or gonadotrophy releasing hormone analogues. This form of treatment is appropriate when the fibroid is small or moderate in size. It is also more appropriate among women who are close to menopause when treatment may be limited to a short period of time before menopause ensues. This treatment is not a cure of fibroids. </p>
                            <h5> Hysteroscopic resection of fibroid </h5>
                            <p> Submucus fibroid or fibroid polyp can be effectively removed by resection through a hysteroscope. It is a minimally invasive procedure through the vaginal and cervical approach. This technique is suitable for women of any age, including those considering pregnancy in the future. </p>
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