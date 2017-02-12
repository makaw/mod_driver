<?php

/*
 * 
 *  Somado - mod_driver
 * 
 *  Serwis update_order - zmiana stanu zamówienia na dostarczone i oznaczenie doręczenia w dostawie
 * 
 */
 
  session_start();  
  
 
  require("../www/user.php");
  require_once("../www/config.php");

  $user = new User();
  if (!$user->isAuthorized()) { echo '{"error":{"text":"unauthorized"}}'; die(); }
  
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  $driver_id = $user->getUID();
  $state_done = $ORDER_STATE_DONE;

  try {
	
    $dbh = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);	
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->query("SET NAMES 'utf8';");

    // aktualizacja stanu zamówienia w dostawie
    $query = "UPDATE dat_deliveries_drivers_orders SET done = 1 WHERE id=:id ;";	
    $stmt = $dbh->prepare($query);  
    $stmt->bindParam("id", $id);
    $stmt->execute();		
	
    exit();
	
	
  } catch(PDOException $e) {
	
    exit($e->getMessage());
	
  }


