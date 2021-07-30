<?php
/*
 *  @author: tracieqwynn
 */

session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';

require_once ENUMS_PATH . '/Health_Info_Type.php';
require_once HINFO_MOD . '/Health_Info.php';
require_once UTIL_MOD . '/StringUtils.php';
/*
 * CREATE NEW HEALTH INFO
 */

if (!isset($_SESSION['user'])):
    header("Location:/"); # -- REDIRECT BACK TO THE HOME PAGE
else:
    $user = unserialize($_SESSION["user"]);
    if ($user->get_usertype() !== User_Type::FACIILITY_ADMIN):
        header("Location:/"); # -- REDIRECT BACK TO THE HOME PAGE
    else: # -- ONLY ALLOW FACILITY ADMIN

        $health_info_types = Health_Info_Type::get_constants(); # Retrieve The Constant Types

        $health_info_form = array(
            'title' => '',
            'descriptions' => '',
            'type' => ''
        );

        if ($_SERVER["REQUEST_METHOD"] == "POST"):

            if (isset($_POST['submit_health_info'])):
                /* Load Data to Array */
                foreach ($_POST as $key => $value) :
                    if (isset($health_info_form[$key])) :
                        $health_info_form[$key] = htmlspecialchars($value);
                        $validArr[$key] = False; // Set All Field Validation Check As False
                        $err_msg[$key] = "";
                    endif;
                endforeach;

                ###### -- VALIDATION -- ######
                foreach ($health_info_form as $key => $value):

                    # Step 1: Check Empty
                    $value = StringUtils::trim_string($value);
                    if (!empty($value)):

                        # Step 2: Other Validations
                        if ($key == 'type'):

                            $validArr[$key] = Health_Info_Type::validate_type($value);

                            # Check For Valid Type Selection
                            if (!$validArr[$key]):
                                $err_msg[$key] = "Invalid Input";
                            endif;
                        else:
                            $validArr[$key] = true;
                        endif;

                    else:
                        # Some Error Message
                        $err_msg[$key] = "Field cannot be blank";
                    endif;

                endforeach;
                ###### -- END VALIDATION -- ######
                if (!in_array(FALSE, $validArr)) :
                    # Add To Database
                    Health_Info::create_healthinfo($health_info_form);
                endif;

            endif; # -- END CHECK FOR SUBMIT BTN TRIGGER
        endif; # -- END POST REQUEST
        ?><!DOCTYPE html>
        <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Add New Health Info</title>
                <?php require TEMPLATES_PATH . '/bootstrap.php' ?>
            </head>
            <body>
                <!-- Navigation -->
                <?php require TEMPLATES_PATH . '/navbar.php' ?>

                <!-- FORM TO CREATE HEALTH INFO -->
                <form id="health_info_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <!-- Title -->
                    <input type="text" name="title" placeholder="Title" value="<?php echo $health_info_form['title']; ?>" /><br/><br/>
                    <!-- Description -->
                    <textarea style="resize:none;" rows="10" cols='50' name="descriptions" 
                              placeholder="Descriptions" ><?php echo $health_info_form['descriptions']; ?></textarea><br/><br/>
                    <!-- Type -->
                    <select name="type">
                        <?php
                        foreach ($health_info_types as $type):
                            ?>
                            <!-- Each Type -->
                            <option value="<?php echo $type; ?>" 
                            <?php
                            if ($type == $health_info_form['type']) :
                                echo "selected";
                            endif;
                            ?>><?php echo $type; ?>
                            </option>
                            <!-- End Each Type -->
                            <?php
                        endforeach;
                        ?>
                    </select><br/><br/>
                    <!-- Submit Btn -->
                    <button type="submit" name="submit_health_info" class="action back btn btn-sm btn-outline-primary">
                        Add Health Info
                    </button>
            </body>
        </html>
    <?php
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>