
<?php
   require_once 'userdata.php';
   require_once TIME_MOD . '/Time.php';
   
   $user_list = retrieve_user('patient');
   $html_array = array();
  
   foreach($user_list as $user)
   {   
       $array = array($user->get_firstname(),$user->get_lastname(),$user->get_gender());
       array_push($html_array,json_encode($array));
   }
   $html_text = implode(',',$html_array);
   
   $html = '{"data":['.$html_text.']}';
   
   echo $html;
?>