<?php 
session_start();
/* Load Config File */
require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/resources/config.php';
require VENDOR_PATH . '/autoload.php';

require_once ENUMS_PATH . '/Health_Info_Type.php';
require_once HINFO_MOD . '/Health_Info.php';
require_once UTIL_MOD . '/StringUtils.php';
require_once UTIL_MOD . '/ArrayCreation.php';

require_once USER_MOD . '/Account_User.php';
require_once USER_MOD . '/Medical_Personnel.php';
require_once USER_MOD . '/Facility_Admin.php';
require_once USER_MOD . '/Super_Admin.php';
require_once USER_MOD . '/Patient.php';
require_once TIME_MOD . '/Time.php';

require_once FACILITY_MOD . '/Medical_Facility.php';


// Functions
        function retrieve_user(string $type): array {
            $user_arr = array();
            if ($type == 'facilityadmin')
            {
                $type = User_Type::FACIILITY_ADMIN;
            }else if($type == 'medicalpersonnel')
            {
                $type = User_Type::MEDICAL_PERSONNEL;
            } else if ($type == 'patient')
            {
                $type = User_Type::PATIENT;
            }
            $arr = Account_User::retrieve_user_by_type($type, 10);
            foreach ($arr as $a):
                $user_arr[] = initialise_user($a, $type);
            endforeach;
            return $user_arr;
        }

        function initialise_user(array $user, string $type): Account_User {
            switch ($type):
                case User_Type::PATIENT:
                    return Patient::initialise_patient($user);
                case User_Type::MEDICAL_PERSONNEL:
                    return Medical_Personnel::initialise_medical_personnel($user);
                case User_Type::FACIILITY_ADMIN:
                    return Facility_Admin::initialise_facility_admin($user);
            endswitch;
        }
        ?> 
        <?php

       
?>