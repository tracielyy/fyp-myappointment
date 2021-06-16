<!--
   Developed By FYP-21-S2-24
-->
<!-- This File Is Solely Used For Debugging -->
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require_once UTILS_PATH . '/Time.php';
require_once ENTITIES_PATH . '/Account_User.php';
require_once ENTITIES_PATH . '/Appointment_Record.php';
require_once ENUMS_PATH . '/User_Type.php';
require_once ENUMS_PATH . '/Appointment_Type.php';
require_once FUNCTIONS_PATH . '/PatientFunctions.php';
require_once FUNCTIONS_PATH . '/AccountUserFunctions.php';
?>
<html>
    <head>
        <!-- Title -->
        <title>FYP-21-S2-24: Book Appointment</title>
        <!-- Styling -->
        <?php require COMPONENTS_PATH . '/bootstrap.php' ?>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>
            function dateChange(input, date) {

                var ipt = input.id.split(",");
                var facilityid = ipt[0];
                var appointmenttype = ipt[1];

                console.log("js triggered");
                $.ajax({
                    type: "POST",
                    url: "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>",
                    data: {ajax: 1, set_location: 1, set_appointmenttype: 1, facilityid: facilityid, appointmenttype: appointmenttype, date: date},
                    success: function () {
                        $('#apptform').submit();
                        console.log("post submit");
                        console.log(appointmenttype);
                    },
                    error: function () {
                        console.log("Error Date Change");
                    }
                });
            }

        </script>
    </head>
    <body>
        <?php
        // -- Check If User Is Signed In (When Redirect or Load The Page) -- //
        if (isset($_SESSION["user"])):

            // -- Get Signed In User Information
            $user = unserialize($_SESSION["user"]);
            $user_email = $user->get_email();
            $user_type = $user->get_usertype();

            // -- Check User Type (PATIENT ONLY) --//
            if (User_Type::check_user_type(User_Type::PATIENT, $user_type)):
                // -- Get All Facilities -- //
                $facility_list = AccountUserFunctions::get_all_facilities();


                // -- Used to store correct data
                $appointmentArr = array(
                    'facilityid' => '',
                    'appointmenttype' => '',
                    'date' => '',
                    'slotid' => ''
                );
                $validArr = array();


                // -- When Submit Appointment
                if ($_SERVER["REQUEST_METHOD"] == "POST") :

                    /* Load Data to Array */
                    foreach ($_POST as $key => $value):
                        if (isset($appointmentArr[$key])):
                            $appointmentArr[$key] = htmlspecialchars($value);
                            $validArr[$key] = False; // Set All Field Validation Check As False
                        endif;
                    endforeach;

                    if (isset($_POST['bookappointment'])):


                        // Possible Validation of Email Before Firestore Query
                        /* ------------ Start Validation ------------ */

                        # -- Facility ID
                        if (!empty($appointmentArr['facilityid'])):
                            $validArr['facilityid'] = True;
                        endif;

                        # -- Appointment Type
                        if (!empty($appointmentArr['appointmenttype'])):
                            $validArr['appointmenttype'] = True;
                        endif;

                        # -- Date
                        if (!empty($appointmentArr['date'])):
                            $appointmentArr['date'] = Time::date_format_default($appointmentArr['date']);
                            $validArr['date'] = True;
                        endif;

                        # -- Slot ID
                        if (!empty($appointmentArr['slotid'])):
                            $validArr['slotid'] = True;
                        endif;

                        /* ------------ End Validation ------------ */
                        echo var_dump($validArr);


                        // Can Only Book Appointment When Required Fields Are Filled
                        if (!in_array(FALSE, $validArr)) :
                            
                            # -- Booking Of Appointment -- #
                            $slot_info_arr = explode("~", $appointmentArr['slotid']);
                            $appointmentArr['slotid'] = $slot_info_arr[1];
                            $appointmentArr['time'] = $slot_info_arr[2];
                            $booking_status = PatientFunctions::book_appointment($user_email, $appointmentArr);
                            
                            # -- Make Use Of The Following Message To Show Patient Their Booking Status -- #
                            if ($booking_status):
                                echo "Booking Success";
                            else:
                                echo "You Have Already Book The Slot Previously";
                            endif;
                        else:
                            echo "Booking Fail";
                        endif;

                    endif;
                endif;
                ?>
                <!-- Appointment Booking Form -->
                <form id="apptform" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <!-- Display & Select Location -->
                    <?php
                    if ($facility_list == null):
                        echo '<br><p class="text-center text-muted display-6">No Location</p>';
                    else:
                        foreach ($facility_list as $facility):
                            $facilityid = $facility->get_facilityid();
                            $facilityname = $facility->get_facilityname();
                            ?>
                            <input type="radio" id="<?php echo $facilityid; ?>" name="facilityid" value="<?php echo $facilityid; ?>"
                            <?php
                            if ($appointmentArr['facilityid'] == $facilityid):
                                echo "checked";
                            endif;
                            ?>/>
                            <label for="<?php echo $facilityid; ?>"><?php echo $facilityname; ?></label><br/>
                            <?php
                        endforeach;
                    endif;
                    ?>
                    <button type="submit" name="set_location">Next</button>  
                    <!-- Display & Select Appointment Type --> 
                    <div>
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST") :

                            # -- Setting The Location -- #
                            if (isset($appointmentArr['facilityid'])):
                                $appointmentArr['facilityid'] = htmlspecialchars($_POST['facilityid']);
                            endif;
                            ?>

                            <!-- Specialist Consultation -->
                            <?php $sp_consult = Appointment_Type::SPECIALIST_CONSULTATION; ?>
                            <input type="radio" id="<?php echo $sp_consult; ?>" name="appointmenttype" value="<?php echo $sp_consult; ?>"
                            <?php
                            if ($appointmentArr['appointmenttype'] == $sp_consult):
                                echo "checked";
                            endif;
                            ?>/>
                            <label for="<?php echo $sp_consult; ?>"><?php echo $sp_consult; ?></label><br/>

                            <!-- Doctor Consultation -->
                            <?php $dr_consult = Appointment_Type::DOCTOR_CONSULTATION; ?>
                            <input type="radio" id="<?php echo $dr_consult; ?>" name="appointmenttype" value="<?php echo $dr_consult; ?>"
                            <?php
                            if ($appointmentArr['appointmenttype'] == $dr_consult):
                                echo "checked";
                            endif;
                            ?>/>
                            <label for="<?php echo $dr_consult; ?>"><?php echo $dr_consult; ?></label><br/>

                            <!-- Check Up-->
                            <?php $checkup = Appointment_Type::CHECK_UP; ?>
                            <input type="radio" id="<?php echo $checkup; ?>" name="appointmenttype" value="<?php echo $checkup; ?>"
                            <?php
                            if ($appointmentArr['appointmenttype'] == $checkup):
                                echo "checked";
                            endif;
                            ?>/>
                            <label for="<?php echo $checkup; ?>"><?php echo $checkup; ?></label><br/>

                            <button type="submit" name="set_appointmenttype">Next</button>  
                        </div>


                        <?php
                    endif;
                    ?>

                    <!-- Display & Select The Given Time Slot -->
                    <?php
                    # -- Setting The Appointment Type -- #
                    if (isset($_POST['set_appointmenttype']) || isset($_POST['date'])):

                        if (isset($appointmentArr['appointmenttype']) && isset($appointmentArr['facilityid'])):


                            #-- Get The Next Day -- #
                            $cal_default = Time::CALENDAR_FORMAT_DEFAULT;
                            $next_day = Time::get_enddate(date($cal_default), 1, $cal_default);

                            # -- Allow Date Selection For 6 Mths (est. 180 days) -- #
                            $future_days = 180;
                            $max_date = Time::get_enddate($next_day, $future_days, $cal_default);

                            # -- Check For Some On Change Event -- #
                            if (isset($_POST['date'])):
                                $selected_date = Time::date_format_default($_POST['date']);
                                $appt_date = Time::date_format_change($selected_date, $cal_default);
                            else:
                                $selected_date = Time::date_format_default($next_day);
                                $appt_date = $next_day;
                            endif;
                            $slots = PatientFunctions::get_apptslots($appointmentArr['facilityid'], $appointmentArr['appointmenttype'], $selected_date);

                            # -- View By Dates -- #
                            ?>
                            <label for= "date">Date:</label>
                            <input type= "date" id= "<?php echo $appointmentArr['facilityid'] . "," . $appointmentArr['appointmenttype']; ?>" name= "date"  value="<?php echo $appt_date; ?>" min="<?php echo $next_day; ?>" max="<?php echo $max_date; ?>"  
                                   onchange="dateChange(this, this.value)"><br/>
                                   <?php
                                   # -- Check & Loop All The Available Slots (Only Show Not Full Slots) -- #
                                   if (empty($slots)):
                                       echo "No Slots Available<br/>";
                                   else:
                                       foreach ($slots as $slot):
                                           $sid = $slot->get_appointmentschedule()->get_date() . "~" . $slot->get_slotid() . "~" . $slot->get_appointmentschedule()->get_time();
                                           ?>
                                    <input type = "radio" id = "<?php echo $sid; ?>" name = "slotid" value = "<?php echo $sid; ?>" 
                                    <?php
                                    if ($appointmentArr['slotid'] == $sid):
                                        echo "checked";
                                    endif;
                                    ?>/>
                                    <label for = "<?php echo $sid; ?>"><?php echo $slot->get_slot_description(); ?></label><br/>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                            <button type="submit" name="bookappointment">Book Now</button>  
                            <?php
                        endif;
                    endif;
                    ?>
                </form>

                <?php
            else:
                echo "Wrong User Type";
            endif; # Check User Type
        else:
            echo "User Not Logged In";
        endif;
        ?>
    </body>
</html>

