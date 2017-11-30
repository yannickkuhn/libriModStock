<?php
require_once "../../require/class_new_info.php";
$INFO = new Info();

if( isset($_GET) ){
	
	$req    = '';
	$entries= array();
	$search = '';
	// UTF8 par défaut
	$mode_utf8=true;

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

	//////////////////////////////////////////////////////////////////
	//                     COMPATIBILITE DE TABLE					//
	//////////////////////////////////////////////////////////////////
	// Champs par défaut
	$cmd_ent_billingNom='cmd_ent_billingNom';
	$cmd_ent_billingPrenom='cmd_ent_billingPrenom';
	// Recherche si ce champ existe dans la table
	$req="SHOW COLUMNS FROM commande_entetes LIKE 'cmd_ent_billingNom'";
	// Tente d'exécuter la requête
	if ( $INFO->Query($req) ) {
		// Test du nombre d'enregistrements
		if($INFO->Num()==0){
			// Ancien modèle de table
			$cmd_ent_billingNom='cmd_ent_nom';
			$cmd_ent_billingPrenom='cmd_ent_prenom';
			// Mode iso
			$mode_utf8=false;
		}
	}else{
		// Message d'erreur
		$entries["error"]=$INFO->erreur.PHP_EOL.$req;
		// Il faut alors envoyer un tableau de DATA vide pour avoir le message affiché dans la table
		$entries["data"]='';
		// Renvoi au format JSON
		echo json_encode($entries);
		// On ne va pas plus loin
		exit;
	}

	//////////////////////////////////////////////////////////////////
	//                     LECTURE DES PARAMETRES					//
	//////////////////////////////////////////////////////////////////
	$start = $_GET['start']; // Borne de départ de selection SQL
	$limit = $_GET['length'];// Borne de fin de selection SQL
	$draw  = $_GET['draw'];	 // Compteur d'executions à retourner (sécurite obligatoire)

	// Nombre d'enregistrements au total dans la table produits
	$nbTotal = $_GET['nbRecords'];

	//////////////////////////////////////////////////////////////////
	//                    		 FILTRES							//
	//////////////////////////////////////////////////////////////////
	$cmdValid = $_GET["columns"]["1"]["search"]["value"];
	$cmdDownl = $_GET["columns"]["2"]["search"]["value"];
	$cmdCustr = $_GET["columns"]["3"]["search"]["value"];
	$cmdIdWeb = $_GET["columns"]["5"]["search"]["value"];
	$cmdIdLib = $_GET["columns"]["6"]["search"]["value"];

	if ($cmdValid!='*' && $cmdValid!=''){
		$search = "WHERE (cmd_ent_valid=$cmdValid)";
	}

	if ($cmdDownl!='*' && $cmdDownl!=''){
		if ($search==''){
			$search ="WHERE (cmd_ent_download=$cmdDownl)";
		}else{
			$search .=" AND (cmd_ent_download=$cmdDownl)";
		}
	}

	if($cmdCustr!=''){
		if ($search==''){
			$search="WHERE ($cmd_ent_billingNom LIKE '%$cmdCustr%' OR $cmd_ent_billingPrenom LIKE '%$cmdCustr%')";
		}else{
			$search.=" AND ($cmd_ent_billingNom LIKE '%$cmdCustr%' OR $cmd_ent_billingPrenom LIKE '%$cmdCustr%')";
		}
	}

	if ($cmdIdWeb!=''){
		if ($search==''){
			$search="WHERE (cmd_ent_id like '%$cmdIdWeb%')";
		}else{
			$search.=" AND (cmd_ent_id like '%$cmdIdWeb%')";
		}
	}

	if ($cmdIdLib!=''){
		if ($search==''){
			$search="WHERE (cmd_ent_id_libri like '%$cmdIdLib%')";
		}else{
			$search.=" AND (cmd_ent_id_libri like '%$cmdIdLib%')";
		}
	}

	//////////////////////////////////////////////////////////////////
	//                    		PRE-REQUETE							//
	//////////////////////////////////////////////////////////////////

	// Il faut une première requete 
	// SANS les limites pour avoir le bon nombre de résultats pour le filtre
	$req = "SELECT COUNT(*) AS NB
			FROM commande_entetes
			$search
			";
	// Tente d'exécuter la requête
	if ( $INFO->Query($req) ) {
		// Nombre d'enregistrements disponibles au total (sans les limites)
		$ROW = $INFO->Result();
		$nbSearch=$ROW['NB'];
	}else{
		// Message d'erreur
		$entries["error"]=$INFO->erreur.PHP_EOL.$req;
		// Il faut alors envoyer un tableau de DATA vide pour avoir le message affiché dans la table
		$entries["data"]='';
		// Renvoi au format JSON
		echo json_encode($entries);
		// On ne va pas plus loin
		exit;
	}

	//////////////////////////////////////////////////////////////////
	//                    	REQUETE FINALE							//
	//////////////////////////////////////////////////////////////////
 
	// Requete SQL finale
	$req = "SELECT
			cmd_ent_autoid,
			(CASE WHEN cmd_ent_valid=1 THEN 'Oui' ELSE 'Non' END) AS cmd_ent_valid ,
			(CASE WHEN cmd_ent_download=1 THEN 'Oui' ELSE 'Non' END) AS cmd_ent_download ,
			CONCAT($cmd_ent_billingNom,' ',$cmd_ent_billingPrenom) AS cmd_customer ,
			CONCAT(SUM(cmd_ent_montant_hfp+cmd_ent_montant_fp),' &euro;') AS cmd_montant ,
			CAST(cmd_ent_id AS CHAR) AS cmd_ent_id,
			CAST(cmd_ent_id_libri as CHAR) AS cmd_ent_id_libri
			FROM commande_entetes
			$search
			GROUP BY cmd_ent_id
			ORDER BY cmd_ent_id DESC
			LIMIT $start, $limit";

	// Tente d'exécuter la requête finale
	if( !$INFO->Query($req) ) {
		// Message d'erreur
		$entries["error"]=$INFO->erreur.PHP_EOL.$req;
		// Il faut alors envoyer un tableau de DATA vide pour avoir le message affiché dans la table
		$entries["data"]='';
		// Renvoi au format JSON
		echo json_encode($entries);
		// On ne va pas plus loin
		exit;
	}else{
		// Nombre d'appels (clic sur boutons de pagination ou recherches)
		$entries["draw"]=intval($draw);
		// Nombre d'enregistrement au total dans la table produits
		$entries["recordsTotal"]=intval($nbTotal);
		// Nombre d'enregistrements selon le filtre SANS les limites
		$entries["recordsFiltered"]=intval($nbSearch);
		// Si des enregistrements sont présents
		if ( $INFO->Num()>0 ) {
			// Charge le tableau de données
			while($ROW = $INFO->Result()) {
				// UTF8 ou ISO
				if ($mode_utf8){
					$entries["data"][]=$ROW;
				}else{
					$entries["data"][]=array_map("decode", $ROW);
				}
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

// Renvoi au format JSON
echo json_encode($entries);

// Convertit le résultat de l'ISO en UTF8
function decode ( $param ){
	return utf8_encode(urldecode($param));
}