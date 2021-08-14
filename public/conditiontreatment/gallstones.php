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
                <title> Gallstones </title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" type="text/css" href="../css/conditioncss.css">
            </head>
            <body>
                <main>
                    <script type="text/javascript" src="../js/medjs.js"></script>
                    <div>
                        <section id="what-it-is">
                            <h1><b>Gallstones</b></h1>
                            <h3>Gallstones - What it is</h3>
                            <p> The gallbladder stores bile and releases bile into the intestine, where it helps to digest fat at meal times. The gallbladder does not produce bile. Bile is produced by the liver. If the gallbladder is surgically removed, the liver continues to produce bile which similarly flows into the intestines. </p>
                            <p> Gallstones generally refer to stones that are found in the gallbladder. Gallstones are more common in females than males, in persons that are overweight or those with hemolytic diseases. Gallstones form when the amount of bile and other fluid in the gallbladder become unbalanced. When this happens, some of the chemicals become solid and form stones. </p>
                        </section>

                        <section id="symptom">
                            <h3>Symptoms</h3>
                            <p> Often there are no symptoms that arise due to the gallstones (asymptomatic), and many patients only come to know that they have gallstones when they get a scan done for other reasons, for example during a health screening. When symptoms arise, they can range from discomfort after eating, especially with fatty food, to severe cramping pains over the middle and right of the upper abdominal area. Occasionally, complications can occur due to the gallstones such as infection of the gallbladder or blockage of the bile duct. In such cases, fever and jaundice (yellowing of the eyes) may be experienced in addition to the pain. Migration of the gallstones through the bile duct may also cause blockage of the pancreatic duct which leads to inflammation of the pancreas known as pancreatitis. </p>
                        </section>

                        <section id="causes">
                            <h3>Causes and Risk Factors</h3>
                            <h5>Causes</h5>
                            <p> It's not clear what causes gallstones to form. Doctors think gallstones may result when:</p>
                            <ul>
                                <li><b>Your bile contains too much cholesterol.</b> Normally, your bile contains enough chemicals to dissolve the cholesterol excreted by your liver. But if your liver excretes more cholesterol than your bile can dissolve, the excess cholesterol may form into crystals and eventually into stones.</li>
                                <li><b>Your bile contains too much bilirubin.</b> Bilirubin is a chemical that's produced when your body breaks down red blood cells. Certain conditions cause your liver to make too much bilirubin, including liver cirrhosis, biliary tract infections and certain blood disorders. The excess bilirubin contributes to gallstone formation.</li>
                                <li><b>Your gallbladder doesn't empty correctly.</b> If your gallbladder doesn't empty completely or often enough, bile may become very concentrated, contributing to the formation of gallstones.</li>
                            </ul>
                            <h5> Risk Factors </h5>
                            <p>Factors that may increase your risk of gallstones include:</p>
                            <ul>
                                <li>Being pregnant</li>
                                <li>Diabetes</li>
                                <li>Family history of gallstones</li>
                                <li>Taking medications that contain estrogen, such as oral contraceptives or hormone therapy drugs</li>
                                <li>Liver disease</li>
                            </ul>
                        </section>

                        <section id="treatment">
                            <h3>Treatment</h3>
                            <p> Surgery is not necessary in most cases without symptoms as the risk of the surgery is more than the risk of developing symptoms and complications from the gallstones. Once gallstones become symptomatic, they tend to recur and can become worse. Thus surgery is advocated when symptoms develop, or when the patient develops complications from the gallstones such as infection of the gallbladder, obstruction of the bile duct or inflammation of the pancreas due to a stone that had blocked the pancreatic duct. </p>
                            <p> Surgery to remove the gallbladder is the standard treatment for gallstones. Surgery to remove just the gallstones and leave the gallbladder intact is not recommended as it does not treat the underlying diseased gallbladder. </p>
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