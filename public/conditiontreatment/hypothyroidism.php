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
                <title> Hypothyroidism </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Hypothyroidism</b></h1>
                            <h3>Hypothyroidism - What it is</h3>
                            <p> The thyroid gland is a butterfly-shaped gland in the middle of the neck. It produces thyroid hormones, which are important for maintaining our body’s metabolism. </p>
                            <p> Hypothyroidism means that the thyroid gland is underactive, and is not producing enough thyroid hormone. This may cause a “slowing down” of many bodily functions. </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> Symptoms of hypothyroidism may include the following: </p>
                            <ul>
                                <li> Cold intolerance (you feel cold more easily compared to other people) </li>
                                <li> Weight gain </li>
                                <li> Fatigue and weakness </li>
                                <li> Constipation </li>
                                <li> Abnormal menses or heavy / prolonged menses </li>
                                <li> Depression or irritability </li>
                                <li> Hair loss </li>
                                <li> Dry, rough skin </li>
                                <li> Muscle aches or cramps </li>
                            </ul>
                            <p> Occasionally, some patients with hypothyroidism have no symptoms, or symptoms that are subtle enough to go unnoticed. </p>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5>Causes</h5>
                            <p> There are many reasons why hypothyroidism occurs. The more common causes include the following: </p>
                            <ul>
                                <li> Hashimoto’s disease </li>
                                <p> This is the most common cause of hypothyroidism. It is an “autoimmune”, or “self-attacking-self” disease. It occurs when the body’s immune system mistakenly attacks thyroid cells and damages them. Over time, the thyroid gland fails, causing hypothyroidism. </p>
                                <li> Radioactive iodine treatment </li>
                                <p> Hypothyroidism often develops as a desired treatment goal after the use of radioactive iodine treatment for high thyroid hormone production (hyperthyroidism). </p>
                                <li> Thyroid operation </li>
                                <p> Previous thyroid surgery can cause hypothyroidism, especially if most of the thyroid gland has been removed. </p>
                                <li> Medication </li>
                                <p> Some medication including amiodarone and lithium can cause hypothyroidism </p>
                                <li> Subacute thyroiditis </li>
                                <p> This causes a painful inflammation of the thyroid. This causes a period of hyperthyroidism (high thyroid hormone level) as the damaged cells leak their hormone supply into the bloodstream, but this is followed by a period of hypothyroidism. </p>
                                <li> Congenital hypothyroidism </li>
                                <p> A baby may be born with an insufficient amount of thyroid tissue or a problem that does not allow normal thyroid hormone production </p>
                            </ul>
                            <p> Less commonly, hypothyroidism can also be caused by problems in the pituitary gland, the “master hormone gland” that controls the thyroid gland. </p>
                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> Hypothyroidism is treated with thyroxine replacement. This boosts the amount of thyroid hormone in the bloodstream back to normal levels. It is given as a small pill daily. It has very few side effects and almost no allergic reactions. The correct dose is determined with the help of blood tests.  </p>
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