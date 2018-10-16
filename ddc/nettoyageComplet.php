<?php

	// SCRIPT DE NETTOYAGE DE LA BASE WOOCOMMERCE
	// - nettoyage des données faussées
	// - nettoyage des images uploadées plusieurs fois
	// - suppression des images dans les dossiers (à la main)


	$CONF["dbhost"] = "localhost"; 
	$CONF["dbuser"] = "root"; 
	$CONF["dbpassword"] = "root"; 
	$CONF["dbname"] = "wordpress"; 

	//$CONF["dbuser"] = "root"; 
	//$CONF["dbpassword"] = "root"; 

	try {
		$db = new PDO("mysql:host=".$CONF["dbhost"].";dbname=".$CONF["dbname"].";charset=utf8", $CONF["dbuser"], $CONF["dbpassword"]);
	} catch(Exception $e) {
		echo 'Impossible de se connecter à la base de données ZENOBI : '.$e->getMessage();
        die('Erreur : '.$e->getMessage());
	}
	echo ("Authentification à la base de données ZENOBI réussie !<br>");

	// =======================================
	// 1- Supprimer les produits créés !
	// =======================================

	echo ("SUPPRESSION DE TOUS LES PRODUITS EN COURS !<br>");

	$metaDeleted = 0;
    $postDeleted = 0;
    
    $tabPostId = [];

	$response = $db->query("SELECT * FROM `wpmk_posts` WHERE `post_type`='product'");
	while ($data = $response->fetch()) {
        $tabPostId[] = $data['ID'];
		$res = $db->prepare("DELETE FROM wpmk_postmeta WHERE post_id = '".$data['ID']."'");
		$res->execute();
		$metaDeleted += $res->rowCount();
	}
	$res = $db->prepare("DELETE FROM `wpmk_posts` WHERE `post_type`='product'");
	$res->execute();
    $postDeleted += $res->rowCount();
    
    var_dump(count($tabPostId));

	echo "$metaDeleted enregistrements méta données supprimées !<br>";
    echo "$postDeleted posts supprimées !<br>";

    // =======================================
	// 1- Supprimer les images !
	// =======================================

    echo ("SUPPRESSION DES ENTREES IMAGES EN BASE !<br>");
    
    foreach($tabPostId as $postId) {

        $response = $db->query("SELECT * FROM `wpmk_posts` WHERE `post_parent` = '".$postId."'");
        if ($data = $response->fetch()) {
            $res = $db->prepare("DELETE FROM wpmk_postmeta WHERE post_id = '".$data['ID']."'");
            $res->execute();
            $metaDeleted += $res->rowCount();
        }
        $res = $db->prepare("DELETE FROM `wpmk_posts` WHERE `post_parent` = '".$postId."'");
        $res->execute();
        $postDeleted += $res->rowCount();
    }

    
