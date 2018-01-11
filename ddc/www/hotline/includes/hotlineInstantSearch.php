<?php
	// ------
	// Ce fichier sert à faire une
	// recherche instantannée de produits
	// Il est appelé par une requête AJAX
	// pour la recherche de codes EAN
	// ------

	require_once __DIR__."/../../require/class_new_info.php";
	$INFO = new Info();

	$array = array(); // on créé un tableau

  	if(!$INFO->Connect()) return false;

  	if(isset($_GET["codeEan"])) {
  		extract($_GET);
  		if (!isset($simple)){
  			$simple='0';
  		}
  		$req = "SELECT 
  				prod_ean, prod_titre
				FROM produits
				WHERE prod_ean LIKE \"$codeEan%\"
				LIMIT 0, 5
				";
		if(!$INFO->Query($req)) {
			if ($simple=='1'){
				array_push($array, "Erreur :".$INFO->erreur);
			} else{
				echo "Erreur :".$INFO->erreur;
			}
		}
		$nb  = $INFO->Num();
		if($nb > 0) {
			while($ROW = $INFO->Result()) {
				if ($simple=='1'){
					echo '<ul><li>Article dans la base <b>EAN: '.$ROW["prod_ean"]." - TITRE: ".$ROW["prod_titre"].'</b></li></ul>';
				}else{
					array_push($array, $ROW["prod_ean"]." - ".$ROW["prod_titre"]);
				}
			}
		} else {
			if ($simple=='1'){
				echo '<ul><li><b class="popup_erreur">Article absent de la base de données !</b></li></ul>';
			}else{
				array_push($array, 'Aucun produit trouvé !');
			}
		}
		if ($simple=='0'){
			echo json_encode($array); // il n'y a plus qu'à convertir en JSON
		}
  	}