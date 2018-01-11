<?php
class Func {
	/**
	 *  Mise en forme de la date au format aaaammjj
	 *
	 * @param date $data Date US (aaaa-mm-jj)
	 * @return string Date au format aaaammjj
	 */
	function simpledateen( $data ) {
		$return = substr($data, 0, 4)."-".substr($data, 4, 2)."-".substr($data, 6, 2);
		return $return;
	}
	
	/**
	 * Controle si une date est vide (0000-00-00) et retrourne "" si vrai
	 *
	 * @param date $data Date US (aaaa-mm-jj)
	 * @return string retourne vide ou la date US (aaaa-mm-jj)
	 */
	function nodate( $data ) {
		if($data == "0000-00-00") return "";
		else return urldecode($data);
	}
	
	/**
	 * Date US vers date FR (jj/mm/aaaa)
	 *
	 * @param date $data Date US (aaaa-mm-jj)
	 * @return string Date FR (jj/mm/aaaa)
	 */
	function datefr( $data ) {
		if(empty($data)) return;

		$return = explode("-", $data);

		return $return[2]."/".$return[1]."/".$return[0];
	}

	/**
	 * Date FR vers date US (aaaa-mm-jj)
	 *
	 * @param string $data Date FR (jj/mm/aaaa)
	 * @return date Date US (aaaa-mm-jj)
	 */
	function dateen( $data ) {
		if(empty($data)) return;

		$return = explode("/", $data);

		return $return[2]."-".$return[1]."-".$return[0];
	}

	/**
	 * Retourne une date - le nombre d'année/mois/jours spécifié
	 *
	 * @param date $date Date de départ au format US
	 * @param int $y Nombre d'année à soustraire
	 * @param int $m Nombre de mois à soustraire
	 * @param int $d Nombre de jours à soustraire
	 * @return date
	 */
	function make_date( $date, $y = 0, $m = 0, $d = 0 ) {
		$date = explode("-", $date);
		$date = strftime("%Y-%m-%d", mktime(0, 0, 0, ($date[1] - $m), ($date[2] - $d), ($date[0] - $y)));

		return $date;
	}

	/**
	 * Retourne "s" si le nb de départ est supérieur à 1
	 *
	 * @param int $data
	 * @return string
	 */
	function pluriel( $data ) {
		if($data > 1) return "s";
	}

	function date_letter( $data ) {
		// w : Jour de la semaine au format numérique
		// d : Jour du mois, sur deux chiffres (avec un zéro initial)
		// n : Mois sans les zéros initiaux
		// Y : Année

		$jour_fr = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");
		$mois_fr = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

		list($nom_jour, $jour, $mois, $annee) = explode('/', $data);

		return $jour_fr[$nom_jour]." ".$jour." ".$mois_fr[(int)$mois]." ".$annee;
	}

	function date_letter2( $data ) {
		// d : Jour du mois, sur deux chiffres (avec un zéro initial)
		// n : Mois sans les zéros initiaux
		// Y : Année

		$mois_fr = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

		list($jour, $mois, $annee) = explode('/', $data);

		return $jour." ".$mois_fr[(int)$mois]." ".$annee;
	}

	function month_fr( $m ) {
		$mois_fr = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

		return $mois_fr[(int)$m];
	}
	
	function is_978( $data ) {
		$validCode = array("978", "979");
		
		if(in_array(substr($data, 0, 3), $validCode)) return true;
		return false;
	}

	function civilite( $data ) {
		switch($data) {
			case "1":
				return "M.";
				break;
			case "2":
				return "Mle";
				break;
			case "3":
				return "Mme";
				break;
		}
	}
	
	function replace_wrong_charxml( $data ) {
		$data = str_replace("&", "et", $data);
		
		return $data;
	}
	
	function maj_first_letter_by_word( $data ) {
		return ucwords(strtolower($data));
	}
	
	function size( $size ) {
		$go = (1024 * 1024 * 1024);
		$mo = (1024 * 1024);
		$ko = 1024;

		if($size >= $go) $size = round($size / $go * 100) / 100 . " Go";
		elseif($size >= $mo) $size = round($size / $mo * 100) / 100 . " Mo";
		elseif($size >= $ko) $size = round($size / $ko * 100) / 100 . " Ko";
		else $size = $size . " o";

		return $size;
	}
	
	function pound( $pound ) {
		$kg = 1000;

		if($pound >= $kg) $pound = round($pound / $kg * 100) / 100 . " Kg";
		else $pound = $pound . " g";

		return $pound;
	}

	function control_var( $data, $requiredvar ) {
		while(list($key, $val) = each($requiredvar)) {
			if(empty($data[$val])) return $val;
		}
	}

	function control_var_xml( $data, $requiredvar ) {
		global $INFO;

		while(list($key, $val) = each($requiredvar)) {
			if(empty($data[$val])) {
				$INFO->erreur = $INFO->returnxml( "-1", "DONNEES: Le champ \"".$val."\" est obligatoire" );
				return false;
			}
		}
		return true;
	}

	function post_to_get( $data ) {
		$return = "";

		while(list($key, $val) = each($data)) {
			if($key !== "load" && $key !== "act") $return .= "&".urlencode($key)."=".urlencode($val);
		}

		return $return;
	}

	function valid_mail( $mail ) {
		// DECODE
		$mail = urldecode($mail);

		$nonascii      = "\x80-\xff"; # Les caractères Non-ASCII ne sont pas permis

		$nqtext        = "[^\\\\$nonascii\015\012\"]";
		$qchar         = "\\\\[^$nonascii]";

		$protocol      = '(?:mailto:)';

		$normuser      = '[a-zA-Z0-9][a-zA-Z0-9_.-]*';
		$quotedstring  = "\"(?:$nqtext|$qchar)+\"";
		$user_part     = "(?:$normuser|$quotedstring)";

		$dom_mainpart  = '[a-zA-Z0-9][a-zA-Z0-9._-]*\\.';
		$dom_subpart   = '(?:[a-zA-Z0-9][a-zA-Z0-9._-]*\\.)*';
		$dom_tldpart   = '[a-zA-Z]{2,5}';
		$domain_part   = "$dom_subpart$dom_mainpart$dom_tldpart";

		$regex         = "$protocol?$user_part\@$domain_part";

		return preg_match("/^$regex$/", $mail);
	}

