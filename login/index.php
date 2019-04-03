<?php
if (isset($_GET["backto"])) {
    $backto = '<script>realreferer = \''.$_GET["backto"].'\'</script>';
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Schülerzeitung: Die Edith - Anmelden</title>
	<?php $subdirectory = '../';
  $version = '10';
  include '../config/basic-framework/head.php';?>
	<link rel="stylesheet" type="text/css" href="login.css?v=<?php echo $version ?>">
	<?php echo $backto;?></script>
	<script src="login.js?v=<?php echo $version ?>"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	<div id="title-section" class="big">
		<span id="title" class="big"><a class="text" href="../">Die Edith</a></span>
	</div>
	<div id="wrapper">
		<div id="login-container">
			<span id="subheading">Login</span><br>
			<p>Melde dich mit deinen Login-Daten an. <br> Noch keine bekommen? Frage deinen IT-Lehrer.</p>
			<p id="errormessage"></p>
			<form id="loginform">
				<input id="username_input" autocomplete="username" class="" name="username" type="text" value="" placeholder="Benutzername"><br>
				<input id="password_input"  autocomplete="current-password" class="" name="password" type="password" placeholder="Passwort"><br>
				<input id="form__button" type="button" name="doLogin" value="Anmelden">
			</form>
		</div>
	</div>


</body>

</html>
