<?php

/*  Main Configuration File: Creating Constants For Heavily Used Paths */
define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));
define("ENTITIES_PATH", realpath(dirname(__FILE__) . '/entities'));

define("ENUMS_PATH", realpath(dirname(__FILE__) . '/enums'));

############## -- CONFIGURATIONS FOR MODULES -- ################
define("DB_MOD", realpath(dirname(__FILE__) . '/modules/database'));
define("AUTH_MOD", realpath(dirname(__FILE__) . '/modules/authentication'));
define("EMAIL_MOD", realpath(dirname(__FILE__) . '/modules/email'));
define("USER_MOD", realpath(dirname(__FILE__) . '/modules/user'));
define("FACILITY_MOD", realpath(dirname(__FILE__) . '/modules/facility'));
define("UTIL_MOD", realpath(dirname(__FILE__) . '/modules/utility'));
define("APPT_MOD", realpath(dirname(__FILE__) . '/modules/appointment'));
define("TIME_MOD", realpath(dirname(__FILE__) . '/modules/time'));
define("SECURE_MOD", realpath(dirname(__FILE__) . '/modules/security'));
define("MEDDOC_MOD", realpath(dirname(__FILE__) . '/modules/medicaldocument'));

############## -- CONFIGURATIONS FOR PAGES -- ################
define("LOGIN_WEB", '/login');
define("REGISTER_WEB", '/register');
define("APPT_WEB", '/appointment');
define("FORGOT_PW_WEB", '/forgotpassword');


?>