	function format_decimal( $data ) {
		//return number_format(str_replace(" ", "", $data), 2, ".", "");
		if(empty($data)) return $data;
		
		return number_format(str_replace(",", ".", str_replace(" ", "", $data)), 2, ".", "");
		//return str_replace(",", ".", $data);
	}

	function format_number( $data ) {
		$data = str_replace(" ", "", $data);
		return number_format(str_replace(",", ".", $data), 2, ",", " ");
	}

	function format_number_2( $data ) {
		$data = str_replace(" ", "", $data);
		return number_format(str_replace(",", ".", $data), 0, " ", " ");
	}

	function point_virgule( $data ) {
		return str_replace("%3B", ";", $data);
	}

	function return_html_url( $data ) {
		$return = explode("?", urldecode($data));
		return $return[0];
	}

	function encoding( $data ) {
		return urlencode(convert_uuencode($data));
	}

	function decoding( $data ) {
		return convert_uudecode(urldecode($data));
	}

	function url_encoding( $data ) {
		return urlencode(base64_encode($data));
	}

	function url_decoding( $data ) {
		return base64_decode(urldecode($data));
	}
	
	function implodeSQLString( $array ) {
		$return = "";
		$k 		= 0;
		foreach($array as $value) {
			if($k > 0) $return .= ", ";
			$return .= "\"".$value."\"";
			$k++;
		}
		
		return $return;
	}

