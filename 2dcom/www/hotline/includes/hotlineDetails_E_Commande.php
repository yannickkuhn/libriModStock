<?php

require_once "../../require/class_new_info.php";
$INFO = new Info();

if( isset($_GET['cmd_id']) ) {

	// Connection Mysql
	if( !$INFO->Connect() ) {
		echo $INFO->erreur;
		exit;
	}


	//////////////////////////////////////////////////////////////////
	//                     COMPATIBILITE DE TABLE					//
	//////////////////////////////////////////////////////////////////
	// Champs par défaut
	$cmd_ent_billingNom='cmd_ent_billingNom';
	$cmd_ent_billingPrenom='cmd_ent_billingPrenom';
	$cmd_ent_billingAdresse='cmd_ent_billingAdresse';
	$cmd_ent_billingVille='cmd_ent_billingVille';
	$cmd_ent_billingCodePostal='cmd_ent_billingCodePostal';
	$cmd_ent_billingTel='cmd_ent_billingTel';
	$utf8=true;
	$messFact='';
	// Recherche si ce champ existe dans la table
	$req="SHOW COLUMNS FROM commande_entetes LIKE 'cmd_ent_billingNom'";
	// Tente d'exécuter la requête
	if ( $INFO->Query($req) ) {
		// Test du nombre d'enregistrements
		if($INFO->Num()==0){
			// Ancien modèle de table
			$cmd_ent_billingNom='cmd_ent_nom';
			$cmd_ent_billingPrenom='cmd_ent_prenom';
			$cmd_ent_billingAdresse='cmd_ent_adresse';
			$cmd_ent_billingVille='cmd_ent_ville';
			$cmd_ent_billingCodePostal='cmd_ent_code_postal';
			$cmd_ent_billingTel='cmd_ent_tel';
			$utf8=false;
			$messFact="et livraison ";
		}
	}else{
		// On ne va pas plus loin
		echo $INFO->erreur;
		exit;
	}
	///////////////////////////////////////////////////////////////////////////////////////
	// Requete sur la commande
	$req="
		SELECT *
		FROM commande_entetes 
		WHERE cmd_ent_autoid=".$_GET['cmd_id'];

	if (!$INFO->Query($req,1)) {
		$mess=$INFO->erreur;
		exit;
	}
	// Résultat requete 1
	$ROW=$INFO->Result(1);
	// Mémorise le statut pour la requete 2
	$statut_id=$ROW['statut_id'];
	$pai_id=$ROW['pai_id'];
	$expd_id=$ROW['expd_id'];
	$pai_stat=$ROW['cmd_ent_pai_statut'];
	///////////////////////////////////////////////////////////////////////////////////////
	// Requete sur le statut de la commande
	$req="
		SELECT statut_libelle
		FROM commande_statuts
		WHERE statut_id=$statut_id";
	if(!$INFO->Query($req,2)){
		echo $INFO->erreur;
		exit;
	}
	// Résultat requete 2
	$ROW_statut=$INFO->Result(2);
	///////////////////////////////////////////////////////////////////////////////////////
	// Requete sur le mode de paiement
	$req="SELECT pai_libelle FROM paiement WHERE pai_id=$pai_id";
	if ( !$INFO->Query($req,3) ){
		echo $INFO->erreur;
		exit;
	}
	if ( $INFO->Num(3)!=0 ) {
		$ROW_pai=$INFO->Result(3);
	} else {
		$ROW_pai='';
	}

	// Statut règlement
	$statreg=array(0=>' (Non réglée)',1=>' (Réglée)',9=>' (Rejeté/Annulé)');

	if (array_key_exists($pai_stat, $statreg)){
		$libmodreg=$statreg[$pai_stat];
	}
	///////////////////////////////////////////////////////////////////////////////////////
	// Requete sur le mode d'expédition
	$req="SELECT expd_libelle FROM expedition WHERE expd_id=$expd_id";
	if ( !$INFO->Query($req,4) ){
		echo $INFO->erreur;
		exit;
	}
	$ROW_expd=$INFO->Result(4);
	///////////////////////////////////////////////////////////////////////////////////////
	// Si le numéro librisoft est connu
	if ( !empty($_GET['cmd_libri']) ) {
		$mess='<div style="text-align:center;font-size:1.2em;background-color:#ddd"><b>Commande Librisoft n°:'.$_GET['cmd_libri'].'</b></div>'.PHP_EOL;
	}
	// Construit une table de résultats
	$mess.='<table class="display table table-striped table-bordered dataTable no-footer" cellspacing="1" width="100%">'.PHP_EOL;
		$mess.='<thead>'.PHP_EOL;
			$mess.='<tr>'.PHP_EOL;
				$mess.='<th>Identification</th>'.PHP_EOL;
				$mess.='<th>Valeur</th>'.PHP_EOL;
			$mess.='</tr>'.PHP_EOL;
		$mess.='</thead>'.PHP_EOL;
		$mess.='<tbody>'.PHP_EOL;

			// Selon le type de structure de table
			if ($utf8==true){
				//////////////////////////
				// 			UTF8 		//
				//////////////////////////
				// Statut
				$mess.='<tr><td>Statut</td><td>'.$ROW_statut['statut_libelle'].'</td></tr>'.PHP_EOL;

				// Montant hors frais de port
				$mess.='<tr><td>Montant des achats <em>(hors frais de port)</em></td><td>'.$ROW['cmd_ent_montant_hfp'].' €</td></tr>'.PHP_EOL;

				// Frais de port
				$mess.='<tr><td>Frais de port</td><td>'.$ROW['cmd_ent_montant_fp'].' €</td></tr>'.PHP_EOL;

				// Mode de paiement
				if ($ROW_pai['pai_libelle']!=''){
					$mess.='<tr><td>Mode de paiement</td><td>'.$ROW_pai['pai_libelle'].$libmodreg.'oo</td></tr>'.PHP_EOL;
				}else{
					$mess.='<tr><td>Mode de paiement</td><td class="popup_erreur">Pas de règlement.</td></tr>'.PHP_EOL;
				}
				
				// Mode d'expédition
				if ($ROW_expd['expd_libelle']!=''){
					$mess.='<tr><td>Mode d\'expédition</td><td>'.utf8_encode($ROW_expd['expd_libelle']).'</td></tr>'.PHP_EOL;
				}else{
					$mess.='<tr><td>Mode d\'expédition</td><tdclass="popup_erreur">Inconnu</td></tr>'.PHP_EOL;
				}

				// Poids total
				$mess.='<tr><td>Poids total</td><td>'.$ROW['cmd_ent_poids'].' <b>g</b></td></tr>'.PHP_EOL;

				// Facturation
				$mess.='<tr><td colspan="2"><b>Facturation '.$messFact.'à '.strtoupper($ROW[$cmd_ent_billingNom].' '.$ROW[$cmd_ent_billingPrenom]).'</b></td></tr>'.PHP_EOL;

				// Facturation société
				if ( $ROW['cmd_ent_billingSociete']!='' ) {
					$mess.='<tr><td> - société</td><td>'.$ROW['cmd_ent_billingSociete'].'</td></tr>'.PHP_EOL;
				}

				// Adresse de facturation
				$mess.='<tr><td> - adresse</td><td>'.$ROW[$cmd_ent_billingAdresse].'</td></tr>'.PHP_EOL;
				$mess.='<tr><td> - ville & code postal</td><td>'.$ROW[$cmd_ent_billingVille].' - '.$ROW[$cmd_ent_billingCodePostal].'</td></tr>'.PHP_EOL;
				if ( $ROW[$cmd_ent_billingTel]!='' ){
					$mess.='<tr><td> - téléphone</td><td>'.$ROW[$cmd_ent_billingTel].'</td></tr>'.PHP_EOL;
				}

				// Livraison
				$mess.='<tr><td colspan="2"><b>Livraison à '.strtoupper($ROW['cmd_ent_deliveryNom'].' '.$ROW['cmd_ent_deliveryPrenom']).'</b></td></tr>'.PHP_EOL;

				// Livraison société
				if ( $ROW['cmd_ent_deliverySociete']!='' ) {
					$mess.='<tr><td> - société</td><td>'.$ROW['cmd_ent_deliverySociete'].'</td></tr>'.PHP_EOL;
				}

				// Adresse de livraison
				$mess.='<tr><td> - adresse</td><td>'.$ROW['cmd_ent_deliveryAdresse'].'</td></tr>'.PHP_EOL;
				$mess.='<tr><td> - ville & code postal</td><td>'.$ROW['cmd_ent_deliveryVille'].' - '.$ROW['cmd_ent_deliveryCodePostal'].'</td></tr>'.PHP_EOL;
				if ( $ROW['cmd_ent_deliveryTel']!='' ) {
					$mess.='<tr><td> - téléphone</td><td>'.$ROW['cmd_ent_deliveryTel'].'</td></tr>'.PHP_EOL;
				}
			}else{
				//////////////////////////
				// 			ISO 		//
				//////////////////////////
				// Statut
				$mess.='<tr><td>Statut</td><td>'.utf8_encode(urldecode($ROW_statut['statut_libelle'])).'</td></tr>'.PHP_EOL;

				// Montant hors frais de port
				$mess.='<tr><td>Montant des achats <em>(hors frais de port)</em></td><td>'.$ROW['cmd_ent_montant_hfp'].' €</td></tr>'.PHP_EOL;

				// Frais de port
				$mess.='<tr><td>Frais de port</td><td>'.$ROW['cmd_ent_montant_fp'].' €</td></tr>'.PHP_EOL;

				// Mode de paiement
				if ($ROW_pai['pai_libelle']!=''){
					$mess.='<tr><td>Mode de paiement</td><td>'.utf8_encode(urldecode($ROW_pai['pai_libelle'])).$libmodreg.'</td></tr>'.PHP_EOL;
				}else{
					$mess.='<tr><td>Mode de paiement</td><td class="popup_erreur">Pas de règlement.</td></tr>'.PHP_EOL;
				}

				// Mode d'expédition
				if ($ROW_expd['expd_libelle']!=''){
					$mess.='<tr><td>Mode d\'expédition</td><td>'.utf8_encode(urldecode($ROW_expd['expd_libelle'])).'</td></tr>'.PHP_EOL;
				}else{
					$mess.='<tr><td>Mode d\'expédition</td><td class="popup_erreur">Inconnu</td></tr>'.PHP_EOL;
				}

				// Poids total
				$mess.='<tr><td>Poids total</td><td>'.$ROW['cmd_ent_poids'].' <b>g</b></td></tr>'.PHP_EOL;

				// Facturation
				$mess.='<tr><td colspan="2"><b>Facturation '.$messFact.'à '.strtoupper(utf8_encode(urldecode($ROW[$cmd_ent_billingNom])).' '.utf8_encode(urldecode($ROW[$cmd_ent_billingPrenom]))).'</b></td></tr>'.PHP_EOL;

				// Adresse simplifiée
				$mess.='<tr><td> - adresse</td><td>'.utf8_encode(urldecode($ROW[$cmd_ent_billingAdresse])).'</td></tr>'.PHP_EOL;
				$mess.='<tr><td> - ville & code postal</td><td>'.utf8_encode(urldecode($ROW[$cmd_ent_billingVille])).' - '.utf8_encode(urldecode($ROW[$cmd_ent_billingCodePostal])).'</td></tr>'.PHP_EOL;

				// Téléphone
				if ( $ROW[$cmd_ent_billingTel]!='' ) {
					$mess.='<tr><td> - téléphone</td><td>'.utf8_encode(urldecode($ROW[$cmd_ent_billingTel])).'</td></tr>'.PHP_EOL;
				}
			}

		$mess.='</tbody>'.PHP_EOL;
	$mess.='</table>'.PHP_EOL;
	// Fin de la table

	// Infos générales
	$mess.='Commande ajoutée le '.dateFr($ROW['cmd_ent_date']).' à '.$ROW['cmd_ent_heure'];
	// Numéro d'enregistrement
	$mess.=' - Enregistrement n° '.$ROW['cmd_ent_autoid'].PHP_EOL;

	// Renvoi du code HTML généré
	echo $mess;

}else{
	// Rien dans le GET
	echo "Pas de paramètres recus";
}

// Passe la date du format US au format FR
function dateFr($date){
	$date = new DateTime($date);
	return $date->format('d-m-Y');
}