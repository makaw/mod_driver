<?php

/*
 * 
 *  Somado - mod_driver
 * 
 *  Serwis JSON get_orders - lista zamówień w dostawie
 * 
 */
 
  session_start();  
  
  require_once("../www/user.php");
  require("../www/config.php");
  
  $user = new User();
  if (!$user->isAuthorized()) { echo '{"error":{"text":"unauthorized"}}'; die(); }
  
  $did = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  $driver_id = $user->getUID();

  $query = "SELECT o.id, o.customer_desc, o.customer_label, o.order_number, o.done, "
		  ."TRUNCATE(o.order_weight, 1) AS weight, "
	      ."TRUNCATE(o.distance/1000.0, 1) AS distance, "
	      ."SEC_TO_TIME(ROUND(o.time)) AS time "
	      ."FROM dat_deliveries_drivers_orders AS o INNER JOIN dat_deliveries_drivers AS d "
		  ."ON o.delivery_driver_id=d.id "
		  ."WHERE d.driver_user_id = :driver_id AND d.delivery_id=:did  ORDER BY o.id;";

  try {
	
	$dbh = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->query("SET NAMES 'utf8';");
	$stmt = $dbh->prepare($query);  
	$stmt->bindParam("driver_id", $driver_id);
	$stmt->bindParam("did", $did);
	$stmt->execute();
	$orders = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"items":'. json_encode($orders) .'}'; 
	
  } catch(PDOException $e) {
	
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	
  }


