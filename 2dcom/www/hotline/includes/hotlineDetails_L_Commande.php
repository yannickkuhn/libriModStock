<?php

require_once "../../require/class_new_info.php";
$INFO = new Info();

if( isset($_GET['cmd_id']) ) {

	// Connection Mysql
	if( !$INFO->Connect() ) {
		echo $INFO->erreur;
		exit;
	}

	///////////////////////////////////////////////////////////////////////////////////////
	// Requete sur l'en-tete de la commande
	$req="
		SELECT cmd_ent_autoid, cmd_ent_date, cmd_ent_heure, cmd_ent_montant_fp
		FROM commande_entetes 
		WHERE cmd_ent_autoid=".$_GET['cmd_id'];
	if (!$INFO->Query($req)) {
		$mess=$INFO->erreur;
		exit;
	}
	// Résultat requete
	$ROW=$INFO->Result();
	// Mémorise les infos
	$dateCMD=$ROW['cmd_ent_date'];
	$heureCMD=$ROW['cmd_ent_heure'];
	$cmdfp=$ROW['cmd_ent_montant_fp'];
	if ($cmdfp!=0){
		$cmd_libri.='<br>Frais de port: '.$cmdfp.' €';
	}
	///////////////////////////////////////////////////////////////////////////////////////

	///////////////////////////////////////////////////////////////////////////////////////
	// Requete sur les lignes de la commande
	$req="
		SELECT *
		FROM commande_lignes
		WHERE cmd_ent_autoid=".$_GET['cmd_id'];
	///////////////////////////////////////////////////////////////////////////////////////

	if ($INFO->Query($req)){
		// Mets le tout dans des div
		$mess ='<div style="max-height:435px">'.PHP_EOL;

		// Si le numéro librisoft est connu
		if ( !empty($_GET['cmd_libri']) ) {
			$mess.='<div style="text-align:center;font-size:1.2em;background-color:#ddd;font-weight:bold;">Commande Librisoft n°:'.$_GET['cmd_libri'].$cmd_libri.'</div>'.PHP_EOL;
		}
		
		// Construit une table
		$mess.='<table class="display table table-striped table-bordered dataTable no-footer" cellspacing="1" width="100%">'.PHP_EOL;
			if ($INFO->Num()==0){
				$mess.='<thead>'.PHP_EOL;
					$mess.='<tr>'.PHP_EOL;
						$mess.='<th class="popup_erreur">Erreur</th>'.PHP_EOL;
					$mess.='</tr>'.PHP_EOL;
				$mess.='</thead>'.PHP_EOL;
				$mess.='<tr><td class="popup_erreur">Pas d\'articles pour cette commande</td></tr>'.PHP_EOL;
			}else{
				$mess.='<thead>'.PHP_EOL;
					$mess.='<tr>'.PHP_EOL;
						$mess.='<th>Identification</th>'.PHP_EOL;
						$mess.='<th>Valeur</th>'.PHP_EOL;
					$mess.='</tr>'.PHP_EOL;
				$mess.='</thead>'.PHP_EOL;
				$mess.='<tbody>'.PHP_EOL;

					// Liste des lignes de la commande
					while ($ROW=$INFO->Result()) {
						$mess.='<tr><td><b>'.$ROW['cmd_lig_ean'].'</b></td><td><b>'.utf8_encode(urldecode($ROW['cmd_lig_titre'])).'</b></td></tr>'.PHP_EOL;
						$mess.='<tr><td> - quantité</td><td>'.$ROW['cmd_lig_qte'].'</td></tr>'.PHP_EOL;
						$mess.='<tr><td> - prix ttc</td><td>'.$ROW['cmd_lig_prixttc'].' €</td></tr>'.PHP_EOL;
						$mess.='<tr><td> - TVA</td><td>'.$ROW['cmd_lig_tva'].' %</td></tr>'.PHP_EOL;
					}
			}
			$mess.='</tbody>'.PHP_EOL;
		$mess.='</table>'.PHP_EOL;
		// Fin de la table

		// Infos générales
		$mess.='Commande ajoutée le '.dateFr($dateCMD).' à '.$heureCMD.' - Enregistrement n° '.$_GET['cmd_id'];
		$mess.="</div>";

		// Renvoi du code HTML généré
		echo $mess;
	}
}

// Passe la date du format US au format FR
function dateFr($date){
	$date = new DateTime($date);
	return $date->format('d-m-Y');
}