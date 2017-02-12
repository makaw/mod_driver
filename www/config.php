<?php
	
/*
 * 
 *  Somado - mod_driver
 * 
 *  Konfiguracja serwisów
 * 
 */	
 
  // dostęp do bazy danych MySQL
  $DB_HOST = '127.0.0.1';
  $DB_USER = 'somado';
  $DB_PASS = 'somado';
  $DB_NAME = 'somado_driver';

  // Pełny URL do serwisów
  $SERVICES_URL = "http://".$_SERVER['HTTP_HOST'].'/mod_driver/services/';
  
  //-------------------------------------------------------------------------
  
  // ID roli użytkownika - kierowcy
  $DRIVER_ROLE_ID = 3;
  
  // ID stanu zamówienia - doręczone
  $ORDER_STATE_DONE = 3;
  
  // URL do usługi TMS
  $TMS_URL = 'http://a.tile.openstreetmap.org';
  



 
