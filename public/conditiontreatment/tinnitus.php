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
                <title> Tinnitus </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Tinnitus</b></h1>
                            <h3>Tinnitus - What it is</h3>
                            <p> Tinnitus is the perception of sounds not generated in the external environment. It is common and many people will experience it at some point in their lives. </p>
                            <p> People with tinnitus often describe the sound as ringing, buzzing, swishing or clicking.While it can be disturbing to someone who has it, for many with tinnitus it is not a serious problem. Some, however, may require medical or surgical treatment. </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> ​A person with tinnitus often complains of sounds of ringing, roaring, buzzing or chirping of crickets that may involve one or both ears. There may also be complaints of pulsatile tinnitus with associated symptoms that include hearing loss and dizziness. </p>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5>Causes</h5>
                            <p>In many people, tinnitus is caused by one of the following:</p>
                            <ul>
                                <li><b>Ear infection or ear canal blockage.</b> Your ear canals can become blocked with a buildup of fluid (ear infection), earwax, dirt or other foreign materials. A blockage can change the pressure in your ear, causing tinnitus.</li>
                                <li><b>Head or neck injuries.</b> Head or neck trauma can affect the inner ear, hearing nerves or brain function linked to hearing. Such injuries usually cause tinnitus in only one ear.</li>
                                <li><b>Medications.</b> A number of medications may cause or worsen tinnitus. Generally, the higher the dose of these medications, the worse tinnitus becomes. Often the unwanted noise disappears when you stop using these drugs.</li>
                            </ul>

                            <h5>Risk Factors</h5>
                            <p>Anyone can experience tinnitus, but these factors may increase your risk:</p>
                            <ul>
                                <li><b>Age.</b> As you age, the number of functioning nerve fibers in your ears declines, possibly causing hearing problems often associated with tinnitus.</li>
                                <li><b>Tobacco and alcohol use.</b> Smokers have a higher risk of developing tinnitus. Drinking alcohol also increases the risk of tinnitus.</li>
                                <li><b>Loud noise exposure.</b> Loud noises, such as those from heavy equipment, chain saws and firearms, are common sources of noise-related hearing loss. Portable music devices, such as MP3 players, also can cause noise-related hearing loss if played loudly for long periods.</li>
                            </ul>
                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> Treatment for tinnitus depends on the underlying cause. In most cases, tinnitus is caused by damage to the cochlea. There is normally no need for treatment in such cases other than reassurance. If the patient is extremely bothered by the tinnitus, there are a number of treatment options. </p>
                            <p> <b>Relaxation</b> exercises help to control muscle groups and circulation throughout the body. This may reduce the intensity of tinnitus in some individuals. </p>
                            <p> <b>Masking</b> of the noise with a competing sound at a constant low level, such as a ticking clock, radio static (white noise) or soothingsounds (rain, running water) may make it less noticeable, since tinnitus is usually more bothersome in quiet surroundings. Hearing aids may reduce tinnitus while the patients are wearing them. </p>
                            <p> <b>Medications</b> that can be prescribed include tricyclic antidepressants and betahistine. Tricyclic antidepressants may have a role especially in patients with concomitant depression. Betahistine is a vasodilator that may improve blood circulation in the cochlea. Herbal medications and vitamins that have been advocated are gingko biloba and Vitamin B. </p>
                            <p> <b>Tinnitus Retraining Therapy (TRT)</b> is a multimodality therapy that incorporates counselling, patient education and the use of low level white noise tinnitus maskers. This therapy has shown significant promising results in certain studies. Where the tinnitus is caused by other rare problems (such as a tumor or aneurysm), treatment of the tinnitus involves fixing the main issue. </p>
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