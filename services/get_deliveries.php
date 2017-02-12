<?php

/*
 * 
 *  Somado - mod_driver
 * 
 *  Serwis JSON get_deliveries - lista dostaw lub szczegóły danej dostawy
 * 
 */
 

  session_start();  
    
  require("../www/user.php");
  require("../www/config.php");
  
  $user = new User();
  if (!$user->isAuthorized()) { echo '{"error":{"text":"unauthorized"}}'; die(); }
  
  $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
  $driver_id = $user->getUID();

  //AND d.active='1' 

  $query = "SELECT d.id, dr.driver_desc, dr.vehicle_desc, dr.return_to_depot, d.delivery_date, "
	    ."(SELECT COUNT(id) FROM dat_deliveries_drivers_orders WHERE done='1' "
	    ."AND delivery_driver_id=dr.id) AS orders_done, "
  	    ."COUNT(o.id)-(dr.return_to_depot+1) AS orders_num, "
	    ."TRUNCATE(SUM(o.order_weight/1000.0), 2) AS orders_weight, "
	    ."TRUNCATE(SUM(o.distance/1000.0), 1) AS total_distance, "
	    ."SEC_TO_TIME(ROUND(SUM(o.time))) AS total_time "
	    ."FROM dat_deliveries AS d "
	    ."INNER JOIN dat_deliveries_drivers AS dr ON dr.delivery_id = d.id "
	    ."INNER JOIN sys_users AS u ON u.id = dr.driver_user_id "
	    ."INNER JOIN dat_deliveries_drivers_orders AS o ON o.delivery_driver_id = dr.id "
	    ."WHERE u.id = :uid AND u.blocked <> '1' AND u.role = :driver_role "
	    ."AND d.delivery_date <= DATE(NOW()) " . ($id > 0 ? "AND d.id=:id " : "")  
	    ."GROUP BY dr.id ORDER BY d.delivery_date DESC, d.id DESC;";	 


  try {
	
	$dbh = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->query("SET NAMES 'utf8';");
	$stmt = $dbh->prepare($query); 
	$stmt->bindParam("uid", $driver_id);
	$stmt->bindParam("driver_role", $DRIVER_ROLE_ID);
	if ($id>0) $stmt->bindParam("id", $id);
	$stmt->execute();
	if ($id>0) $deliveries = $stmt->fetchObject();  
	else $deliveries = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"items":'. json_encode($deliveries) .'}'; 
	
  } catch(PDOException $e) {
	
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	
  }


