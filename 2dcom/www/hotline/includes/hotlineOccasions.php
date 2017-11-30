<?php

require_once "../../require/class_new_info.php";
$INFO = new Info();

$entries = array(); // Tableau vide par defaut

// Connection Mysql
if( !$INFO->Connect() ) {
	// Message d'erreur
	$entries["error"]=$INFO->erreur;
	// Il faut alors envoyer un tableau de DATA vide pour avoir le message affiché dans la table
	$entries["data"]='';
	// Renvoi au format JSON
	echo json_encode($entries);
	// On ne va pas plus loin
	exit;
}

if( isset($_GET) ){	

	//////////////////////////////////////////////////////////////////
	//                     LECTURE DES PARAMETRES					//
	//////////////////////////////////////////////////////////////////

	$export = 0;

	$start = $_GET['start']; // Borne de départ de selection SQL
	$limit = $_GET['length'];// Borne de fin de selection SQL
	$draw  = $_GET['draw'];	 // Compteur d'executions à retourner (sécurite obligatoire)
	if(isset($_GET['export']))
		$export=$_GET['export']; // Export des données
	$select='prod_assoc_id,';

	if($limit!='0'){
		if ($start==''){
			$start=0;
		}
		$start='LIMIT '.$start.', ';
	}else{
		$start='';
		$limit='';
		//$select='';
	}

	// Nombre d'enregistrements au total dans la table produits
	$nbTotal = $_GET['nbRecords'];

	// Jointure par défaut
	$from  =', stock ';
	$where ='WHERE prod_assoc_ean = stock_ean';
	$and   ='';
	$order ='prod_assoc_ean';
	$search='';// Recherche vide par défaut

	//////////////////////////////////////////////////////////////////
	//                    		PRE-REQUETE							//
	//////////////////////////////////////////////////////////////////
	// Il faut une première requete 
	// SANS les limites pour avoir le bon nombre de résultats pour le filtre
	if($limit!=''){
		$reqNb= "SELECT COUNT(*) AS NB
				FROM produit_associations 
				$search";

		if ($INFO->Query($reqNb)){
			// Nombre d'enregistrements disponibles au total (sans les limites)
			$ROWnb = $INFO->Result();
			$nbSearch=$ROWnb['NB'];
		}else{
			// Message d'erreur
			$entries["error"]=$INFO->erreur.PHP_EOL.print_r($reqNb);
			// Il faut alors envoyer un tableau de DATA vide pour avoir le message affiché dans la table
			$entries["data"]='';
			// Renvoi au format JSON
			echo json_encode($entries);
			// On ne va pas plus loin
			exit;
		}
	}

	//////////////////////////////////////////////////////////////////
	//                    	REQUETE FINALE							//
	//////////////////////////////////////////////////////////////////


	// Recherche des nouvelles colonnes pour les produits d'occas
	$prod_assoc_titre = false;
	$req="SHOW COLUMNS FROM produit_associations LIKE 'prod_assoc_titre'";
	if ( $INFO->Query($req) ) {
		if( $INFO->Num() > 0 ){
			$prod_assoc_titre = true;
		}
	}

	$search_titre = "p.prod_titre as prod_assoc_titre";
	if($prod_assoc_titre)
		$search_titre = "a.prod_assoc_titre";


	// Requete SQL finale
	$req = "SELECT $select prod_assoc_ean, CONCAT(prod_assoc_prixttc,' &euro;') AS prod_assoc_prixttc, a.prod_ean, p.prod_id, p.prod_titre, ".$search_titre.", COALESCE(stock_qte, 0) AS stock_qte 
			FROM produit_associations AS a 
			LEFT JOIN produits AS p ON p.prod_ean = a.prod_ean 
			LEFT JOIN stock AS s ON a.prod_assoc_ean = s.stock_ean    	
			ORDER BY $order
			$start $limit";

	// Exécution requête finale
	if(!$INFO->Query($req)) {
		// Message d'erreur
		$entries["error"]=$INFO->erreur.PHP_EOL.print_r($req);
		// Il faut alors envoyer un tableau de DATA vide pour avoir le message affiché dans la table
		$entries["data"]='';
		// Renvoi au format JSON
		echo json_encode($entries);
		// On ne va pas plus loin
		exit;
	}else{
		if ($limit!=''){
			// Charge le tableau à renvoyer
			$entries["draw"]=intval($draw); // Nombre d'appels (clic sur boutons de pagination ou recherches)
			// Nombre d'enregistrement au total dans la table produits
			$entries["recordsTotal"]=intval($nbTotal);
			// Nombre d'enregistrements selon le filtre SANS les limites
			$entries["recordsFiltered"]=intval($nbSearch);
		}
		// Si des enregistrements sont présents
		if ( $INFO->Num()>0 ) {
			// Charge le tableau de données
			while($ROW = $INFO->Result()) {
				$entries["data"][]=array_map("utf8_encode",array_map("urldecode", $ROW));
			}
		}else{
			// Rien est trouvé par la requête
			// Il faut alors envoyer un tableau de DATA vide pour avoir le message affiché dans la table
			$entries["data"]='';
		}
	}

}else{
	// Message d'erreur
	$entries["error"]="Pas de paramètres précisés";
	// Il faut alors envoyer un tableau de DATA vide pour avoir le message affiché dans la table
	$entries["data"]='';
}

// Renvoi du tableau des résultats convertit au format JSON
echo json_encode($entries);