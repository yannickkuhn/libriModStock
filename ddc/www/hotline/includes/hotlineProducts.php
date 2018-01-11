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
	$select='prod_id,';

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

	// Filtres de recherche
	$prodEan = $_GET["columns"]["1"]["search"]["value"]; // Recherche EAN
	$prodTtl = $_GET["columns"]["2"]["search"]["value"]; // Recherche TITRE
	$prodStk = $_GET["columns"]["3"]["search"]["value"]; // Recherche STOCK
	$voirtype= $_GET['voirtype']; // vrai ou faux

	// Recherche sur une des combos
	if($voirtype=='true'){
		$comboType=$_GET['comboType'];	 // Valeurs : Alpha 0,2,4... ou * (tous)
	}else{
		$comboType='*';
	}
	$comboSupr	= $_GET['comboSupr'];	// Valeurs : Alpha  0,1 ou * (tous)
	$comboStock	= $_GET['comboStock'];	// Valeurs : Alpha 0,1 ou * (tous)
	$comboCat	= $_GET['comboCat'];	// Valeurs : Alpha  0 à n ou * (tous)
	$comboPoids	= $_GET['comboPoids'];	// Valeurs : Alpha 0,1 ou * (tous)

	// Jointure par défaut
	$from  =', stock ';
	$where ='WHERE prod_ean = stock_ean';
	$and   ='';
	$order ='prod_titre';
	$search='';// Recherche vide par défaut

	//////////////////////////////////////////////////////////////////
	//                    		FILTRES								//
	//////////////////////////////////////////////////////////////////
	// Type d'article
	if ($voirtype=='true'){
		if ( $comboType!='*' ) {
			$search.=" AND prod_type_prix=$comboType";
		}
	} else{
		$search.='';
	}
 	// En stock
	if ( $comboStock=='0' ) {
		$from='LEFT JOIN stock ON prod_ean = stock_ean';
		$where='WHERE (stock_qte <=0 OR stock_qte IS NULL)';
	} elseif ($comboStock=='1') {
		$from='LEFT JOIN stock ON prod_ean = stock_ean';
	} else {
		$from='LEFT JOIN stock ON prod_ean = stock_ean';
		$where='';
	}
	// Catégorie
	if ($comboCat!='*') {
		if (strpos($where, 'WHERE')===false){
			$where='WHERE';
			$search .= ' (rub_1_id='.$comboCat.')';//' OR rub_2_id='.$comboCat.')';
		}else{
			$search .= ' AND (rub_1_id='.$comboCat.')';//' OR rub_2_id='.$comboCat.')';
		}
	}
	// Code EAN
	if ($prodEan!=''){
		if (strpos($where, 'WHERE')===false){
			$where='WHERE';
			$search .= " (prod_ean LIKE '%".$prodEan."%')";
		} else{
			$search .= " AND (prod_ean LIKE '%".$prodEan."%')";
		}
	}
	// Titre
	if ($prodTtl!=''){
		if (strpos($where, 'WHERE')===false){
			$where='WHERE';		
			$search .= " (prod_titre LIKE'%".$prodTtl."%')";
		} else{
			$search .= " AND (prod_titre LIKE'%".$prodTtl."%')";
		}
	}
	// Stock
	if ($prodStk!=''){
		$from  = 'LEFT JOIN stock ON prod_ean = stock_ean';
		$where = "WHERE (stock_qte ".$prodStk.")";
		$order = 'stock_qte DESC';
	}

	// supprimés
	if ($comboSupr!='*') {
		if (strpos($where, 'WHERE')===false){
			$where='WHERE';		
			$search.=' prod_deleted='.$comboSupr;
		} else {
			$search.=' AND prod_deleted='.$comboSupr;
		}
	} else{
		$and='';
	}	
	// Poids
	if ($comboPoids!='*'){
		if ($comboPoids==1){
			// Poids connu
			if (strpos($where, 'WHERE')===false){
				$where='WHERE';	
				$search.=' (prod_poids<>0)';
			} else {
				$search.=' AND (prod_poids<>0)';
			}
		}else{
			if (strpos($where, 'WHERE')===false){
				$where='WHERE';
				// Poids égal à zero
				$search.=' (prod_poids=0)';
			} else{
				$search.=' AND (prod_poids=0)';
			}
		}
	}

	//////////////////////////////////////////////////////////////////
	//                    		PRE-REQUETE							//
	//////////////////////////////////////////////////////////////////
	// Il faut une première requete 
	// SANS les limites pour avoir le bon nombre de résultats pour le filtre
	if($limit!=''){
		$reqNb= "SELECT COUNT(*) AS NB
				FROM produits $from
				$where $and
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
	// Requete SQL finale
	$req = "SELECT $select prod_ean, prod_titre, COALESCE(stock_qte, 0) AS stock_qte , CONCAT(prod_prixttc,' &euro;') AS prod_prixttc
			FROM produits $from
			$where $and
			$search
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