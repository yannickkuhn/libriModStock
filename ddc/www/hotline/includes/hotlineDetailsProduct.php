<?php
require_once "../../require/class_new_info.php";

if( isset($_GET['prod_id']) ){

	$mess='';
	$lib ='';

	$INFO = new Info();

	if( !$INFO->Connect() ) {
		echo $INFO->erreur;
	}else{
		// Recherche sur le row id (unique)
		$req="SELECT * FROM produits WHERE prod_id=".$_GET['prod_id'];
		if ($INFO->Query($req,1)){

			// Résultat 1
			$ROW = $INFO->Result(1);
		
			// Libellé de la rubrique 1
			if ($ROW['rub_1_id']!=0){
				$req  ="SELECT rub_libelle from rubriques WHERE rub_id=".$ROW['rub_1_id'];
				$INFO->Query($req,2);
				$ROW1 =$INFO->Result(2);
				$lib  =$ROW1['rub_libelle'];
			}

			// Type d'article
			switch ($ROW['prod_type_prix']) {
				case 4 :
					$ROW['prod_type_prix']='Livre (4)';
					break;
				case 6 :
					$ROW['prod_type_prix']='Papeterie (6)';
					break;
				case 9 :
					$ROW['prod_type_prix']='Occasion (9)';
					break;
			}

			// Recherche du résumé
			$titre='';
			$resum='';
			if ( empty(trim($ROW['prod_resume'])) ){
				list( $titre , $resum )=rechercheResume($ROW['prod_ean']);
				$resum=utf8_encode($resum);
			}else{
				$titre="(LOCAL)";
				$resum=stripcslashes(stripcslashes(trim($ROW['prod_resume'])));
			}
			// Mets la table de résultats dans une div scrollable
			$mess .='<div name="table" style="max-height:650px">'.PHP_EOL;
			// Titre
			$mess .='<div style="text-align:center;font-size:1.2em;background-color:#ddd"><b>'. utf8_encode(urldecode($ROW['prod_titre'])).'</b></div>'.PHP_EOL;

			// Construit une table de résultats
			$mess.='<table class="display table table-striped table-bordered dataTable no-footer" cellspacing="1" width="100%">'.PHP_EOL;
				$mess.='<thead>'.PHP_EOL;
					$mess.='<tr>'.PHP_EOL;
						$mess.='<th width="200px">RESUME '.$titre.'</th>'.PHP_EOL;
						$mess.='<th>Paramètre</th>'.PHP_EOL;
						$mess.='<th>Valeur</th>'.PHP_EOL;
					$mess.='</tr>'.PHP_EOL;
				$mess.='</thead>'.PHP_EOL;
				$mess.='<tbody>'.PHP_EOL;
					// Resumé
					$mess.='<tr border="1"><td rowspan="16" border="1">'.$resum.			'</td>'.PHP_EOL;

					// Prix HT
					$mess.='<tr><td>Prix HT</td><td>'	.$ROW['prod_prixht'].	' €</td></tr>'.PHP_EOL;

					// TVA 1
					$mess.='<tr><td>TVA 1</td><td>'		.$ROW['prod_tva1'].		' %</td></tr>'.PHP_EOL;

					// TVA 2
					$mess.='<tr><td>TVA 2</td><td>'		.$ROW['prod_tva2'].		' %</td></tr>'.PHP_EOL;

					// Prix TTC
					$mess.='<tr><td>Prix TTC</td><td>'	.$ROW['prod_prixttc'].	' €</td></tr>'.PHP_EOL;

					// Prix promo connu ?
					if ($ROW['prod_prixpromo']!=0){
						$mess.='<tr><td>Prix promotionnel</td><td>'	.$ROW['prod_prixpromo'].	' €</td></tr>'.PHP_EOL;
					}else{
						$mess.='<tr><td>Prix promotionnel</td><td>Aucun</td></tr>'.PHP_EOL;
					}

					// Poids
					if ($ROW['prod_poids']!=0){
						$mess.='<tr><td>Poids</td><td>'	.$ROW['prod_poids'].	' g</td></tr>'.PHP_EOL;
					}else{
						$mess.='<tr><td>Poids</td><td class="popup_erreur">'.$ROW['prod_poids'].	' g</td></tr>'.PHP_EOL;
					}

					// Auteur connu ?
					if ($ROW['prod_auteurs']!=''){
						$mess.='<tr><td>Auteur</td><td>'.$ROW['prod_auteurs'].	'</td></tr>'.PHP_EOL;
					}else{
						$mess.='<tr><td>Auteur</td><td class="popup_erreur">Inconnu</td></tr>'.PHP_EOL;
					}
					// Editeur connu ?
					if ($ROW['prod_editeur']!=''){
						$mess.='<tr><td>Editeur</td><td>'.$ROW['prod_editeur'].	'</td></tr>'.PHP_EOL;
					}else{
						$mess.='<tr><td>Editeur</td><td class="popup_erreur">Inconnu</td></tr>'.PHP_EOL;
					}
					// Collection connue ?
					if ($ROW['prod_collection']!=''){
						$mess.='<tr><td>Collection</td><td>'.$ROW['prod_collection'].'</td></tr>'.PHP_EOL;
					}else{
						$mess.='<tr><td>Collection</td><td class="popup_erreur">Inconnue</td></tr>'.PHP_EOL;
					}
					// Genre connu ?
					if ( $ROW['prod_genre']!='' ){
						$mess.='<tr><td>Genre</td><td>'	.$ROW['prod_genre'].	'</td></tr>'.PHP_EOL;
					}else{
						$mess.='<tr><td>Genre</td><td class="popup_erreur">Inconnu</td></tr>'.PHP_EOL;
					}
					
					// Date de parution
					$mess.='<tr><td>Parution</td><td>'	.dateFr($ROW['prod_parution']).	'</td></tr>'.PHP_EOL;

					// Distributeur connu ?
					if ($ROW['prod_namedistrib']!=''){
						$mess.='<tr><td>Distributeur</td><td>'.$ROW['prod_namedistrib'].'</td></tr>'.PHP_EOL;
					}else{
						$mess.='<tr><td>Distributeur</td><td class="popup_erreur">Inconnu</td></tr>'.PHP_EOL;
					}

					// Type de support
					$mess.='<tr><td>Support</td><td>'	.$ROW['prod_support'].	'</td></tr>'.PHP_EOL;

					// Type article - 4: librairie, 6: papeterie, 9: occasion
					$mess.='<tr><td>Type d\'article</td><td>'.$ROW['prod_type_prix'].'</td></tr>'.PHP_EOL;

					// Catégorie connue ?
					if ( $lib!='' ){
						$mess.='<tr><td>Catégorie</td><td>'.utf8_encode(urldecode($lib)).' ('.$ROW['rub_1_id'].')</td></tr>'.PHP_EOL;
					} else{
						$mess.='<tr><td>Catégorie 1</td><td class="popup_erreur">Inconnue ('.$ROW['rub_1_id'].')</td></tr>'.PHP_EOL;
					}

				$mess.='</tbody>'.PHP_EOL;
			$mess.='</table>'.PHP_EOL;
			


			// Infos générales dans une autre div
			$mess.='<div name="info">'.PHP_EOL;
			$mess.='Ajouté le '.dateFr($ROW['prod_date_ajout']).' à '.$ROW['prod_heure_ajout'];
			// Test si l'enregistrement à déja été modifié
			if (validateDate($ROW['prod_date_mod'])){
				$mess.=' - Dernière modification le '.dateFr($ROW['prod_date_mod']).' à '.$ROW['prod_heure_mod'];
			}
			// Numéro d'enregistrement
			$mess.=' - Article n°'.$_GET['prod_id'].PHP_EOL;
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

// -------------------------------------------------------------
// Lance la recherche d'un résumé
// -------------------------------------------------------------
function rechercheResume($ean) {
	list($titre, $resume) = search_resume_on_bddi($ean);
	if( $resume!=null ) {
		$res=array( $titre , $resume );
		return $res;
	} else {
		list($titre, $resume)=search_resume_on_decitre($ean);
		$res=array( $titre , $resume );
		return $res;
	}
}

// -------------------------------------------------------------
// Recherche d'un résumé dans la base BDDI
// -------------------------------------------------------------
function search_resume_on_bddi($ean13) {
	$content = file_get_contents('http://bddi.2dcom.fr/GetResume.php?user=wsbddi&pw=ErG2i8Aj&ean='.$ean13);
	$search = "erreur resume non trouve";
	if (preg_match('/'.$search.'/', $content, $m)) {
		// Rien de trouvé
		return array( null , null );
	} else {
		$content = preg_replace(utf8_decode("/<b> Résumé : <\/b><br\/><br\/>/"),"",$content);
		return array( '(BDDI)' , trim(strip_tags($content)) );
	}
}

// -------------------------------------------------------------
// Recherche d'un résumé dans la base DECITRE
// -------------------------------------------------------------
function search_resume_on_decitre($ean13) {
	if(substr($ean13, 0, 3) == "978" || substr($ean13, 0, 3) == "979") {
		$retHeader = get_headers('http://www.decitre.fr/livres/livre-'.$ean13.'.html');
		if($retHeader[0]=="HTTP/1.1 404 Not Found") {
			return array( null , '<b class="popup_erreur">Absence de r&eacute;sum&eacute; LOCAL & BDDI & DECITRE.</b>' );
		} else {
			$content = file_get_contents('http://www.decitre.fr/livres/livre-'.$ean13.'.html');
			$content = preg_match('#<div class=\"block-content\" itemprop=\"description\">(.+)</div>#isU',$content,$matches);
			if( !empty($matches[1]) && isset($matches[1]) ) {
				return array( '(DECITRE)' , htmlentities(trim(strip_tags($matches[1]))) );
			} else {
				return array( null , '<b class="popup_erreur">Absence de r&eacute;sum&eacute; LOCAL & BDDI & DECITRE.</b>' );
			}
		}
	} else {
		return array( null , '<b class="popup_erreur">Absence de r&eacute;sum&eacute; LOCAL & BDDI & DECITRE.</b>' );
	}
}