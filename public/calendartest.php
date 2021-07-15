<!-- This is web app's home page -->
<!DOCTYPE html>
<?php
session_start();
/* Load Config File */
require_once '../resources/config.php';
require '../vendor/autoload.php';
require_once EMAIL_MOD . '/Email.php';
require_once UTIL_MOD . '/StringUtils.php';
require_once UTIL_MOD . '/Regex.php';
require_once AUTH_MOD . '/Authentication.php';
require_once USER_MOD . '/Account_User.php';

include TEMPLATES_PATH . '/bootstrap.php';
include_once TEMPLATES_PATH . '/navbar.php';
?>
<html>
    <head>
        <!-- This Is The Home Page -->
        <title>FYP-21-S2-24: Home</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script src="https://cdn.jsdelivr.net/gh/jquery/jquery@3.2.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/wrick17/calendar-plugin@master/calendar.min.js"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/wrick17/calendar-plugin@master/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/wrick17/calendar-plugin@master/theme.css">

    </head>
    <body>
    <div class="container mt-5">
    <div class="calendar-wrapper"></div>
    </div>
    </body>
    <script>
        function selectDate(date) {
  $('.calendar-wrapper').updateCalendarOptions({
    date: date
  });
}

var defaultConfig = {
  weekDayLength: 1,
  date: new Date(),
  onClickDate: selectDate,
  showYearDropdown: true,
  startOnMonday: true,
  disable: function (date) { 
      var dateYesterday = new Date();
      dateYesterday.setDate(dateYesterday.getDate()-1)
    return date < dateYesterday; // This will disable all dates before today
  },
};

$('.calendar-wrapper').calendar(defaultConfig);
        </script>
</html>