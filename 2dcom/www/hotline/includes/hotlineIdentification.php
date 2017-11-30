<?php
	session_start();
	require_once("../../require/class_new_info.php");
?>


<?php
	if(isset($_POST)) {
		// traitement du formulaire
		extract($_POST);
		$INFO = new Info("", "","","","","",true);
		if(isset($lg_username) && $lg_username == "2dcom" && sha1(urlencode($lg_username).$INFO->auth_salt.urlencode($lg_password)) == $INFO->usr_password) {
			$_SESSION["identification"]=1;
			header("Location:../index.php");
		}
		if(isset($lg_username) && $lg_username == "2dcom" && sha1($lg_password) == $INFO->usr_password) {
			$_SESSION["identification"]=1;
			header("Location:../index.php");
		}
		if(isset($lg_deconnexion) && $lg_deconnexion == "1")
			unset($_SESSION["identification"]);
	}
	//var_dump($_SESSION);
?>

<!-- All the files that are required -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css">
<link rel="stylesheet" href="http://bootsnipp.com/dist/bootsnipp.min.css?ver=7d23ff901039aef6293954d33d23c066">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link href='http://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<style type="text/css">
	/*------------------------------------------------------------------
	[Master Stylesheet]

	Project    	: Aether
	Version		: 1.0
	Last change	: 2015/03/27
	-------------------------------------------------------------------*/
	/*------------------------------------------------------------------
	[Table of contents]

	1. General Structure
	2. Anchor Link
	3. Text Outside the Box
	4. Main Form
	5. Login Button
	6. Form Invalid
	7. Form - Main Message
	8. Custom Checkbox & Radio
	9. Misc
	-------------------------------------------------------------------*/
	/*=== 1. General Structure ===*/
	html,
	body {
	  background: #efefef;
	  padding: 10px;
	  font-family: 'Varela Round';
	}
	/*=== 2. Anchor Link ===*/
	a {
	  color: #aaaaaa;
	  transition: all ease-in-out 200ms;
	}
	a:hover {
	  color: #333333;
	  text-decoration: none;
	}
	/*=== 3. Text Outside the Box ===*/
	.etc-login-form {
	  color: #919191;
	  padding: 10px 20px;
	}
	.etc-login-form p {
	  margin-bottom: 5px;
	}
	/*=== 4. Main Form ===*/
	.login-form-1 {
	  max-width: 300px;
	  border-radius: 5px;
	  display: inline-block;
	}
	.main-login-form {
	  position: relative;
	}
	.login-form-1 .form-control {
	  border: 0;
	  box-shadow: 0 0 0;
	  border-radius: 0;
	  background: transparent;
	  color: #555555;
	  padding: 7px 0;
	  font-weight: bold;
	  height:auto;
	}
	.login-form-1 .form-control::-webkit-input-placeholder {
	  color: #999999;
	}
	.login-form-1 .form-control:-moz-placeholder,
	.login-form-1 .form-control::-moz-placeholder,
	.login-form-1 .form-control:-ms-input-placeholder {
	  color: #999999;
	}
	.login-form-1 .form-group {
	  margin-bottom: 0;
	  border-bottom: 2px solid #efefef;
	  padding-right: 20px;
	  position: relative;
	}
	.login-form-1 .form-group:last-child {
	  border-bottom: 0;
	}
	.login-group {
	  background: #ffffff;
	  color: #999999;
	  border-radius: 8px;
	  padding: 10px 20px;
	}
	.login-group-checkbox {
	  padding: 5px 0;
	}
	/*=== 5. Login Button ===*/
	.login-form-1 .login-button {
	  position: absolute;
	  right: -25px;
	  top: 50%;
	  background: #ffffff;
	  color: #999999;
	  padding: 11px 0;
	  width: 50px;
	  height: 50px;
	  margin-top: -25px;
	  border: 5px solid #efefef;
	  border-radius: 50%;
	  transition: all ease-in-out 500ms;
	}
	.login-form-1 .login-button:hover {
	  color: #555555;
	  transform: rotate(450deg);
	}
	.login-form-1 .login-button.clicked {
	  color: #555555;
	}
	.login-form-1 .login-button.clicked:hover {
	  transform: none;
	}
	.login-form-1 .login-button.clicked.success {
	  color: #2ecc71;
	}
	.login-form-1 .login-button.clicked.error {
	  color: #e74c3c;
	}
	/*=== 7. Form - Main Message ===*/
	.login-form-main-message {
	  background: #ffffff;
	  color: #999999;
	  border-left: 3px solid transparent;
	  border-radius: 3px;
	  margin-bottom: 8px;
	  font-weight: bold;
	  height: 0;
	  padding: 0 20px 0 17px;
	  opacity: 0;
	  transition: all ease-in-out 200ms;
	}
	.login-form-main-message.show {
	  height: auto;
	  opacity: 1;
	  padding: 10px 20px 10px 17px;
	}
	.login-form-main-message.success {
	  border-left-color: #2ecc71;
	}
	.login-form-main-message.error {
	  border-left-color: #e74c3c;
	}
	/*=== 9. Misc ===*/
	.logo {
	  padding: 15px 0;
	  font-size: 25px;
	  color: #aaaaaa;
	  font-weight: bold;
	}
</style>

<!-- Where all the magic happens -->
<!-- LOGIN FORM -->
<div class="text-center" style="padding:50px 0">
	<?php if(!isset($_SESSION["identification"])) { ?>
	<div class="logo">Se connecter</div>
	<!-- Main Form -->
	<div class="login-form-1">
		<form id="login-form" class="text-left" method="post">
			<div class="login-form-main-message"></div>
			<div class="main-login-form">
				<div class="login-group">
					<div class="form-group">
						<label for="lg_username" class="sr-only">Utilisateur</label>
						<input type="text" class="form-control" id="lg_username" name="lg_username" placeholder="username">
					</div>
					<div class="form-group">
						<label for="lg_password" class="sr-only">Mot de passe</label>
						<input type="password" class="form-control" id="lg_password" name="lg_password" placeholder="password">
					</div>
				</div>
				<button type="submit" class="login-button"><i class="fa fa-chevron-right"></i></button>
			</div>
		</form>
	</div>
	<?php } else if($_SESSION["identification"] == 1) { ?>
	<div class="logo">Se déconnecter</div>
	<div class="login-form-1">
		<form id="login-form" class="text-left" method="post">
			<div class="login-form-main-message"></div>
			<div class="main-login-form">
				<input type="hidden" value="1" name="lg_deconnexion">
				<button type="submit" class="login-button"><i class="fa fa-chevron-right"></i></button>
			</div>
		</form>
	</div>
	<?php } ?>
	<!-- end:Main Form -->
</div>