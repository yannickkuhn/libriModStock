<?php
function point_virgule( $data ) {
	return str_replace("%3B", ";", $data);
}

if( isset($_GET) && !empty($_GET) && !empty($_GET["ean"]) ) {
	$EANSEARCH=$_GET["ean"];
	$filename=base64_decode($_GET['filename']);
	// ---------------------------------
	// Lance le traitement de recherche
	// ---------------------------------
	if(file_exists($filename) && $fp = @fopen($filename, "r")) {

		while(!feof($fp)) {
			// ---------------------------------
			// ON LIT CHAQUE LIGNE JUSQU'A TROUVER LE SEPARATEUR
			// ---------------------------------
			$buffer = fgets($fp, 4096);
			while(strpos($buffer, ";#<SEPARATOR>#") === false && !feof($fp)) {
				$buffer .= fgets($fp, 4096);
			}
			if(!empty($buffer)) {

				$line = explode(";", $buffer);
				$EAN["ean"]=urlencode(point_virgule(trim($line[1])));

				if($EANSEARCH == $EAN["ean"]) {

					echo "<ul><li><u>Article <b>$EANSEARCH</b> trouvé dans le fichier :</u></li><ul>\r\n";

					$EAN["add_data"] 		= addslashes(point_virgule(trim($line[0])));
					$EAN["isbn"]	 	   	= addslashes(point_virgule(trim($line[2])));
					$EAN["devise"]	 	  	= addslashes(point_virgule(trim($line[3])));
					$EAN["prixttc"]	 	  	= addslashes(point_virgule(trim($line[4])));
					$EAN["prixht"]	 	  	= addslashes(point_virgule(trim($line[5])));
					$EAN["idtva1"]	 	  	= addslashes(point_virgule(trim($line[6])));
					$EAN["tva1"]	 		= addslashes(point_virgule(trim($line[7])));
					$EAN["idtva2"]	 	  	= addslashes(point_virgule(trim($line[8])));
					$EAN["tva2"]	 	    = addslashes(point_virgule(trim($line[9])));
					$EAN["datepar"]	 	  	= addslashes(point_virgule(trim($line[10])));
					$EAN["iddispo"]	 	  	= addslashes(point_virgule(trim($line[11])));
					$EAN["titre"]	 		= addslashes(point_virgule(trim($line[12])));
					$EAN["editeur"]	 	  	= addslashes(point_virgule(trim($line[13])));
					$EAN["idediteur"]	 	= addslashes(point_virgule(trim($line[14])));
					$EAN["collection"]	   	= addslashes(point_virgule(trim($line[15])));
					$EAN["idcollection"]	= addslashes(point_virgule(trim($line[16])));
					$EAN["auteur"]	 	  	= addslashes(point_virgule(trim($line[17])));
					$EAN["theme_edl_g"]	 	= addslashes(point_virgule(trim($line[18])));
					$EAN["id_theme_edl_g"]	= addslashes(point_virgule(trim($line[19])));
					$EAN["theme_per_g"]	 	= addslashes(point_virgule(trim($line[20])));
					$EAN["id_theme_per_g"]	= addslashes(point_virgule(trim($line[21])));
					$EAN["support"]	 	  	= addslashes(point_virgule(trim($line[22])));
					$EAN["idsupport"]	 	= addslashes(point_virgule(trim($line[23])));
					$EAN["distributeur"]	= addslashes(point_virgule(trim($line[24])));
					$EAN["iddistrib"]	 	= addslashes(point_virgule(trim($line[25])));
					$EAN["poids"]	 		= addslashes(point_virgule(trim($line[26])));
					$EAN["epaisseur"]	 	= addslashes(point_virgule(trim($line[27])));
					$EAN["largeur"]	 	  	= addslashes(point_virgule(trim($line[28])));
					$EAN["hauteur"]	 	  	= addslashes(point_virgule(trim($line[29])));
					$EAN["qte"]	 		  	= addslashes(point_virgule(trim($line[30])));
					$EAN["occasion"]	    = addslashes(point_virgule(trim($line[31])));
					$EAN["ean_editeur"]	  	= addslashes(point_virgule(trim($line[32])));
					$EAN["etat"]			= addslashes(point_virgule(trim($line[33])));
					$EAN["isref"]			= addslashes(point_virgule(trim($line[34])));
					$EAN["resume"]			= stripslashes(point_virgule(base64_decode(trim($line[35]))));
					$EAN["typeDePrix"]	    = addslashes(point_virgule(trim($line[39])));

					// Construction résultat
					$RETOUR .= "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Indice. <b>Type</b>: Valeur<br>\r\n<ol>\r\n";
					foreach($EAN as $type => $val) {
						$RETOUR .= "<li><b>$type</b>:\t".utf8_encode(stripcslashes($val))."</li>\r\n";
					}
					$RETOUR .= "</ol>";
					// Pas la peine de continuer à parcourir tout le fichier si on a trouvé ce qui est recherché
					break;
				}
			}
		}
		// Fermeture du fichier
		fclose($fp);
		if (!empty($RETOUR)) {
			// Envoi le résultat
			echo $RETOUR;
		} else{
			echo "<ul><li class=\"popup_erreur\">Article <b >$EANSEARCH</b> INTROUVABLE dans le fichier UPDATEDB.TXT !</li></ul>\r\n";
		}
	}
}