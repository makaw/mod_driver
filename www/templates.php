<?php


/*
 * 
 *  Somado - mod_driver
 * 
 *  Klasa Templates - szablony stron WWW (HTML5)
 * 
 */



class Templates {
	
	
  private $header;
  private $footer;
  private $serviceURL;
  private $tmsURL;
  
  private $id = 0; 
  
  private $error = '';
  
  
  public function __construct() {
	  
	require("../www/config.php");
	
	$this->serviceURL = $SERVICES_URL;
	$this->tmsURL = $TMS_URL;

	$this->header = $this->pageHeader();  
	  
	$this->footer = $this->pageFooter();
	  
  }


/* Nagłówek strony WWW */
private function pageHeader() {
	
return <<<EOF
<!DOCTYPE HTML>
<html>
<head>
<title>Somado mod_driver</title>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<meta charset="utf-8"/>
<link rel="stylesheet" href="css/jquery.mobile-1.0.1.min.css" />
<link rel="stylesheet" href="css/zebra_dialog.css" />
<link rel="stylesheet" href="css/somado.css" />
<link rel="stylesheet" href="css/leaflet.css" />
</head>	
<body>
EOF;
	
}

/** Zakończenie kodu strony WWW, załadowanie skryptów JS */
private function pageFooter() {
	
return <<<EOF
<script src="js/jquery-1.6.4.min.js"></script>
<script src="js/jquery.mobile-1.0.1.min.js"></script>
<script>
var serviceURL = '{$this->serviceURL}';
var tmsURL = '{$this->tmsURL}';
</script>
<script src="js/login_form.js"></script>
<script src="js/deliveries_list.js"></script>
<script src="js/delivery_details.js"></script>
<script src="js/zebra_dialog.js"></script>
<script src="js/leaflet.js"></script>
<script src="js/map_route.js"></script>
</body>
</html>
EOF;
		
}



//-------------------------------------------------------------------


/** Szablon listy dostaw */
public function tplDeliveriesList() {
	
  return $this->readTemplate('deliveries_list.php');
	
}


/** Szablon szczegółów dostawy i listy zamówień */
public function tplDeliveryDetails($id) {
	

  $this->id = $id;
	
  return $this->readTemplate('delivery_details.php');
		
}


/** Wyświetlanie mapy z trasą */
public function tplDeliveryDetailsMap($id) {		
	
  $this->id = $id;
  
  return $this->readTemplate('map_route.php');
		
}



/** Szablon formularza logowania */
public function tplLoginForm($err = false) {

	$this->error = !$err ? '' : '<div class="login_error">Nieprawidłowy login lub hasło.</div>';

	return $this->readTemplate('login_form.php');

}



private function readTemplate($file) {

	$error = $this->error;
	$id = $this->id;
	
	ob_start();
	require 'templates/'.$file;
	$R = ob_get_contents();
	ob_end_clean();
	
	return $this->header . $R . $this->footer;
	
}



}