	// Vérifie que le code EAN13 est valide //
	function is_ean( $EAN13 ) {
		$type = substr($EAN13, 0, 3);

		if(strlen($EAN13) == 13 && is_numeric($EAN13) && $type == "978") {
			$somme = 0;
				
			for($i = 2; $i <= 12; $i += 2) {
				$somme += (int)substr($EAN13, ($i - 1), 1);
			}
				
			$somme = $somme * 3;
				
			for($i = 1; $i <= 11; $i+= 2) {
				$somme += (int)substr($EAN13, ($i - 1), 1);
			}
				
			if((10 - ($somme % 10) == substr($EAN13, 12, 1)) or (($somme % 10) == substr($EAN13, 12, 1))) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}
	////

	// Transforme un EAN13 en ISBN //
	function ean_to_isbn( $data ) {
		if(substr($data, 0, 3) == "978") {
			$isbn  = substr($data, 3, 9);
			$somme = 0;
				
			for($i = 1; $i <= 9; $i++) {
				$somme += (int)substr($isbn, ($i - 1), 1) * (11 - $i);
			}
				
			if(11 - ($somme % 11) > 10) {
				$isbn .= "0";
			}
			else {
				if(11 - ($somme % 11) == "10") $isbn .= "X";
				else $isbn .= 11 - ($somme % 11);
			}
				
			return $isbn;
		}
	}
	////

	// Transforme un ISBN en EAN13 //
	function isbn_to_ean( $data ) {
		$data = ereg_replace("-", "", $data);
		$ean = "978".substr($data, 0, 9);
		$somme = 0;

		for($i = 2; $i <= 12; $i += 2) {
			$somme += substr($ean, ($i -1), 1);
		}

		$somme = $somme * 3;

		for($i = 1; $i <= 11; $i += 2) {
			$somme += substr($ean, ($i - 1), 1);
		}

		if((10 - ($somme % 11)) >= 10) {
			$ean .= 0;
		}
		else {
			if((10 - ($somme % 10)) == 10) $ean .= 0;
			else $ean .= (10 - ($somme % 10));
		}

		return $ean;
	}
	////

	function is_utf8( $data ) {
		if(is_array($data)) {
			$data = implode("", $data);
			// retourne FALSE si aucun caractere n'appartient au jeu utf8
			return !((ord($data[0]) != 239) && (ord($data[1]) != 187) && (ord($data[2]) != 191));
		}
		else {
			// retourne TRUE
			// si la chaine decoder et encoder est egale a elle meme
			return (utf8_encode(utf8_decode($data)) == $data);
		}
	}

	function check_cache( $id, $ean, $SEARCHDIST ) {
		if(!is_file($INFO->static_dir."img/cache/cache".$id.".jpg")) {
			$bin_thumbs = $SEARCHDIST->get_image($ean, true);
			$bin 		= $SEARCHDIST->get_image($ean, false);
				
			if(empty($bin) or empty($bin_thumbs)) return false;
				
			$this->make_imgjpg( $INFO->static_dir."img/cache/thumbs/", $bin_thumbs, "cache".$id );
			$this->make_imgjpg( $INFO->static_dir."img/cache/", $bin, "cache".$id );
		}

		return true;
	}

	function make_imgjpg( $dir, $bin, $name ) {
		if($file = fopen($dir.$name.".jpg", "wb+")) fwrite($file, $bin);
		fclose($file);
	}

	function return_prod_search( $ean, $titre, $auteur, $editeur, $collection, $prixttc, $dispo ) {
		$return = array();

		$return["ean"] 	  	  = $ean;
		$return["titre"][] 	  = htmlentities($titre, ENT_QUOTES | ENT_IGNORE, "ISO-8859-1");
		$return["auteur"][]	  = ucwords(strtolower(htmlentities($auteur, ENT_QUOTES | ENT_IGNORE, "ISO-8859-1")));
		$return["editeur"] 	  = ucwords(strtolower(htmlentities($editeur, ENT_QUOTES | ENT_IGNORE, "ISO-8859-1")));
		$return["collection"] = ucwords(strtolower(htmlentities($collection)));
		$return["prixttc"]    = $prixttc;
		/*if((int)$dispo !== 1) $return["dispo"] = 0;
		else $return["dispo"] = 1;*/
		$return["dispo"]	  = $dispo;

		return $return;
	}

	function return_prod( $ean, $titre = array(), $auteur = array(), $editeur, $collection, $parution , $dispo, $prixttc, $tva, $resume, $resume4couv, $support, $nbpage, $theme = array() ) {
		$return = array();

		$return["ean"] 	  	   = $ean;
		foreach($titre as $eltvalue) {
			$return["titre"][] = htmlentities($eltvalue);
		}
		foreach($auteur as $eltvalue) {
			$return["auteur"][] = ucwords(strtolower(htmlentities($eltvalue)));
		}
		$return["editeur"] 	   = ucwords(strtolower(htmlentities($editeur)));
		$return["collection"]  = ucfirst(strtolower(htmlentities($collection)));
		$return["parution"]    = htmlentities($parution);
		/*if((int)$dispo !== 1) $return["dispo"] = 0;
		else $return["dispo"] = 1;*/
		$return["dispo"]	   = $dispo;
		$return["prixttc"]     = $prixttc;
		$return["tva"]         = $tva;

		$return["resume"]      = $resume;

		$return["support"]     = htmlentities($support);
		$return["nbpage"]      = $nbpage;
		foreach($theme as $eltvalue) {
			$return["theme"][] = htmlentities($eltvalue);
		}

		return $return;
	}

	function up_case( $data ) {
		$LATIN1_UC_CHARS = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÖØÙÚÛÜİ";
		$LATIN1_LC_CHARS = "àáâãäåæçèéêëìíîïğñòóôõöøùúûüı";

		$data = strtoupper(strtr($data, $LATIN1_LC_CHARS, $LATIN1_UC_CHARS));
		return $data;
	}

	function low_case( $data ) {
		$LATIN1_UC_CHARS = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÖØÙÚÛÜİ";
		$LATIN1_LC_CHARS = "àáâãäåæçèéêëìíîïğñòóôõöøùúûüı";

		$data = strtolower(strtr($data, $LATIN1_UC_CHARS, $LATIN1_LC_CHARS));
		return $data;
	}

	function make_pagelink($all, $s, $nbr, $href, $onclick = "", $links = false) {
		/*
		$all : nombre de résulats
		$s : début
		$nbr : nombre de resultats par page
		$action : page a afficher
		*/
		
		global $INFO;
		
		## PARAMETRES
		$return   = "";
		$extended = false;
		$op 	  = 2;
		//$query  = $_SERVER["QUERY_STRING"];
		if(empty($s)) $s = 1;
		
		## NOMBRE DE PAGES
		$nbpage = floor($all / $nbr);
		if(($nbr * $nbpage) < $all) $nbpage += 1;
		
		if($nbpage > 1) {
			$return .= "<div class=\"pagelink\">";
			//$return .= "<span>Page(s): </span>";
			$return .= "<span class=\"block float-left\">Page(s) : ";
			for($i = 1; $i <= $nbpage; $i++) {
				## Lien page
				$rhref 	  = str_replace("<page>", $i, $href);
				$ronclick = str_replace("<page>", $i, $onclick);
				
				if(($i >= $s && $i < ($s + $op + 1)) or ($i < $s && $i >= ($s - $op)) or $i == $nbpage or $i == 1) {
					if((($i <= $nbpage && $i > $s) or ($i <= $nbpage && $i >= ($s - ($op -1))) or ($i < $op)) && $i > 1 && $extended === false) $return .= "-";
					if($i == $s) $return .= "<span class=\"select\">".$i."</span>";
					else {
						$return .= "<a href=\"".$rhref."\"";
						if(!empty($onclick)) $return .= " onclick=\"".$ronclick."\"";
						$return .= ">".$i."</a>";
					}
					
					if($i == 1 && ($s - $op) > ($i + 1)) $return .= "...";
				}
				elseif($i > ($s + $op) && $nbpage > ($s + $op) && $extended === false) {
					$return .= "...";
					$extended = true;
				}
			}
			
			$return .= "</span>";
			
			## SUIVANT | PRECEDENT
			if($links) {
				$phref 	  = str_replace("<page>", ($s - 1), $href);
				$nhref 	  = str_replace("<page>", ($s + 1), $href);
				$return .= "<span class=\"block float-right\">\n";
					if($s > 1) $return .= "<a href=\"".$phref."\" alt=\"Précédent\">&lsaquo;&nbsp;Précédent</a>";
					if($s > 1 && $s < $nbpage) $return .= "&nbsp;|&nbsp;";
					if($s < $nbpage) $return .= "<a href=\"".$nhref."\" alt=\"Suivant\">Suivant&nbsp;&rsaquo;</a>";
				$return .= "</span>\n";
			}
			
			$return .= "</div>";
		}
		
		return $return;
	}

	function make_link( $data ) {
		$data = str_replace("/", "-", $data);
		$data = str_replace(",", "", $data);
		$data = str_replace("(", "", $data);
		$data = str_replace(")", "", $data);
		
		return $data;
	}

	function make_html_link( $data ) {
		$LATIN1_UC_CHARS = "ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÖØÙÚÛÜİàáâãäåçèéêëìíîïğñòóôõöøùúûüı";
		$LATIN1_LC_CHARS = "aaaaaaceeeeiiiidnoooooouuuuyaaaaaaceeeeiiiidnoooooouuuuy";
		
		$data = strtolower(strtr(html_entity_decode(urldecode($data)), $LATIN1_UC_CHARS, $LATIN1_LC_CHARS));
		
		$data = str_replace("!", "", $data);
		$data = str_replace(":", "", $data);
		$data = str_replace(";", "", $data);

		$data = str_replace('&', '-et-', $data);
		$data = trim(preg_replace('/[^\w\d_ -]/si', '', $data));//remove all illegal chars
		$data = trim($data);
		$data = str_replace(' ', '-', $data);
		$data = str_replace('--', '-', $data);

		return urlencode($data);
	}

	function return_page_html( $url, $id ) {
		return "pages/".$url."-".$id.".html";
	}

	function check_prod( $RESULT ) {
		if(empty($RESULT) or empty($RESULT["ean"])) return false;
		if((int)$RESULT["prod_validate"] !== 1) return false;
		if((int)$RESULT["prod_ban"] == 1) return false;
		if((int)$RESULT["prod_deleted"] == 1) return false;

		return true;
	}

	function check_dispo( $codeDispo, $stock ) {
		global $INFO;
		
		// DELAIS MOYEN DE COMMANDE / LIVRAISON
		$delai_livr = 7;

		if((int)$stock > 0) {
			$out = "Disponible (En stock)";
			
			// ALERTE FIN DE STOCK
			if(!in_array($codeDispo, $INFO->CONFIG["validCDispo"])) $out .= "&nbsp;".$this->fstock();
			
			return $out;
		}
		
		if(isset($INFO->DISPO[$codeDispo])) {
			return $INFO->DISPO[$codeDispo];
		}
		else {
			return "Indisponible";
		}

	}
	
	function fstock( $type = "" ) {
		$out = "";
		
		//$out .= "<span class=\"fstock\"><img src=\"".STATIC_HOST."img/frontoffice/fstock.png\" alt=\"Fin de stock\" onmouseover=\"montre('<b>";
		//$out .= "<span class=\"fstock\"><img alt=\"Fin de stock\" onmouseover=\"montre('<b>";
		//switch($type) {
		//	case 1:
		//		$out .= "Cet article est momentanément indisponible chez notre fournisseur.";
		//		break;
		//	case 2:
		//		$out .= "Vous êtes sur le point d\'acquérir le(s) dernier(s) exemplaire(s) disponible(s) dans notre stock.";
		//		break;
		//	default:
		//		$out .= "Cet article est momentanément indisponible chez notre fournisseur.<br />Vous êtes sur le point d\'acquérir le(s) dernier(s) exemplaire(s) disponible(s) dans notre stock.";
		//		break;
		//}
		//$out .= "</b>');\" onmouseout=\"cache();\" /></span>";	
		$out .= "<span class=\"fstock\">";
		return $out;
	}

	function select_remise( $prixttc = 0, $tva = 0, $rub_1_id = 0, $rub_2_id = 0, $occasion = 0, $REMISES = array(), $prod_promo = 0, $prixpromo = 0 ) {
		$out = array();

		## INITIALISATION
		$tva = $this->format_decimal($tva);
		$prixttc = $this->format_decimal($prixttc);

		$occasion   = (int)$occasion;
		$prod_promo = (int)$prod_promo;

		$out["remise"]  = 0;
		$out["display"] = "".$this->format_number($prixttc)."&euro;";

		//if(($prixttc > 0 && ($tva == 5.5 or $tva == 2.1) && $occasion <= 0) && (in_array($rub_1_id, $REMISES) or in_array($rub_2_id, $REMISES)) && $prod_promo <= 0)
		if(($prixttc > 0 && $occasion <= 0) && (in_array($rub_1_id, $REMISES) or in_array($rub_2_id, $REMISES)) && $prod_promo <= 0) {
			if(!empty($_SESSION["cli_collectivite"]) && (int)$_SESSION["cli_collectivite"] == 1) {
				$prixremise = $this->return_remise( $prixttc, 9 );
					
				$out["remise"]  = 9;
				$out["display"] = "<img class=\"noborder\" src=\"".STATIC_HOST."img/frontoffice/remise9.png\" alt=\"Remise 9 pourcent\" title=\"Remise 9 pourcent\" />&nbsp;<span class=\"barre\">".$this->format_number($prixttc)."&euro;</span>&nbsp;".$prixremise."&euro;";
			}
			else {
				$prixremise = $this->return_remise( $prixttc, 5 );
					
				$out["remise"]  = 5;
				$out["display"] = "<img class=\"noborder\" src=\"".STATIC_HOST."img/frontoffice/remise5.png\" alt=\"Remise 5 pourcent\" title=\"Remise 5 pourcent\" />&nbsp;<span class=\"barre\">".$this->format_number($prixttc)."&euro;</span>&nbsp;".$prixremise."&euro;";
			}
		}
		//elseif($prod_promo == 1 && $prixttc > 0 && $prixpromo > 0 && $tva != 5.5)
		elseif($prod_promo == 1 && $prixttc > 0 && $prixpromo > 0) {
			$out["remise"]  = 0;
			$out["display"] = "<span class=\"barre\">".$this->format_number($prixttc)."&euro;</span>&nbsp;".$this->format_number($prixpromo)."&euro;";
		}

		return $out;
	}

	function return_remise( $prixttc, $remise, $recal = false, $prod_promo = 0, $prixpromo = 0 ) {
		$prod_promo = (int)$prod_promo;
		$prixremise = $prixttc;

		if($remise > 0 && $prod_promo <= 0) {
			## REVERIFICATION DE LA REMISE
			if($recal) {
				if(!empty($_SESSION["cli_collectivite"]) && (int)$_SESSION["cli_collectivite"] == 1) $remise = 9;
				else $remise = 5;
			}
				
			$remise     = round((($prixttc * $remise) / 100), 2);
			$prixremise = $prixttc - $remise;
		}
		elseif($prod_promo == 1 && $prixpromo > 0) {
			$prixremise = $prixpromo;
		}

		return $this->format_number($prixremise);
	}

	/**
	 * Retourne le montant des frais de port
	 *
	 * @param bool $usebar Utilisation de barème
	 * @param decimal $addttc Supplément au barème
	 * @param decimal $stotal Total TTC de la commande
	 * @return decimal Montant des frais de port
	 */
/*	function return_fp( $usebar = 0, $addttc = 0, $stotal = 0, $poids = 0 ) {
		global $INFO;

		$return   = 0;
		$bareme   = array();
		$maxvalue = 0;
		
		if(empty($INFO->expd_mod) or $INFO->expd_mod == "montant") $value = $stotal;
		else $value = $poids;
		
		if($stotal > 0) {
			## BAREME DE BASE
			if($usebar == 1) {
				## STATUT CONNEXION MYSQL
				$op_mysql = $INFO->op_mysql;

				## Connexion MySQL
				if(!$op_mysql) {
					if(!$INFO->Connect()) $INFO->print_error();
				}

				## SELECTION DU BAREME
				if(!$INFO->Query("SELECT expd_bar_level, expd_bar_start, expd_bar_stop, expd_bar_value FROM expedition_bareme ORDER BY expd_bar_level ASC", 9)) $INFO->print_error();
				while($ROW = $INFO->Result(9)) {
					$bareme[$ROW["expd_bar_level"]]["start"] = $ROW["expd_bar_start"];
					$bareme[$ROW["expd_bar_level"]]["stop"]  = $ROW["expd_bar_stop"];
					$bareme[$ROW["expd_bar_level"]]["value"] = $ROW["expd_bar_value"];
					
					// POIDS MAX
					if($ROW["expd_bar_stop"] > $maxvalue) $maxvalue = $ROW["expd_bar_stop"];
				}

				## Déconnexion MySQL
				if(!$op_mysql) $INFO->Close();

				$NB = count($bareme);
				
				// VALEUR MAX - NOMBRE DE COLIS
				$nbcolis	  = 1;
				$initialvalue = $value;
				if($bareme[$NB]["stop"] != "0" && $value > $maxvalue) $nbcolis = ceil($value / $maxvalue);
				
				for($k = 1; $k <= $nbcolis; $k++) {
					// VALEUR A PRENDRE EN COMPTE
					if($nbcolis > 1) {
						if($k == $nbcolis) $value = $initialvalue - ($maxvalue * ($nbcolis - 1));
						else $value = $maxvalue;
					}
					
					for($i = 1; $i <= $NB; $i++) {
						if($i == $NB) {
							if($value > $bareme[$i]["start"]) $return += $this->format_decimal($bareme[$i]["value"]);
						}
						elseif($value <= $bareme[$i]["stop"] && $value > $bareme[$i]["start"]) $return += $this->format_decimal($bareme[$i]["value"]);
					}
	
					$return += $addttc;
				}
			}
			else {
				$return = $addttc;
			}
		}
		//echo($usebar."-".$addttc."-".$stotal."-".$poids."<br>");
		return $return;
	}*/
	function return_fp( $usebar = 0, $addttc = 0, $stotal = 0, $poids = 0, $zone = "", $group = "" ) {
		global $INFO;

		$return   = 0;
		$bareme   = array();
		$maxvalue = 0;
		
		if(empty($INFO->expd_mod) or $INFO->expd_mod == "montant") $value = $stotal;
		else $value = $poids;
		
		if($stotal > 0) {
			## BAREME DE BASE
			if($usebar == 1) {
				## STATUT CONNEXION MYSQL
				$op_mysql = $INFO->op_mysql;

				## Connexion MySQL
				if(!$op_mysql) {
					if(!$INFO->Connect()) $INFO->print_error();
				}

				## SELECTION DU BAREME
				if(!$INFO->Query("SELECT 
										expd_bar_level, 
										expd_bar_start, 
										expd_bar_stop, 
										expd_bar_value, 
										expd_bar_value_DOM, 
										expd_bar_value_TOM, 
										expd_bar_value_ZA, 
										expd_bar_value_ZB, 
										expd_bar_value_ZC, 
										expd_bar_value_ZD
										FROM 
										expedition_bareme 
										WHERE 
										expd_bar_group = \"".$group."\" 
										ORDER BY 
										expd_bar_level ASC", 9)) $INFO->print_error();
				$i = 1;
				while($ROW = $INFO->Result(9)) {
					$bareme[$i]["start"] = $ROW["expd_bar_start"];
					$bareme[$i]["stop"]  = $ROW["expd_bar_stop"];
					
					// INTERNATIONNAL
					switch($zone) {
						case "FM":
							$bareme[$i]["value"] = $ROW["expd_bar_value"];
							break;
						case "DOM":
							$bareme[$i]["value"] = $ROW["expd_bar_value_DOM"];
							break;
						case "TOM":
							$bareme[$i]["value"] = $ROW["expd_bar_value_TOM"];
							break;
						case "A":
							$bareme[$i]["value"] = $ROW["expd_bar_value_ZA"];
							break;
						case "B":
							$bareme[$i]["value"] = $ROW["expd_bar_value_ZB"];
							break;
						case "C":
							$bareme[$i]["value"] = $ROW["expd_bar_value_ZC"];
							break;
						case "D":
						default:
							$bareme[$i]["value"] = $ROW["expd_bar_value_ZD"];
							break;
					}
					
					// POIDS MAX
					if($ROW["expd_bar_stop"] > $maxvalue) $maxvalue = $ROW["expd_bar_stop"];
					
					$i++;
				}

				## Déconnexion MySQL
				if(!$op_mysql) $INFO->Close();

				$NB = count($bareme);
				
				// VALEUR MAX - NOMBRE DE COLIS
				$nbcolis	  = 1;
				$initialvalue = $value;
				if($bareme[$NB]["stop"] != "0" && $value > $maxvalue) $nbcolis = ceil($value / $maxvalue);
				
				for($k = 1; $k <= $nbcolis; $k++) {
					// VALEUR A PRENDRE EN COMPTE
					if($nbcolis > 1) {
						if($k == $nbcolis) $value = $initialvalue - ($maxvalue * ($nbcolis - 1));
						else $value = $maxvalue;
					}
					
					for($i = 1; $i <= $NB; $i++) {
						if($i == $NB) {
							if($value > $bareme[$i]["start"]) $return += $this->format_decimal($bareme[$i]["value"]);
						}
						elseif($value <= $bareme[$i]["stop"] && $value > $bareme[$i]["start"]) $return += $this->format_decimal($bareme[$i]["value"]);
					}
	
					$return += $addttc;
				}
			}
			else {
				$return = $addttc;
			}
		}
		//echo($usebar."-".$addttc."-".$stotal."-".$poids."<br>");
		return $return;
	}

	/**
	 * Sélection dynamique de 18 mots clés pour le meta tag keyword en fonction de leur importance
	 *
	 * @param array $data Données diverses (Sous titre, Editeur, Résumé par exemple)
	 * @param string $titre Titre du produit
	 * @param string $auteur Auteur du produit
	 * @return string 18 Mots clés séparés par une virgule
	 */
	function tags( $data = array(), $titre = array(), $auteur = array() ) {
		set_time_limit(0);

		$tags  = "";
		$tag   = array();
		$texte = implode(" ", $data);

		$i = 0;
		## Ajout du titre dans les tags
		while(list($key, $val) = each($titre)) {
			if(!empty($val)) {
				$tag[$i] = str_replace(",", " ", $val);
				$i++;
			}
		}

		## Ajout de l'auteur dans les tags
		while(list($key, $val) = each($auteur)) {
			if(!empty($val)) {
				$tag[$i] = str_replace(",", " ", $val);
				$i++;
			}
		}

		$tags .= implode(", ", $tag);

		$taille_des_mots = 15; // taille des mots en nombre de lettre
		$nbre_elements = 18;  // nombre d'elements

		## STOP WORD
		// C'est ici que vous mettez les mots que vous ne voulez pas !
		$tab_banni =
		array(
		"mais","ou","et","donc","or","ni","car",
		"je","il","lui","ils","elle","elles","nous","vous",
		"vos","votre","mes","mien","mien","tien","tiens",
		"tout","toute","toutes",
		"a","b","c","d","e","f","g","h","i","j","l","m","n","o","p","q",
		"r","s","t","u","v","w","x","y","z",
		"le","la","les","nos",
		"alors","au","aucuns","aussi","autre","avant","avec","avoir","bon","car","ce",
		"cela","ces","ceux","chaque","ci","comme","comment","dans","des","du","dedans",
		"dehors","depuis","deux","devrait","doit","donc","dos","droite","début","elle",
		"elles","en","encore","essai","est","et","eu","fait","faites","fois","font",
		"force","haut","hors","ici","il","ils","je juste","la","le","les","leur","là",
		"ma","maintenant","mais","mes","mine","moins","mon","mot","même","ni","nommés",
		"notre","nous","nouveaux","ou","où","par","parce","parole","pas","personnes",
		"peut","peu","pièce","plupart","pour","pourquoi","quand","que","quel","quelle",
		"quelles","quels","qui","sa","sans","ses","seulement","si","sien","son",
		"sont","sous","soyez sujet","sur","ta","tandis","tellement","tels","tes","ton",
		"tous","tout","trop","très","tu","valeur","voie","voient","vont","votre","vous",
		"vu","ça","étaient","état","étions","été","être",
		"un","deux","trois","quatre","cinq","six","sept","huit","neuf","dix",
		"0","1","2","3","4","5","6","7","8","9","10",
		"avec","chez","par","dans","des","en","de","une","votre","meilleurs","entre",
		"entres","depuis","alors","ne","pas","du","meme",
		"ou","nom","seuls","acceptes","ayant",
		"vos","votre","mes","mien","mien","tien","tiens","tout","toute","toutes",
		"que","quoi","qui","comment","peu","peut","pis","puis","pas",
		"chaque","chacun","chacune",
		"son","ses","au","aux","se","sur","ce","ceux","cette","ca","ci","ceci","cela",
		"aussi","pour","petit","grand","moyen","large","haut","bas","milieu","droite",
		"gauche","centre","dit","etre","leur","leurs","plus","moin","moins",
		"es","est","sont","son","va","suis","ai","viens");

		## Suppression des caractères spéciaux
		$texte = strip_tags(urldecode($texte));
		$texte = html_entity_decode($texte);

		## Suppression des slashs
		$texte = get_magic_quotes_gpc() == 1 ? stripslashes($texte) : $texte;

		## Suppression des accents
		$accent   = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËéèêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
		$noaccent = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
		$texte 	  = strtr($texte, $accent, $noaccent);

		## Suppression des apostrophes
		$texte = str_replace("'", " ", $texte);
		## On passe le texte en minuscule
		$texte = strtolower($texte);
		## On place tous les mots d'une chaine dans un tableau
		//$regs1 = split("[^[:alpha:]]+", $texte);
		$regs1 = preg_split("#[^[:alpha:]]+#", $texte);
		//print_r($regs1);exit;
		$tab_copie = $regs1;

		## Traitement du tableau
		// On enleve les mots bannis en faisant le difference des 2 tableaux
		$regs = array_diff($regs1, $tab_banni);
		// On groupe les Mots-clés identiques et on les compte
		$stats = array_count_values($regs);
		// On trie par ordre croissant la liste
		array_multisort($stats, SORT_DESC);
		// On crée un tableau avec les clés du tableau correpondants aux mots clés
		$tabKey = array_keys($stats);

		## CREATION TABLEAU
		$i 		  = 0;
		$compteur = 0;
		$tag	  = array();
		while( $i < sizeof($tabKey)) {
			$champ  = $tabKey[$i];
			$taille = strlen($champ);
				
			if(($taille <= $taille_des_mots) && ($taille > 2)) {
				$tag[$compteur] = $tabKey[$i];
				$compteur++;
			}
			$i++;
				
			if($compteur == ($nbre_elements + 1)) break;
		}

		if(!empty($tags)) $tags .= ", ";
		$tags .= implode(", ", $tag);

		return $tags;
	}

	/**
	 * Génération d'une chaine de 14 caractères aléatoires
	 *
	 * @return string 14 Caractères Aléatoires
	 */
	function generate_code() {
		$code = "";
		$all  = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		srand((double)microtime()*1000000);
		$i = 0;
		while($i <= 14) {
			$num = rand() % 62;
			$tmp = substr($all, $num, 1);
			$code = $code.$tmp;
			$i++;
		}

		return $code;
	}
	
	function getMTime() {
		$mtime = microtime();
		$mtime = str_replace(" ", "", $mtime);
		$mtime = str_replace(".", "", $mtime);
		
		return "f".$mtime;
	}
	
	function get_sections_level( $RUB, $id ) {
		global $INFO;
		
		$level = array();
		
		if(!empty($RUB) && !empty($id)) {
			if(isset($RUB["RUB2"][$id])) {
				## URL CATEGORIES
				$level[2] = $RUB["RUB2"][$id];
				$level[1] = $RUB["RUB1"][$level[2]["assoc"]];
				$level[0] = $RUB["RUB0"][$level[1]["assoc"]];
				
				$level[2]["libelle"] = $level[2]["libelle"];
				$level[1]["libelle"] = $level[1]["libelle"];
				$level[0]["libelle"] = $level[0]["libelle"];
				
				$level[2]["id"] = $id;
				$level[1]["id"] = $level[2]["assoc"];
				$level[0]["id"] = $level[1]["assoc"];
				
				$level[2]["link"] = "thematique/";
				if($INFO->section_maxlevel == 2) $level[2]["link"] .= urlencode($level[0]["libelle"]);
				$level[2]["link"] .= "/".urlencode($level[1]["libelle"])."/".urlencode($level[2]["libelle"])."-".$level[2]["id"].".html";
				$level[1]["link"] = "thematique/";
				if($INFO->section_maxlevel == 2) $level[1]["link"] .= urlencode($level[0]["libelle"]);
				$level[1]["link"] .= "/sommaire-".urlencode($level[1]["libelle"])."-".$level[1]["id"].".html";
				$level[0]["link"] = "";
				
				// LEVEL 1
				if(in_array($level[1]["assoc"], $INFO->DISPLAY->mBlackList) or in_array($level[2]["assoc"], $INFO->DISPLAY->mBlackList)) {
					unset($level[1]);
				}
				
				// LEVEL 0
				if(in_array($id, $INFO->DISPLAY->mBlackList)) {
					unset($level[0]);
				}
			}
			elseif(isset($RUB["RUB1"][$id])) {
				## URL CATEGORIES
				$level[1] = $RUB["RUB1"][$id];
				$level[0] = $RUB["RUB0"][$level[1]["assoc"]];
				
				$level[1]["libelle"] = $level[1]["libelle"];
				$level[0]["libelle"] = $level[0]["libelle"];
				
				$level[1]["id"] = $id;
				$level[0]["id"] = $level[1]["assoc"];
				
				$level[1]["link"] = "thematique/";
				if($INFO->section_maxlevel == 2) $level[1]["link"] .= urlencode($level[0]["libelle"]);
				$level[1]["link"] .= "/sommaire-".urlencode($level[1]["libelle"])."-".$level[1]["id"].".html";
				$level[0]["link"] = "";
			}
			elseif(isset($RUB["RUB0"][$id])) {
				## URL CATEGORIES
				$level[0] = $RUB["RUB0"][$id];
				
				$level[0]["libelle"] = $level[0]["libelle"];
				
				$level[0]["id"] = $id;
				
				$level[0]["link"] = "thematique/sommaire-".urlencode($this->make_html_link($level[0]["libelle"]))."-".$level[0]["id"].".html";
			}
		}
		
		return $level;
	}
	
	/**
	 * Génère un nom de page HTML vers une fiche produit contenant la rubrique, le titre et l'auteur du produit (URL Rewriting)
	 *
	 * @param array $RUB Tableau contenant l'ensemble des rubriques
	 * @param int $rub_id ID de la rubrique en cours de navigation
	 * @param int $rub_1_id ID de la rubrique 1 du produit
	 * @param int $rub_2_id ID de la rubrique 2 du produit
	 * @param string $auteur Nom de l'auteur
	 * @param string $titre Titre du produit
	 * @param string $ean Code EAN13
	 * @return string Nom de la page HTML
	 */
	function link_to_product( $RUB = array(), $rub_id = 0, $rub_1_id = 0, $rub_2_id = 0, $auteur = "", $titre = "", $ean = "" ) {
		global $INFO;
		
		$link = "produits/fiche/";
		$id	  = "";
		$i	  = 0;

		if(!empty($RUB)) {
			if(!empty($rub_id) && $rub_id !== "no") $id = $rub_id;
			elseif(!empty($rub_1_id)) $id = $rub_1_id;
			elseif(!empty($rub_2_id)) $id = $rub_2_id;
			
			if(!empty($id))
			{
				$level = $this->get_sections_level($RUB, $id);
				
				$link = "";
				if($INFO->section_maxlevel == 2) $link .= urlencode($this->make_html_link($level[0]["libelle"]))."/";
				if(!empty($level)) {
					if(!empty($level[1]["libelle"])) $link .= urlencode($this->make_html_link($level[1]["libelle"]))."/";
					if(!empty($level[2]["libelle"])) $link .= urlencode($this->make_html_link($level[2]["libelle"]))."/";
				}
			}
		}
		
		if(!empty($auteur)) {
			if(is_array($auteur)) $auteur = implode("-", $auteur);
			$link .= urlencode($this->make_html_link($this->low_case($auteur)))."-";
			$i++;
		}
		if(!empty($titre)) {
			if(is_array($titre)) $titre = implode("-", $titre);
			$link .= urlencode($this->make_html_link($this->low_case($titre)))."-";
			$i++;
		}
		if(!empty($ean)) {
			$link .= $ean."-";
			$i++;
		}
		if($i == 0) $link .= "-";
		$link .= "v.html";

		return $link;
	}

	/**
	 * Envoi un mail afin d'avertir les personnes en liste de recherche de la rentrée en stock d'un produit
	 *
	 * @param string $ean Code EAN
	 * @return false Retourne uniquement false si pb pendant l'envoi
	 */
	function mail_listes_recherche( $ean ) {
		global $INFO;

		require_once("./class/class_mailer.php");
		$MAIL = new mailer;

		## STATUT CONNEXION MYSQL
		$op_mysql = $INFO->op_mysql;

		## Connexion MySQL
		if(!$op_mysql) {
			if(!$INFO->Connect()) $INFO->print_error();
		}

		if(!$INFO->Query("SELECT a.list_rech_ean, a.list_rech_titre, a.cli_id, b.cli_civ, b.cli_nom, b.cli_prenom, b.cli_mail FROM listes_recherche AS a LEFT JOIN clients AS b USING(cli_id) WHERE a.list_rech_ean = \"".$ean."\" AND a.list_rech_msend = \"0\" GROUP BY b.cli_mail")) return false;
		while($ROW = $INFO->Result()) {
			## Envoi du mail
			$mail_datas = array(
							"cli_civ" => $INFO->FUNC->civilite(urldecode($ROW["cli_civ"])), 
							"cli_nom" => urldecode($ROW["cli_nom"]), 
							"cli_prenom" => urldecode($ROW["cli_prenom"]), 
							"cli_mail" => urldecode($ROW["cli_mail"]), 
							"prod_titre" => urldecode($ROW["list_rech_titre"]), 
							"prod_ean" => urldecode($ROW["list_rech_ean"]), 
			);
				
			$MAIL->send_mail("liste_recherche", $mail_datas);
				
			if(!$INFO->Query("UPDATE listes_recherche SET list_rech_msend = \"1\" WHERE cli_id = \"".$ROW["cli_id"]."\" AND list_rech_ean = \"".$ROW["list_rech_ean"]."\"", 2)) return false;
		}

		## Déconnexion MySQL
		if(!$op_mysql) $INFO->Close();
	}
	
	function load_remises() {
		global $INFO;
		
		if(empty($INFO->REMISES)) {
			## Connexion MySQL
			if(!$INFO->Connect()) $INFO->print_error();
			
			## SELECTION DES RUBRIQUES
			if(!$INFO->Query("SELECT rub_id FROM rubriques WHERE rub_remise = \"1\"")) $INFO->print_error();
			while($ROW = $INFO->Result()) {
				$INFO->REMISES[] = $ROW["rub_id"];
			}
			
			## Déconnexion MySQL
			$INFO->Close();
		}
	}
	
	function load_dispo() {
		global $INFO;
		
		if(empty($INFO->DISPO)) {
			// Connexion MySQL
			if(!$INFO->Connect()) $INFO->print_error();
			
			// SELECTION DES RUBRIQUES
			if(!$INFO->Query("SELECT 
								dispo_id, 
								dispo_libelle, 
								dispo_allowCmd 
								FROM 
								disponibilite")) $INFO->print_error();
			while($ROW = $INFO->Result()) {
				$INFO->DISPO[$ROW["dispo_id"]] = urldecode($ROW["dispo_libelle"]);
				if($ROW["dispo_allowCmd"] == "1") {
					if(!isset($INFO->CONFIG["validCDispo"])) {
						$INFO->CONFIG["validCDispo"] = array();
					}
					$INFO->CONFIG["validCDispo"][] = $ROW["dispo_id"];
				}
			}
			
			// Déconnexion MySQL
			$INFO->Close();
		}
	}
	
	function rewrite_summary($text, $rwsize = "", $rwfont = "", $rwcolor = true, $offset = "") {
		$start   = 0;
		$startff = 0;
		$end     = 0;
		$fface   = false;
		$replace = "";
		
		// FONT STYLE
		if(empty($offset)) $start = strpos(strtolower($text), "<font style=\"");
		else $start = strpos(strtolower($text), "<font style=\"", $offset);
		
		// FONT FACE
		if(empty($offset)) $startff = strpos(strtolower($text), "<font face=\"");
		else $startff = strpos(strtolower($text), "<font face=\"", $offset);
		if($startff === false){
		}
		elseif($start === false or $startff < $start) {
			$fface = true;
			$start = $startff;
		}
		
		if($start === false) {
			return $text;
		}
		else {
			if($fface) $start += 12;
			else $start += 13;
			$end = strpos($text, "\">", $start);
			
			if($end === false){
			}
			else {
				$color = "";
				
				if(!$fface) {
					$data = explode(";", substr($text, $start, ($end - $start)));
					foreach($data as $element) {
						if(strpos($element, "color:") === false){
						}
						elseif(!$rwcolor) $color = $element.";";
					}
					
					// TEXTE DE REMPLACEMENT
					if(empty($rwsize) && empty($rwfont)) $replace = $color;
					else $replace = "Font: ".$rwsize." ".$rwfont.";".$color;
					
					$text = substr_replace($text, $replace, $start, ($end - $start));
				}
				else {
					// TEXTE DE REMPLACEMENT
					$replace = $rwfont;
					$text = substr_replace($text, $replace, $start, ($end - $start));
				}
				
				return $this->rewrite_summary($text, $rwsize, $rwfont, $rwcolor, $end - ($end - $start) + strlen($replace));
			}
		}
	}
	

}
?>