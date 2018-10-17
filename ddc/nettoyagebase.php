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
	// 1- Supprimer les produits vides créés !
	// =======================================

	echo ("SUPPRESSION DES PRODUITS VIDES EN COURS !<br>");

	$metaDeleted = 0;
	$postDeleted = 0;
	$images_utilisees_en_ligne = [];

	$response = $db->query("SELECT * FROM `wpmk_posts` WHERE `post_type`='product' AND `post_title`='produit'");
	while ($data = $response->fetch()) {
		$res = $db->prepare("DELETE FROM wpmk_postmeta WHERE post_id = '".$data['ID']."'");
		$res->execute();
		$metaDeleted += $res->rowCount();
	}
	$res = $db->prepare("DELETE FROM `wpmk_posts` WHERE `post_type`='product' AND `post_title`='produit'");
	$res->execute();
	$postDeleted += $res->rowCount();

	echo "$metaDeleted enregistrements méta données supprimées !<br>";
	echo "$postDeleted posts supprimées !<br>";

	// =======================================
	// 1- Supprimer les images !
	// =======================================

	echo ("SUPPRESSION DES IMAGES NON UTILISEES SUR LE DISQUE !<br>");

	// PARTIE 1

	$response = $db->query("SELECT * FROM `wpmk_posts` WHERE (`post_name` LIKE 'image-%' OR `post_title` REGEXP '[0-9]{13}') AND (`post_mime_type`='image/jpeg' OR `post_mime_type`='image/png')");
	while ($data = $response->fetch()) {
		var_dump($data['guid']);
		$data['guid'] = pathinfo($data['guid']);
		$data['guid'] = $data['guid']['dirname'].'/'.$data['guid']['filename'];
		$images_utilisees_en_ligne[$data['ID']] = $data['guid'];
	}

	echo " Suppression des images non utilisées en cours ...<br>";

	var_dump($images_utilisees_en_ligne);

	// UNLINK IMAGES

	$folders = [
		"../wp-content/uploads/2017/08",
		"../wp-content/uploads/2017/09",
		"../wp-content/uploads/2017/10",
		"../wp-content/uploads/2017/11",
		"../wp-content/uploads/2017/12",
		"../wp-content/uploads/2018/01",
		"../wp-content/uploads/2018/02",
		"../wp-content/uploads/2018/03",
		"../wp-content/uploads/2018/04",
		"../wp-content/uploads/2018/05",
		"../wp-content/uploads/2018/06",
		"../wp-content/uploads/2018/07",
		"../wp-content/uploads/2018/08",
		"../wp-content/uploads/2018/09",
		"../wp-content/uploads/2018/10",
	];

	$nbImagesDel = 0;

	foreach($folders as $folder) {
		if (file_exists($folder)) {
			if ($handle = opendir($folder)) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != "..") {
						$file_server = $folder.'/'.$entry;
						$file = str_replace("../", "", $file_server);
						$file = str_replace("/", "\/", $file);
						var_dump($file);
						if(preg_grep("/$file/i", $images_utilisees_en_ligne)) {
							var_dump('image utilisée en ligne !');
						} else {
							// Si le fichier ne matche pas les entrées dans la base, il faut tester qu'il s'agit d'une image 
							// ajoutée via le web service
							if(preg_match("/image-(.*)-/", $entry, $output_array)) {
								unlink($folder.'/'.$entry);
								$nbImagesDel++;
							}
						}
					}
				}
				closedir($handle);
			}
		}
	}
	echo "$nbImagesDel images supprimées !<br>";

	// TODO : LE SCRIPT SUPPRIME LES IMAGES THUMBNAILS ...



