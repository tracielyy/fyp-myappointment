<?php
/*
 *  @author: tracieqwynn
 */
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';
require_once TIME_MOD . '/Time.php';
require_once ENUMS_PATH . '/Health_Info_Type.php';
require_once HINFO_MOD . '/Health_Info.php';
require_once UTIL_MOD . '/StringUtils.php';
/*
 * EDIT HEALTH INFO
 */

if (!isset($_SESSION['user'])):
    header("Location:/"); # -- REDIRECT BACK TO THE HOME PAGE
else:
    $user = unserialize($_SESSION["user"]);
    if ($user->get_usertype() !== User_Type::FACIILITY_ADMIN):
        header("Location:/"); # -- REDIRECT BACK TO THE HOME PAGE
    else: # -- ONLY ALLOW FACILITY ADMIN

        if ($_SERVER["REQUEST_METHOD"] == "GET"):

            function valid_post_vars(string $id): bool|Health_Info {
                $health_info_obj = Health_Info::retrieve_health_info_by_id($id);
                if ($health_info_obj === null):
                    return false;
                endif;
                return $health_info_obj;
            }
            ?><!DOCTYPE html>
            <html lang="en">
                <head>
                    <link href="https://cdn.jsdelivr.net/npm/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />
                    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <title>Edit Health Info</title>
                    <?php
                    include TEMPLATES_PATH . '/bootstrap.php';
                    include_once TEMPLATES_PATH . '/navbar.php';
                    ?>
                </head>
                <body>
                    <?php
                    if (!isset($_GET['id'])):
                        ?>
                        <!-- SHOW INVALID PAGE -->
                        <div>
                            Invalid Page
                        </div>
                        <?php
                    else:
                        $id = $_GET['id'];
                        # Check if the id exist in database for edit
                        $health_info = valid_post_vars($id);
                        if (!$health_info):
                            ?>
                            <div>
                                Invalid POST VARS
                            </div>
                            <?php
                        else: /* If The Variables Exists In The Database */
                            $health_info_form = array(
                                'id' => $health_info->get_id(),
                                'title' => $health_info->get_title(),
                                'descriptions' => $health_info->get_descriptions(),
                            );
                            ?>
                            <form id="health_info_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                <!-- Hidden ID -->
                                <input type="hidden" id="id" name="id" value="<?php echo $health_info_form['id']; ?>"/>
                                <!-- Title -->
                                <input type="text" id="title" name="title" placeholder="Title" value="<?php echo $health_info_form['title']; ?>" style="width:300px;"/><br/><br/>
                                <!-- Description -->
                                <textarea style="resize:none;" rows="10" cols='50' name="descriptions" id="descriptions"
                                          placeholder="Descriptions" ><?php echo $health_info_form['descriptions']; ?></textarea><br/><br/>
                                <!-- Submit Btn -->
                                <button type="button" name="submit_health_info" id="submit_health_info" class="action back btn btn-sm btn-outline-primary">
                                    Save Changes
                                </button>
                            </form>
                        </body>
                        <!-- FORM TO EDIT HEALTH INFO -->
                    </body>
                    <!-- SOME JQUERY FUNCTIONS -->
                    <script>
                        // On click for saving of edited health info
                        $('#submit_health_info').on('click', function () {

                            $.ajax({
                                type: "POST",
                                url: "<?php echo FADMIN_WEB . "/edit/func/validatehealthinfo.php"; ?>",
                                data: {
                                    ajax_submit_health_info: true,
                                    id: $("#id").val(),
                                    title: $('#title').val(),
                                    descriptions: $('#descriptions').val()
                                },
                                success: function (data) {
                                    console.log('initiatiate health info change');
                                    console.log(data);
                                },
                                error: function () {

                                }
                            });
                        });
                    </script>
                    </html>
                <?php
                endif; # -- END FOR VALID VARS
            endif; # -- END GET ID CHECK ISSET
        endif; # -- END GET REQUEST
    endif; # -- END USER TYPE CHECK
endif; # -- END SESSION CHECK
?>
