/*
 * 
 *  Somado - mod_driver
 * 
 *  Form. logowania
 * 
 */



$('#loginFormPage').live('pagecreate', function(event) {

	$.mobile.loadingMessage = "Proszę czekać ...";		

	$('.show-page-loading-msg').bind( "click", function() {
		$.mobile.showPageLoadingMsg();
	})
	
	$('#loginFormPage #loginForm #loginFormForm').bind('submit', function() {
	  $.post('index.php', $(this).serialize(), function() {
	    $('#loginFormPage #loginForm').empty();
	  });
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
