<?php
 
/*
 * 
 *  Somado - mod_driver
 * 
 *  Przeładowanie po zalogowaniu, podtrzymanie sesji
 * 
 */
  
 
  session_start();  

  require("../www/user.php");
  require("../www/templates.php");

  $user = new User();
  $tpl = new Templates();
  
  echo $tpl->tplLoader();
  
  
  
