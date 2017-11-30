<?php

	// ---------------------------------
    // Version : 1.0
	// Date : 11/10/2017
    // ---------------------------------
    // TESTS SUR LES CLASSES WORDPRESS
	// LIAISON BASE LIBRIWEB
    // ----------------------------------	

	require_once __DIR__."/conf.php";
	require_once __DIR__."/Classes/Autoloader.php"; 

	use \Classes\Autoloader;
	use \Classes\Common\AbstractSql;
	use \Classes\Wordpress\WooCommerceOrderHeader;
	use \Classes\Wordpress\WooCommerceUtAddress;
	use \Classes\Wordpress\WooCommerceUtOrder;

	echo "<h1>Premiers tests en cours</h1>";
	Autoloader::register();

	// Attention : le poids est manquant dans la commande ! Il faut le récupérer autrement
	$order_test		= unserialize($order_test_serial);
	$product_test	= unserialize($product_test_serialize["9590"]);

	echo "<pre>";
	print_r("Commande récupérée : ".$order_test->order_number."\r\n");
	print_r("Poids de l'article (en grammes) : ".$product_test->product->weight);
	echo "</pre>";

	$db 			= new AbstractSql($db_name, $db_host, $db_user, $db_password);
	$orderheader 	= new WooCommerceOrderHeader($order_test);

	echo "<b>Nom de la table</b> : ".$orderheader->table_name."<br/>";
	echo "<b>Commande de test récupérée</b> : ".$order_test->id."<br/>";
	echo "<b>Commande de test injectée dans l'entité</b> : ".$orderheader->cmd_ent_id."<br/>";
	echo "<b>Requete INSERT</b> : ".$orderheader->getInsertReq()."<br/>";

























	//echo "<b>Requete SEARCH BY ID</b> : ".$orderheader->getSearchReqById(1)."<br/>";
	//$shipAddr 	= new WooCommerceUtAddress("Yannick", "KUHN", "2DCOM", "ZI Clairs Chênes", "", "54000", "NANCY", "0383000000", 'S');
	//$bilAddr 	 	= new WooCommerceUtAddress("Yannick", "KUHN", "2DCOM", "ZI Clairs Chênes", "", "54000", "NANCY", "0383000000", 'B');
	//$order 		= new WooCommerceUtOrder($shipAddr, $bilAddr);
	//echo $order;

