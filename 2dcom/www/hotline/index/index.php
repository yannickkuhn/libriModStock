<?php
	require_once "../includes/hotlineHeader.php";
?>

<h2>Bienvenue sur l'espace SuperAdmin</h2>
<p>Cet espace est dédié à la hotline et aux développeurs.</p>
<div class="login-form-1">
	<form id="login-form" class="text-left" method="post">
		<div class="login-form-main-message"></div>
		<div class="main-login-form">
			<input type="hidden" value="1" name="lg_deconnexion">
			<button type="submit" class="login-button">Se déconnecter</button>
		</div>
	</form>
</div>

<?php require_once "../includes/hotlineFooter.php"; ?>