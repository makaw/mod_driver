<?php

/*
 * 
 *  Somado - mod_driver
 * 
 *  Serwis JSON get_route - lista koordynat geometrii trasy
 * 
 */
 
 
 
  session_start();   
    
  require("../www/user.php");
  require("../www/config.php");
  
  $user = new User();
  if (!$user->isAuthorized()) { echo '{"error":{"text":"unauthorized"}}'; die(); }
  
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  $additional = (isset($_GET['a']) && $_GET['a']>0) ? 1 : 0;
  $driver_id = $user->getUID();
  
  $query = "SELECT g.longitude, g.latitude, o.id FROM dat_deliveries_drivers AS d "
		  ."INNER JOIN dat_deliveries_drivers_orders AS o ON o.delivery_driver_id = d.id "
		  ."INNER JOIN dat_deliveries_orders_geom AS g ON g.delivery_order_id = o.id "
		  ."WHERE d.delivery_id = :id AND d.driver_user_id = :uid AND g.additional = :additional ORDER BY g.id";
  
  try {
	
	$dbh = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->query("SET NAMES 'utf8';");
	$stmt = $dbh->prepare($query);  
	$stmt->bindParam("id", $id);
	$stmt->bindParam("uid", $driver_id);
	$stmt->bindParam("additional", $additional);
	$stmt->execute();
	$points = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"items":'. json_encode($points) .'}'; 
	
  } catch(PDOException $e) {
	
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	
  }
  
 
