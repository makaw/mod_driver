
<div data-role="page" id="loginFormPage">

<div data-role="header" data-position="fixed">
<h1>Somado mod_driver</h1>
</div>

<div id="loginForm" style="text-align:center" data-role="content">

<form id="loginFormForm" action="index.php" method="post" class="ui-body ui-body-b ui-corner-all">
<div data-role="content" class="ui-grid-b">


<div class="ui-block" style="text-align:center">

<?php echo $error; ?>
<div data-role="fieldcontain">
<label for="u_login" class="alignleft">Login:</label>
<input id="u_login" name="u_login" placeholder="" type="text">
</div>

<div data-role="fieldcontain">
<label for="u_pass" class="alignleft">Hasło:</label>
<input id="u_pass" name="u_pass" value="" type="password">
</div>

<br/>
<button type="submit" class="alignleft show-page-loading-msg" data-theme="a" data-icon="check" name="submit" value="1">Zaloguj się</button>

</div>

</div>
</form>

<p align="right" style="font-size: 11px">
<a href="https://github.com/makaw/mod_driver">https://github.com/makaw/mod_driver</a>
</p>


</div>
</div>

