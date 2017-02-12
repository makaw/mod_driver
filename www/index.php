<?php

/*
 * 
 *  Somado - mod_driver
 *  
 */
 
  
  session_start();  
  
  // próba wyłączenia cache'owania stron
  header("Cache-Control: no-cache, no-store, must-revalidate"); 
  header("Pragma: no-cache");  
  header("Expires: 0");


  require("../www/user.php");
  require_once("../www/templates.php");
 

  $tpl = new Templates();
  $user = new User();
  if (!$user->isAuthorized()) {
	  	
	$err = isset($_GET['l']) && $_GET['l']==1;  	
	echo $tpl->tplLoginForm($err);
	die();  
	  
  }
  
  $act = isset($_REQUEST['act']) ? $_REQUEST['act'] : "";
  $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
  
  switch ($act) {
   
    default:  
    case "dl" : echo $tpl->tplDeliveriesList();  break;
    case "dd" : echo $tpl->tplDeliveryDetails($id);  break;
    case "dm" : echo $tpl->tplDeliveryDetailsMap($id); break;
  
  }
  
  
