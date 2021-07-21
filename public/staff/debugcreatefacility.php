<?php
session_start();
/* Load Config File */
require_once '../../resources/config.php';
require '../../vendor/autoload.php';
require_once TIME_MOD . '/Time.php';
require_once FACILITY_MOD . '/Operating_Hours.php';
require_once DB_MOD . '/DbQuery.php';
require_once DB_MOD . '/Database.php';
require_once DB_MOD . '/DbStorage.php';


require_once APPT_MOD . '/Normal_Slot.php';
require_once APPT_MOD . '/Special_Slot.php';

require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Patient.php';

/* THIS TO BE CHANGED TO BE CLIENT REGISTRATION BASIS */

if (!isset($_SESSION['user'])):
    echo '<script>window.location.href = "./../";</script>'; # -- REDIRECT BACK TO THE HOME PAGE
else:
    $user = unserialize($_SESSION["user"]);
    if ($user->get_usertype() !== User_Type::SUPER_ADMIN):
        echo '<script>window.location.href = "./../";</script>'; # -- REDIRECT BACK TO THE HOME PAGE
    else: # -- ONLY ALLOW SUPER ADMIN
        if ($_SERVER["REQUEST_METHOD"] == "POST"):

            /* ---------  FUNCTIONS FOR CREATING FACILITY ---------  */

            function save_facility_icon(array $icon_info) {
                $file_name = $icon_info["name"];
                $size = ($icon_info["size"] / 1024); # In kb
                $type = $icon_info["type"];
                $tmp_path = $icon_info["tmp_name"];

                echo "Upload: " . $file_name . "<br />";
                echo "Type: " . $type . "<br />";
                echo "Size: " . $size . " Kb<br />";
                echo "Stored in: " . $tmp_path;
                echo "<img src='{$_FILES["facility_icon"]["tmp_name"]}' />";
                $db_storage = new DbStorage();
                $db_storage->store_data($type, (int) $size, $tmp_path, 'facility/facilityicon/' . $file_name);
            }

            function facility_account_creation(array $facility_info) {

                # STEP 1: Insert Facility (save image & create new facility record in database)
                # STEP 2: Get The Facility From The Database After Insert
                # STEP 3: Insert Facility Admin
            }

            /* ---------  END OF FUNCTION FOR CREATING FACILITY ---------  */

            if (isset($_FILES['facility_icon'])):

                if ($_FILES["facility_icon"]["error"] > 0):
                    echo "Error: " . $_FILES["facility_icon"]["error"] . "<br />";
                else :
                    $icon = $_FILES['facility_icon'];
                endif; # -- END OF FACILITY ICON ERROR CHECK

                echo "here";


            endif; # -- END OF FACILITY ICON

        endif; # -- END OF POST REQUEST
        ?><!DOCTYPE html>
        <html>
            <head>
                <!-- Title -->
                <title>FYP-21-S2-24</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <!-- Styling -->
        <?php include TEMPLATES_PATH . '/bootstrap.php'; ?>
                <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css'>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js'></script>
                <script>
                    function add_specialisation() {
                        var specialisation_input = $('#specialisation_input').val();
                        var specialisation = `<div class="list-group-item list-group-item-action timebtn" id="${specialisation_input}" name="slotid" value="${specialisation_input}" >${specialisation_input}</div>`;
                        $('#display_specialisation').append(specialisation); // Append  Each Specialisation 
                        $('#specialisation_input').val("");
                    }
                </script>
            <body>
                <form id="facility_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
                    <!-- FACILITY DETAILS SECTION -->
                    <div>
                        <input type="text" id="facilityname" name="facilityname" placeholder="Facility Name"/><br/><br/>
                        <input type="text" id="contactnumber" name="contactnumber" placeholder="Facility Contact Number"/><br/><br/>
                        <!-- Operating Hours -->
                        <input type="checkbox" id="is24hours" name="is24hours" value="true">
                        <label for="is24hours">Is 24 Hours?</label><br>
                        <!-- Specialisation -->
                        <div ><label for="specialisation_input">Specialisation(s):</label>
                            <input type="text"  name="specialisation_input" id="specialisation_input">
                            <button type="button" id="add_spec" onclick="add_specialisation()" ><b>Add</b> <span class="fa fa-plus"></span></button> 
                            <div id="display_specialisation">
                            </div>
                        </div>

                        <div>
                            <label for="facility_icon"> Select Icon: </label>
                            <input type="file" id="facility_icon" name="facility_icon" accept=".png"/>
                        </div>
                    </div> <!-- END OF FACILITY DETAILS -->

                    <!-- FACILITY ADMIN DETAILS  SECTION -->
                    <div>
                        <input type="text" id="adminname" name="adminname" placeholder="Admin Name"/><br/><br/>
                        <input type="text" id="email" name="email" placeholder= "Email"/><br/><br/>
                        <input type="password" id="password" name="password" placeholder="Password"/><br/><br/>
                        <input type="password" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password"/><br/><br/>

                    </div><!-- FACILTIY ADMIN DETAILS -->

                    <br/><br/>
                    <button type="submit" name="add_facility" class="action back btn btn-sm btn-outline-primary">Add Facility</button>

                </form>
            </body>
    <?php
    endif; # -- END SUPER ADMIN CHECK
endif; # -- END USER SESSION CHECK 
?>
</html>