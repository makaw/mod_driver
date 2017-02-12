<?php

/*
 * 
 *  Somado - mod_driver
 * 
 *  Serwis JSON get_user - informacja o użytkowniku
 * 
 */
 
  
  session_start();  
  
  
  require_once("../www/user.php");
  require("../www/config.php");
  
  $user = new User();
  if (!$user->isAuthorized()) { echo '{"error":{"text":"unauthorized"}}'; die(); }
  
 
  echo '{"items":'. json_encode(array('user_info' => $user->toString())). '}';
	

