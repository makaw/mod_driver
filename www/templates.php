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
  
  public function __construct() {
	  
	require("../www/config.php");
	
	$this->serviceURL = $SERVICES_URL;
	$this->tmsURL = $TMS_URL;

	$this->header = self::pageHeader();  
	  
	$this->footer = $this->pageFooter();
	  
  }


/* Nagłówek strony WWW */
private static function pageHeader() {
	
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
	
  return <<<EOF
{$this->header}

<div id="deliveriesListPage" data-role="page" >

	<div data-role="header" data-position="fixed">	
		
		<div style="float:left"><h3 style="float:left"> &nbsp; Lista dostaw</h3></div>
		<div id="userInfo" style="float:right" data-role="content"></div> 
	</div>

	<div data-role="content">
         <ul id="deliveriesList" data-role="listview" data-filter="true" data-filter-placeholder="Filtruj treść ..."></ul>
    </div>		

</div>

{$this->footer}
EOF;
  	
	
	
}


/** Szablon szczegółów dostawy i listy zamówień */
public function tplDeliveryDetails($id) {
	
	
  return <<<EOF
{$this->header}

<div id="detailsPage" data-role="page">

	<div data-role="header">		
		<div id="bback" onclick="window.location.replace('index.php?act=dl')" data-icon="back"
		 data-role="button" class="ui-btn-left">Powrót</div>
		<a id="bmap"  onclick="window.location.replace('index.php?act=dm&id={$id}')" data-icon="star"
		 data-role="button" class="ui-btn-right">Mapa</a>
		<h1>Szczegóły</h1>
	</div>

  <div data-role="content"> 

	<div id="deliveryDetails">
       <h3 id="deliveryDate">&nbsp; </h3>       
       <p id="vehicleDesc"></p>
       <p id="returnToDepot"></p>
   	</div>
	 
    <ul id="ordersList" data-role="listview" data-inset="true"></ul>

  </div>

</div>

{$this->footer}
EOF;
  	
		
}


/** Wyświetlanie mapy z trasą */
public function tplDeliveryDetailsMap($id) {	
	
  $header = str_replace("</head>", "", $this->header);
  $header = str_replace("<body>", "", $header);
	
  return <<<EOF
{$header}
<style>
.ui-content {
    position: absolute;
    top: 40px;
    right: 0;
    bottom: 0;
    left: 0;
    padding: 0 !important;
}
</style>
</head>
<body>

<div id="mapPage" data-role="page">

	<div data-role="header">		
		<div id="bback" onclick="window.location.replace('index.php?act=dd&id={$id}')" data-icon="back"
		 data-role="button" class="ui-btn-left">Powrót</div>
		<h1>Szczegóły</h1>
	</div>
	
	<div data-role="content">
        <div id="map"></div>
    </div>    

</div>

{$this->footer}
EOF;
  	
		
}



/** Szablon formularza logowania */
public function tplLoginForm($err = false) {
	
  $error = !$err ? '' : '<div class="login_error">Nieprawidłowy login lub hasło.</div>';
	
	
return <<<EOF
{$this->header}
        
<div data-role="page">

<div data-role="header" data-position="fixed">
  <h1>Somado mod_driver</h1>
</div>

<div id="loginForm" style="text-align:center" data-role="content">

<form action="loader.php" method="post" class="ui-body ui-body-b ui-corner-all" onsubmit="$.post('index.php', this.serialize(), checkForm);">
  <div data-role="content" class="ui-grid-b"> 
	
	
	<div class="ui-block" style="text-align:center">
	
	{$error}
	<div data-role="fieldcontain">
		<label for="u_login" class="alignleft">Login:</label> 
		<input id="u_login" name="u_login" placeholder="" type="text">
	</div>
	
	<div data-role="fieldcontain">
		<label for="u_pass" class="alignleft">Hasło:</label>
		<input id="u_pass" name="u_pass" value="" type="password">
	</div>

	<br/>
	<button type="submit" class="alignleft" data-theme="a" data-icon="check" name="submit" value="submit-value">Zaloguj się</button>
	
	</div>		
	
  </div>
</form>  

<p align="right" style="font-size: 11px">
<a href="https://github.com/makaw/mod_driver">https://github.com/makaw/mod_driver</a>
</p>


</div>
</div>

<script>
function checkForm(data) {

  $('loginForm').empty();
  return true;

}
</script>
	
{$this->footer}
EOF;
	
	
	
	
}



public function tplLoader() {
	
return <<<EOF
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf8"/>
</head><body>
Proszę czekać ...
<script>
window.location.replace('index.php?l=1');
</script>  	
</body></html>
EOF;

}



}

