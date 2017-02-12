/*
 * 
 *  Somado - mod_driver
 * 
 *  Pobranie i wyświetlenie listy dostaw
 * 
 */
 

// Lista dostaw w JSON
var deliveries;

$('#deliveriesListPage').bind('pageinit', function(event) {
	getDeliveriesList();
});
 

// Pobranie i wyświetlenie listy dostaw
function getDeliveriesList() {
	
	$.getJSON(serviceURL + 'get_user_info.php', function(data) {
		
      $('#userInfo').empty();		
	  $('#userInfo').append('<span class="ui-icon ui-icon-user" style="display: inline-block; vertical-align:middle"/> '
	   + data.items.user_info + '<br /><a href="index.php?act=out" style="float:right">[wyloguj]</a>');
		
	});
	
	$.getJSON(serviceURL + 'get_deliveries.php', function(data) {
		
		$('#deliveriesList li').remove();
		deliveries = data.items;
		if (deliveries.length == 0) {
		  $('#deliveriesList').append('<p><br/>Brak aktywnych dostaw.</p>');	
	      return;		
		}
		
		sep = ' &nbsp; &nbsp; &nbsp; ';
		$.each(deliveries, function(index, delivery) {			
		    drest = delivery.orders_num - delivery.orders_done;
			$('#deliveriesList').append('<li><a href="index.php?act=dd&id=' + delivery.id + '">' +					
					'<h4>Dostawa #' + delivery.id + ' z dn. ' + delivery.delivery_date + '</h4>' + 
					'<p>Zamówienia: ' + delivery.orders_num + sep +'Ładunek: ' + delivery.orders_weight + 't<br/>' + 
					'Dystans: ' + delivery.total_distance + 'km' + sep +
					'Czas: ' + delivery.total_time + '<br/>' + delivery.vehicle_desc + '</p>' + 
					'<span class="ui-li-count" style="color:' + (drest > 0 ? 'red' : 'green')
					 + '!important">' + drest + '</span></a></li>');
		});
		$('#deliveriesList').listview('refresh');
	});
	
}
