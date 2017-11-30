<?php
function point_virgule( $data ) {
	return str_replace("%3B", ";", $data);
}

if( isset($_GET) && !empty($_GET) ) {
	require_once __DIR__."/../../require/class_new_info.php";
	$INFO = new Info();
	// ---------------------------------
	// Lance le traitement de recherche
	// ---------------------------------
	$filename=base64_decode($_GET['filename']);
	$rubname=$_GET['rubname'];
	if(file_exists($filename) && $fp = @fopen($filename, "r")) {

		$STATS = array();

		while(!feof($fp)) {
			// ON LIT CHAQUE LIGNE JUSQU'A TROUVER LE SEPARATEUR
			$buffer = fgets($fp, 4096);
			while(strpos($buffer, ";#<SEPARATOR>#") === false && !feof($fp)) {
				$buffer .= fgets($fp, 4096);
			}
			if(!empty($buffer)) {
				$line = explode(";", $buffer);

				$EAN["theme_edl_g"]	 	= (point_virgule(trim($line[18])));
				$EAN["id_theme_edl_g"]	= (point_virgule(trim($line[19])));
				$EAN["theme_per_g"]	 	= (point_virgule(trim($line[20])));
				$EAN["id_theme_per_g"]	= (point_virgule(trim($line[21])));

				// UTF8
				$EAN = array_map("utf8_encode", $EAN);

				
				// En fonction de la sélection dans la combo
				if ($rubname=='Toutes'){
					// TOUTES les rubriques
					if(!isset($STATS[$EAN["id_theme_edl_g"]."!#!".$EAN["theme_edl_g"]."!#!CLIL"])) {
						$STATS[$EAN["id_theme_edl_g"]."!#!".$EAN["theme_edl_g"]."!#!CLIL"]=0;
					}
					$STATS[$EAN["id_theme_edl_g"]."!#!".$EAN["theme_edl_g"]."!#!CLIL"]+=1;
					if(!isset($STATS[$EAN["id_theme_per_g"]."!#!".$EAN["theme_per_g"]."!#!PERSO"])) {
						$STATS[$EAN["id_theme_per_g"]."!#!".$EAN["theme_per_g"]."!#!PERSO"]=0;
					}
					$STATS[$EAN["id_theme_per_g"]."!#!".$EAN["theme_per_g"]."!#!PERSO"]+=1;
				} elseif ($rubname=='CLIL') {
					// Que CLIL
					if(!isset($STATS[$EAN["id_theme_edl_g"]."!#!".$EAN["theme_edl_g"]."!#!CLIL"])) {
						$STATS[$EAN["id_theme_edl_g"]."!#!".$EAN["theme_edl_g"]."!#!CLIL"]=0;
					}
					$STATS[$EAN["id_theme_edl_g"]."!#!".$EAN["theme_edl_g"]."!#!CLIL"]+=1;
				} else {	
					// Que PERSO
					if(!isset($STATS[$EAN["id_theme_per_g"]."!#!".$EAN["theme_per_g"]."!#!PERSO"])) {
						$STATS[$EAN["id_theme_per_g"]."!#!".$EAN["theme_per_g"]."!#!PERSO"]=0;
					}
					$STATS[$EAN["id_theme_per_g"]."!#!".$EAN["theme_per_g"]."!#!PERSO"]+=1;
				}
				
			}
		}

		// Fermeture du fichier
		fclose($fp);

		$subtotal = 0;
		$tab_wrong_section = $INFO->wrong_section;
	
		//var_dump($STATS);

		foreach($STATS as $rubrique => $nbproduct) {
			$RUBRIQUE = explode('!#!', $rubrique);
			// Articles wrong_sections
			if(in_array($RUBRIQUE[0], $tab_wrong_section)){
				$subtotal += $nbproduct;
			}
			// Articles sans libellé de theme
			if ($RUBRIQUE[1]=='') {
				$notheme+=1;
				$nolibrub+=$nbproduct;
			}
			// Articles dans la rubrique CLIL
			if ($RUBRIQUE[2]=='CLIL') {
				$thCLIL+=1;
				$nbCLIL+=$nbproduct;
			}
			// Articles dans la rubrique PERSO
			if ($RUBRIQUE[2]=='PERSO') {
				$thPERSO+=1;
				$nbPERSO+=$nbproduct;
			}
			// Articles au total
			$totalArt+=$nbproduct;
		}

		// Affichage du nombre de produits dans les rubriques ci-dessous
		if ($subtotal!=0 || $nolibrub!=0 || $totalArt!=0) {
			echo "<h2>Statistiques sur le nombre de produits dans les rubriques sélectionnées :</h2>\r\n";
		}
		// Affichage du nombre de produits dans les rubriques ci-dessous
		if ($subtotal!=0) {
			echo "Dans les rubriques PERSO black listées (wrong_section), il y a un total de ".number_format($subtotal, 0, ',', ' ')." produits (lignes en rouge) !<br/>\r\n";
		}
		// Affichage du nombre de produits dans les rubriques ci-dessous
		if ($nolibrub!=0) {
			echo "Les lignes de couleur bleue ne comportent pas de libellé associé au thème (soit ".number_format($nolibrub, 0, ',', ' ')." articles dans ".number_format($notheme, 0, ',', ' ')." rubriques).<br/><br/>\r\n";
		}
		if ($nbCLIL!=0) {
			//echo "Il y a un total de ".number_format($nbCLIL, 0, ',', ' ')." articles dans $thCLIL thèmes pour la rubrique CLIL.<br/>\r\n";
			echo "Il y a $thCLIL thèmes pour la rubrique CLIL.<br/>\r\n";
		}
		if ($nbPERSO!=0) {
			//echo "Il y a un total de ".number_format($nbPERSO, 0, ',', ' ')." articles dans $thPERSO thèmes pour la rubrique PERSO.<br/>\r\n";
			echo "Il y a $thPERSO thèmes pour la rubrique PERSO.<br/>\r\n";
		}
		// Affichage du nombre de produits dans les rubriques ci-dessous
		if ($totalArt!=0 && $rubname=='Toutes') {
			//echo "<b>Il y a un total de ".number_format($totalArt, 0, ',', ' ')." articles dans le fichier.</b>\r\n";
		}

		// Affichage du nombre de produits par rubrique
		ksort($STATS, SORT_NUMERIC); // tri par ordre croissant

		$RETOUR .= "<h2>Statistiques sur le nombre de produits (rubrique ".strtoupper($rubname).") :</h2>\r\n";
		$RETOUR .= "<table class=\"display table table-striped table-bordered dataTable\"><thead><tr><th>ID</th><th>THEME</th><th>ARTICLES</th><th>RUBRIQUE</th></tr></thead><tbody>\r\n";

		foreach($STATS as $rubrique => $nbproduct) {
			$RUBRIQUE = explode('!#!', $rubrique);
			$RETOUR .= "<tr";

			if(in_array($RUBRIQUE[0], $tab_wrong_section)) {
				$RETOUR .= " style=\"color: red;\" ";
			}
			if(empty($RUBRIQUE[1])){
				$RETOUR .= " style=\"color: #0066ff;\"";
				$RUBRIQUE[1]="Libellé associé absent.";
			}
			$RETOUR .= "><td style=\"width : 60px;\">".$RUBRIQUE[0]."</td><td style=\"width : 550px;\">".$RUBRIQUE[1]."</td><td style=\"width : 80px;\">$nbproduct</td><td style=\"width : 100px;\">".$RUBRIQUE[2]."</td></tr>\r\n";

		}
		$RETOUR .= "</tbody></table>";
		echo $RETOUR;
	}
}