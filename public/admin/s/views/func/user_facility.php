
<?php
   require_once 'userdata.php';
   require_once TIME_MOD . '/Time.php';
   
   $user_list = retrieve_user('facilityadmin');
   $html_array = array();
  
   foreach($user_list as $user)
   {   
       $facilityid = $user->get_facility();
       $array = array($user->get_adminname(),$user->get_email(),$facilityid->get_facilityname(),$facilityid->get_address(),$facilityid->get_contactnumber());
       array_push($html_array,json_encode($array));
   }
   $html_text = implode(',',$html_array);
   
   $html = '{"data":['.$html_text.']}';
   
   echo $html;
?>