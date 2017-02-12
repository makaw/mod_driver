<?php

/*
 * 
 *  Somado - mod_driver
 * 
 *  Serwis JSON get_route_labels - lista koordynat i etykiet punktów odbioru towaru
 * 
 */
 
  session_start();  
    
  require("../www/user.php");
  require("../www/config.php");
  
  $user = new User();
  if (!$user->isAuthorized()) { echo '{"error":{"text":"unauthorized"}}'; die(); }
  
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  $driver_id = $user->getUID();
  
  $query = "SELECT o.customer_longitude AS longitude, o.customer_latitude AS latitude, o.customer_label AS label, "
		  ."CONCAT(o.customer_label, ') ', o.customer_desc, "
		  ."IF (o.order_number<>'', CONCAT('<br/>Zamówienie nr ', o.order_number), '')) AS description "
		  ."FROM dat_deliveries_drivers AS d "
		  ."INNER JOIN dat_deliveries_drivers_orders AS o ON o.delivery_driver_id = d.id "
		  ."WHERE d.delivery_id = :id AND d.driver_user_id = :uid ORDER BY o.id";
  
  try {
	
	$dbh = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->query("SET NAMES 'utf8';");
	$stmt = $dbh->prepare($query);  
	$stmt->bindParam("id", $id);
	$stmt->bindParam("uid", $driver_id);
	$stmt->execute();
	$points = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"items":'. json_encode($points) .'}'; 
	
  } catch(PDOException $e) {
	
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	
  }
  
 
