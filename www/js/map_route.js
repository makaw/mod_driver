/*
 * 
 *  Somado - mod_driver
 * 
 *  Wyświetlenie mapy z trasą
 * 
 */
 
var debug = false;


$('#mapPage').live('pagecreate', function(event) {
	
	$.mobile.loadingMessage = "Proszę czekać ...";	
	
	$('.show-page-loading-msg').bind( "click", function() {
		$.mobile.showPageLoadingMsg();
	})
	
	$.getJSON(serviceURL + 'get_user_info.php', function(data) {
		
      $('#mapPage #userInfo').empty();		
	  $('#mapPage #userInfo').append('<span class="ui-icon ui-icon-user" style="display: inline-block; vertical-align:middle"/> '
	   + data.items.user_info + '<br /><a href="index.php?act=out" style="float:right">[wyloguj]</a>');
		
	});	
	
});


$('#mapPage').live('pageshow', function(event) { 	


	$('#mapPage #map').css({
	    position: 'absolute',
	    top: $('#mapPage #mpHeader').outerHeight(),
	    right: '0',
	    bottom: '0',
	    left: '0',
	    padding: '0 !important'
	});
	
	
    var map = L.map('map');

    // tmsURL + '?z={z}&x={x}&y={y}'
    L.tileLayer(tmsURL + '/{z}/{x}/{y}.png', {        
		attribution: 'Map data &copy; <a href="http://openstreetmap.org" target="_blank">OpenStreetMap</a> contributors',
		maxZoom: 16
    }).addTo(map);
     
    map.locate({setView: false});   
    
    var delivery_id = getUrlVars()["id"];
    
    
    // pobranie i wyświetlenie geometrii (0), dodatkowej geometrii (1)
    for (a = 0; a<=1; a++) {
		 
	  $.getJSON(serviceURL + 'get_route.php?id='+delivery_id+'&a='+a, function(data) {		
	
	    var points = data.items;   
	    if (points.length == 0) return;
	
	    var oid_tmp = 0;
	    var polylinePoints = [];
        var polylineOptions = { color: 'red', weight: 3, opacity: 0.7, clickable: false };                  
         
		var routes = new L.layerGroup();
             
        // przetwarzanie kolejnych punktów
        // trasa do każdego zamówienia jako nowa warstwa       
        $.each(points, function(index, point) {	
		
	      if (oid_tmp != point.id) {			
			  
		    routes.addLayer(new L.Polyline(polylinePoints, polylineOptions)); 
		    polylinePoints = [];
	      }		  

          polylinePoints.push(new L.LatLng(point.latitude, point.longitude));
          oid_tmp = point.id;        
	  
        });      
    
        // trasa do ostatniego punktu        
	    routes.addLayer(new L.Polyline(polylinePoints, polylineOptions));         
	    routes.addTo(map);
		
	});
	
  }
  
  // pobranie i wyświetlenie punktów odbioru towaru (markery)
  $.getJSON(serviceURL + 'get_route_labels.php?id='+delivery_id, function(data) {	
	  
	 var points = data.items;   
	 if (points.length == 0) return; 
	 
	 var marks = [];
	  
	 $.each(points, function(index, point) {	
		 
		marks.push(new L.LatLng(point.latitude, point.longitude));
		L.marker([point.latitude, point.longitude], {title: point.label, draggable: false})
		.bindPopup(point.description).addTo(map);
		if (debug) console.log(point.description);
		 
	 });
	 
	 // ustawienie widoku na całą trasę
	 map.fitBounds(new L.Polyline(marks).getBounds());	
	  
  });
	 
     
});

    	


// Pobranie parametrów z bieżącego URL-a
function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
