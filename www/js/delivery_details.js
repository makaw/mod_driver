/*
 * 
 *  Somado - mod_driver
 * 
 *  Pobranie i wyświetlenie szczegółów dostawy i listy zamówień, zmiana stanu zamówień
 * 
 */


// Identyfikator (BD) dostawy
var delivery_id;

$('#detailsPage').live('pagecreate', function(event) {
	
	$.mobile.loadingMessage = "Proszę czekać ...";	
	
	$('.show-page-loading-msg').bind( "click", function() {
		$.mobile.showPageLoadingMsg();
	})
	
	$.getJSON(serviceURL + 'get_user_info.php', function(data) {
		
      $('#detailsPage #userInfo').empty();		
	  $('#detailsPage #userInfo').append('<span class="ui-icon ui-icon-user" style="display: inline-block; vertical-align:middle"/> '
	   + data.items.user_info + '<br /><a href="index.php?act=out" style="float:right">[wyloguj]</a>');
		
	});	
	
});


$('#detailsPage').live('pageshow', function(event) {
	delivery_id = getUrlVars()['id'];
	$.getJSON(serviceURL + 'get_deliveries.php?id='+delivery_id, displayDelivery);	
	$.getJSON(serviceURL + 'get_orders.php?id='+delivery_id, displayOrders);
});


// Wyświetlenie informacji o dostawie
function displayDelivery(data) {
	
  var delivery = data.items;
  $('#detailsPage #deliveryDate').text('Dostawa #' + delivery.id + ' z dn. ' + delivery.delivery_date);	
  $('#detailsPage #vehicleDesc').text(delivery.vehicle_desc);
  $('#detailsPage #returnToDepot').text('Powrót do magazynu: ' + (delivery.return_to_depot == 1 ? 'Tak' : 'Nie'));
  
}


// Wyświetlenie listy zamówień
function displayOrders(data) {
	
  var orders = data.items;
  sep = ' &nbsp; &nbsp; ';
  
  $('#detailsPage #ordersList').empty();
  
  if (orders.length == 0) {
	$('#detailsPage #ordersList').append('<p style="color:red"><br/>Błąd: brak zamówień.</p>');	
	return;		
  }
  
 
  $.each(orders, function(index, order) {	

	$('#detailsPage #ordersList').append('<li data-icon="check">' + 
	(order.customer_label=='M' || order.done=='1' ? '' : '<a id="confirm_' + order.customer_label + 
	'" data-id="' + order.id +'" data-number="' + order.order_number + '">') +
	 '<h3>' + order.customer_label + ') '
	 + (order.customer_label == 'M' ? 'Magazyn' : 'Zamówienie nr ' + order.order_number) + '</h3>' + 
							'<p>' + order.customer_desc + '<br/>' + 						
    (order.customer_label != 'M' ? '+ ' + order.distance + 'km  ' + sep + '+ ' + order.time + 
    sep + '- ' + order.weight + 'kg' : '') +  
    ((order.customer_label !='M' && order.done=='1') ? '<br/><b style="color:green">dostarczono.</b>' : '') +
    '</p>' +  (order.done=='1' ? '' : '</a>') + '</li>');
	  
	if (order.customer_label != 'M')
	  $(document).delegate('#confirm_' + order.customer_label, 'click', confirmDialog);
	  
	  
  });
  
  
	
  $('#detailsPage #ordersList').listview('refresh');
	

}



// Okno z potwierdzeniem zmiany stanu
function confirmDialog() {
	
  var order_id = $(this).attr('data-id');	
	
  var zdialog = new $.Zebra_Dialog('Czy na pewno zmienić stan zamówienia nr <b>' + $(this).attr('data-number') + '</b> ?', 
    { 'type': 'question', 
	  'title': $(this).attr('data-number'),
	  'buttons': [ 
	    {caption: 'Zmień stan', callback: function() { 
		
			$.get(serviceURL + 'update_order.php', { id: order_id }, 
			function(data){
			  if (data != '')  console.log(data);
			  else {
			   $('#ordersList').empty();
			   $.getJSON(serviceURL + 'get_orders.php?id='+delivery_id, displayOrders);
		      }         
            });	 
            // fix: problem z zamykaniem kolejnych okien
            $(document).trigger('zebra_complete');     
	  	  
		}}, 
	    {caption: 'Anuluj', callback: function() { $(document).trigger('zebra_complete'); }} ],
	  'animation_speed_hide' : 100,
	  'animation_speed_show' : 100,
	  'overlay_opacity' : 0.7 });
	  
	//fix  
	$(document).bind('zebra_complete', function() { zdialog.close(); }); 
	
  
}
