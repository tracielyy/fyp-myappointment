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
                <title> Kidney Failure </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Kidney Failure</b></h1>
                            <h3>Kidney Failure - What it is</h3>
                            <p> The kidneys are a pair of bean-haped organs in the back of the body. Each kidney is attached to the bladder, which is a distensible bag that collects urine. The kidneys make the urine, which flows downward through two tubes called the ureters, and collects in the bladder.  Kidneys that function normally keep in balance many things in the body by altering the composition of urine that they produce. </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> The kidneys have a large reserve and a large amount of kidney must be damaged even before a person has symptoms of renal disease. For this reason a patient may have significant kidney damage but still feel perfectly well, see a doctor only very late in the course of his disease. A patient with mild kidney failure may initially not feel anything at all and in fact may feel totally well. However, as the kidney disease progresses, symptoms become more apparent. Patients may develop: </p>
                            <ul>
                                <li> High blood pressure </li>
                                <li> Swelling of the legs (called oedema) </li>
                                <li> Breathlessness </li>
                                <li> General symptoms of poor sleep, loss of appetite and lethargy </li>
                                <li> A bad smell in the breath called a uraemic fetor </li>
                                <li> Cramps </li>
                                <li> Numbness of the feet </li>
                                <li> Passing a lot of urine especially at night (called nocturia), or conversely too little urine.  </li>
                                <li> Chronic generalised itch </li>
                                <li> Blood in the urine, which usually reflects the underlying kidney disease </li>
                                <li> Soapy urine or frothy urine, which reflects the presence of protein in the urine </li>
                            </ul>
                            <p> Not all patients develop all these symptoms. Some develop these symptoms during different stages of their disease. Also, it is important to realise that these symptoms do not necessarily only mean that one has kidney disease. Each disease affects each patient differently and to different extent. </p>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5>Causes</h5>
                            <p> There are many causes of kidney disease and these affect the kidney to different degrees, causing them to fail at different rates. Some of these are inherited, and while others are related to existing conditions such as diabetes, and other inflammatory conditions or infections. A list of causes of kidney failure is provided. </p>
                            <ul>
                                <li> Diabetic nephropathy </li>
                                <li> Chronic glomerulonephritis </li>
                                <li> Polycystic kidney disease </li>
                                <li> Lupus Nephritis </li>
                                <li> Reflux nephropathy </li>
                            </ul>

                            <h5>Risk Factors</h5>
                            <p>Factors that may increase your risk of kidney disease include:</p>
                            <ul>
                                <li>Diabetes</li>
                                <li>High blood pressure</li>
                                <li>Obesity</li>
                                <li>Smoking</li>
                            </ul>
                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> Once kidney failure reaches a certain degree, it usually will progress to end stage kidney failure. Nevertheless, there are some common treatment that can benefit most patients with established kidney disease. </p>
                            <ul>
                                <li> Treat hypertension (high blood pressure) </li>
                                <li> Anti-proteinuric therapy </li>
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