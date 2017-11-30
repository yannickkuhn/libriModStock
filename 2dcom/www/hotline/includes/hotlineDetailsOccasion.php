<?php
require_once "../../require/class_new_info.php";

if( isset($_GET['prod_assoc_id']) ){

	$mess='';
	$lib ='';

	$INFO = new Info();

	if( !$INFO->Connect() ) {
		echo $INFO->erreur;
	}else{
		// Recherche sur le row id (unique)
		$req="SELECT p.*, s.stock_qte 
				FROM produit_associations AS p 
				LEFT JOIN stock AS s ON p.prod_assoc_ean = s.stock_ean
				WHERE prod_assoc_id=".$_GET['prod_assoc_id']."";
		if ($INFO->Query($req,1)){

			// Résultat 1
			$ROW = $INFO->Result(1);

			// Libellé de la rubrique perso
			if ($ROW['prod_assoc_rub_per_id']!=0){
				$req  ="SELECT rub_libelle from rubriques WHERE rub_id=".$ROW['prod_assoc_rub_per_id'];
				$INFO->Query($req,2);
				$ROW1 =$INFO->Result(2);
				$lib  =$ROW1['rub_libelle'];
			}

			// Mets la table de résultats dans une div scrollable
			$mess .='<div name="table" style="max-height:650px">'.PHP_EOL;
			// Titre
			$mess .='<div style="text-align:center;font-size:1.2em;background-color:#ddd"><b>'. utf8_encode(urldecode($ROW['prod_assoc_titre'])).'</b></div>'.PHP_EOL;

			// Construit une table de résultats
			$mess.='<table class="display table table-striped table-bordered dataTable no-footer" cellspacing="1" width="100%">'.PHP_EOL;
				$mess.='<thead>'.PHP_EOL;
					$mess.='<tr>'.PHP_EOL;
						$mess.='<th>Paramètre</th>'.PHP_EOL;
						$mess.='<th>Valeur</th>'.PHP_EOL;
					$mess.='</tr>'.PHP_EOL;
				$mess.='</thead>'.PHP_EOL;
				$mess.='<tbody>'.PHP_EOL;
					// Prix HT
					$mess.='<tr><td>Prix HT</td><td>'	.$ROW['prod_assoc_prixht'].' €</td></tr>'.PHP_EOL;
					// Prix TTC
					$mess.='<tr><td>Prix TTC</td><td>'	.$ROW['prod_assoc_prixttc'].' €</td></tr>'.PHP_EOL;
					// Stock
					$mess.='<tr><td>Stock</td><td>'		.$ROW['stock_qte'].'</td></tr>'.PHP_EOL;
					// Auteur connu ?
					if ($ROW['prod_assoc_auteurs']!=''){
						$mess.='<tr><td>Auteur</td><td>'.$ROW['prod_assoc_auteurs'].'</td></tr>'.PHP_EOL;
					}else{
						$mess.='<tr><td>Auteur</td><td class="popup_erreur">Inconnu</td></tr>'.PHP_EOL;
					}
					// Editeur connu ?
					if ($ROW['prod_assoc_editeur']!=''){
						$mess.='<tr><td>Editeur</td><td>'.$ROW['prod_assoc_editeur'].'</td></tr>'.PHP_EOL;
					}else{
						$mess.='<tr><td>Editeur</td><td class="popup_erreur">Inconnu</td></tr>'.PHP_EOL;
					}
					// Collection connue ?
					if ($ROW['prod_assoc_collection']!=''){
						$mess.='<tr><td>Collection</td><td>'.$ROW['prod_assoc_collection'].'</td></tr>'.PHP_EOL;
					}else{
						$mess.='<tr><td>Collection</td><td class="popup_erreur">Inconnue</td></tr>'.PHP_EOL;
					}
					// Date de parution
					$mess.='<tr><td>Parution</td><td>'	.dateFr($ROW['prod_assoc_parution']).'</td></tr>'.PHP_EOL;
					// Type de support
					$mess.='<tr><td>Support</td><td>'	.$ROW['prod_assoc_support'].'</td></tr>'.PHP_EOL;

					// Poids
					if ($ROW['prod_assoc_poids']!=0){
						$mess.='<tr><td>Poids</td><td>'	.$ROW['prod_assoc_poids'].' g</td></tr>'.PHP_EOL;
					}else{
						$mess.='<tr><td>Poids</td><td class="popup_erreur">'.$ROW['prod_assoc_poids'].' g</td></tr>'.PHP_EOL;
					}

					// Catégorie connue ?
					if ( $lib!='' ){
						$mess.='<tr><td>Catégorie</td><td>'.utf8_encode(urldecode($lib)).' ('.$ROW['prod_assoc_rub_per_id'].')</td></tr>'.PHP_EOL;
					} else{
						$mess.='<tr><td>Catégorie 1</td><td class="popup_erreur">Inconnue ('.$ROW['prod_assoc_rub_per_id'].')</td></tr>'.PHP_EOL;
					}

				$mess.='</tbody>'.PHP_EOL;
			$mess.='</table>'.PHP_EOL;
			


			// Infos générales dans une autre div
			$mess.='<div name="info">'.PHP_EOL;
			$mess.='Ajouté le '.dateFr($ROW['prod_assoc_date_ajout']).' à '.$ROW['prod_assoc_heure_ajout'];
			// Test si l'enregistrement à déja été modifié
			if (validateDate($ROW['prod_assoc_date_mod'])){
				$mess.=' - Dernière modification le '.dateFr($ROW['prod_assoc_date_mod']).' à '.$ROW['prod_assoc_heure_mod'];
			}
			// Numéro d'enregistrement
			$mess.=' - Article d\'occasion n°'.$_GET['prod_assoc_id'].PHP_EOL;
			$mess.='</div>'.PHP_EOL;
	$mess.='</div>'.PHP_EOL;
			// Renvoi du code HTML généré
			echo $mess;

		}else{
			echo $INFO->erreur;
		}
	}
} else {
	// Rien dans le GET
	echo "Pas de paramètres recus";
}

// -------------------------------------------------------------
// Passe la date du format US au format FR
// -------------------------------------------------------------
function dateFr($date){
	$date = new DateTime($date);
	return $date->format('d-m-Y');
}

// -------------------------------------------------------------
// Renvoi vrai ou faux
// -------------------------------------------------------------
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}