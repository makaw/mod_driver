
<div id="detailsPage" data-role="page">

<div data-role="header" data-position="fixed">
<a href="index.php?act=dl" data-icon="back" data-role="button" class="ui-btn-left show-page-loading-msg">Powrót</a>
<h1 style="position:absolute;left:40%">Szczegóły</h1>
<div id="userInfo" style="float:right" data-role="content"></div>
</div>

<a id="bmap"  href="index.php?act=dm&id=<?php echo $id; ?>" data-icon="star"
	data-role="button" class="ui-btn-right">Zobacz mapę</a>

<div data-role="content">

	<div id="deliveryDetails">
		<h3 id="deliveryDate">&nbsp; </h3>
		<p id="vehicleDesc"></p>
		<p id="returnToDepot"></p>
	</div>

	<ul id="ordersList" data-role="listview" data-inset="true"></ul>

</div>

</div>
