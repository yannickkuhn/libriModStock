<?php
class notice {
	function add( $data ) {
		global $AUTH, $XML, $INFO;
		
		/*
		// RETOUR //
		-1 : Erreur
		 1 : La notice a été mise à jour
		 
		 // <RESTORE>
		 0/false : Conserver la notice existante
		 1/true : Ecraser la notice existante
		*/
		
		// Lecture des données XML
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password)) return $AUTH->error;
		
		// Connexion MySQL
		if(!$INFO->Connect()) return $INFO->erreur;
		
		// Ecrasement de l'anicenne notice
		if(!empty($XML->data["RESTORE"])) {
			if(!$INFO->Query("DELETE 
									FROM 
									produits 
									WHERE 
									prod_ean = '".urlencode($XML->data["EAN"])."'")) return $INFO->erreur;
			
			// Création de la notice
			if(!$this->insertdatas()) return $INFO->erreur;
			else return $INFO->returnxml( "1" );
		}
		
		// Recherche de la rubrique
		if(!$INFO->Query("SELECT rub_id FROM rubriques WHERE rub_libri_id = '".$XML->data["THEME_PER"]["attributs_xml"]["id"]."'")) return $INFO->erreur;
		$ROW = $INFO->Result();
		if(!empty($XML->data["THEME_PER"]["attributs_xml"]["id"]) && !empty($ROW["rub_id"])) $XML->data["THEME_PER"]["attributs_xml"]["id"] = $ROW["rub_id"];
		else $XML->data["THEME_PER"]["attributs_xml"]["id"] = "";
		
		// Recherche de la notice
		// [HACK] SI OCCASION, SEUL LIBRIWEB TRAITE LA NOTICE
		if((int)$XML->data["OCCASION"] == 1) return $INFO->returnxml( "1" );
		
		if(!$INFO->Query("SELECT prod_ean FROM produits WHERE prod_ean = '".$XML->data["EAN"]."'")) return $INFO->erreur;
		$nb = $INFO->Num();
		
		// Déconnexion MySQL
		$INFO->Close();
		
		// La notice existe déjà, on la met à jour
		if($nb > 0) {
			if(!$this->editdatas()) return $INFO->erreur;
			else return $INFO->returnxml( "1" );
		}
		// La notice n'existe pas, on la crée
		else {
			if(!$this->insertdatas()) return $INFO->erreur;
			else return $INFO->returnxml( "1" );
		}
	}

	function insertdatas() {
		global $XML, $INFO;
		
		$wrong_dispo = array("3", "4", "6", "7");
		
		// Initialisation
		$this->initxml();
		
		// Connexion MySQL
		$INFO->Connect();
		
		// Controle des champs
		if(!$INFO->FUNC->control_var_xml( $XML->data, array( "EAN", "TITRE", "PRIXTTC" ) )) {
			$INFO->erreur = $INFO->returnxml( "-1", "DONNEES: Les champs 'EAN', 'TITRE' et 'PRIXTTC' doivent être renseignés" );
			return false;
		}
		
		// On charge le mode d'ajout
		$prod_validate = 1;
		
		if(!in_array($iddispo, $wrong_dispo)) {
			if(!$INFO->Query("INSERT INTO produits (prod_ean, rub_1_id, prod_etat_id, prod_prixht, prod_prixttc, prod_tva1, 
									prod_tva2, prod_titre, prod_auteurs, prod_editeur, prod_collection, prod_parution, prod_support, 
									prod_poids, prod_epaisseur, prod_largeur, prod_hauteur, prod_dispo, 
									prod_validate, prod_ban, prod_deleted, prod_date_ajout, prod_heure_ajout) 
									VALUES('".urlencode($XML->data["EAN"])."', '".$XML->data["THEME_PER"]["attributs_xml"]["id"]."', '1', 
									'".$INFO->FUNC->format_decimal($XML->data["PRIXHT"])."', '".$INFO->FUNC->format_decimal($XML->data["PRIXTTC"])."', 
									'".$INFO->FUNC->format_decimal($XML->data["TVA1"]["valeur_xml"])."', 
									'".$INFO->FUNC->format_decimal($XML->data["TVA2"]["valeur_xml"])."', '".$XML->data["TITRE"]."', 
									'".$XML->data["AUTEUR"]."', '".$XML->data["EDITEUR"]["valeur_xml"]."', '".$XML->data["COLLECTION"]["valeur_xml"]."', 
									'".$INFO->FUNC->simpledateen($XML->data["DATEPAR"])."', '".$XML->data["SUPPORT"]["valeur_xml"]."', 
									'".$XML->data["POIDS"]."', '".$XML->data["EPAISSEUR"]."', '".$XML->data["LARGEUR"]."', '".$XML->data["HAUTEUR"]."', 
									'".$XML->data["IDDISPO"]."', '".$prod_validate."', '0', '0', '".date("Y-m-d")."', '".date("H:i:s")."')")) return false;
		}
		
		// Déconnexion MySQL
		$INFO->Close();
		
		// Mise à jour du stock
		if(!empty($XML->data["STOCK"])) $this->upstock();
		
		return true;
	}
	
	function editdatas() {
		global $XML, $INFO;
		
		// Initialisation
		$this->initxml();
		
		// Connexion MySQL
		$INFO->Connect();
		
		// Controle des champs
		if(!$INFO->FUNC->control_var_xml( $XML->data, array( "EAN", "TITRE", "PRIXTTC" ) )) {
			$INFO->erreur = $INFO->returnxml( "-1", "DONNEES: Les champs \"EAN\", \"TITRE\" et \"PRIXTTC\" doivent être renseignés" );
			return false;
		}
		
		// On charge les rubriques
		$RUB = array();
		if(!$INFO->Query("SELECT rub_id, rub_libri_id FROM rubriques")) return false;
		while($ROW = $INFO->Result()) {
			if(!empty($ROW["rub_libri_id"])) $RUB[$ROW["rub_libri_id"]] = $ROW["rub_id"];
		}
		
		// RUBRIQUE
		$thm = "";
		if(!empty($RUB[$XML->data["THEME_PER"]["attributs_xml"]["id"]])) $thm = "rub_1_id = '".$RUB[$XML->data["THEME_PER"]["attributs_xml"]["id"]]."', ";
		
		if(!$INFO->Query("UPDATE produits SET ".$thm."
								prod_etat_id = '1', prod_prixht = '".$INFO->FUNC->format_decimal($XML->data["PRIXHT"])."', 
								prod_prixttc = '".$INFO->FUNC->format_decimal($XML->data["PRIXTTC"])."', 
								prod_tva1 = '".$INFO->FUNC->format_decimal($XML->data["TVA1"]["valeur_xml"])."', prod_tva2 = '".$INFO->FUNC->format_decimal($XML->data["TVA1"]["valeur_xml"])."', 
								prod_parution = '".$INFO->FUNC->simpledateen($XML->data["DATEPAR"])."', 
								prod_dispo = '".$XML->data["IDDISPO"]."', 
								prod_deleted = '0', prod_date_mod = '".date("Y-m-d")."', prod_heure_mod = '".date("H:i:s")."' 
								WHERE prod_ean = '".urlencode($XML->data["EAN"])."'")) return false;
		
		// MAJ RUBRIQUE
		if(!empty($RUB[$XML->data["THEME_PER"]["attributs_xml"]["id"]])) {
			if(!$INFO->Query("UPDATE produit_selections SET rub_1_id = '".$RUB[$XML->data["THEME_PER"]["attributs_xml"]["id"]]."' WHERE prod_ean = '".$XML->data["EAN"]."'")) return false;
			if(!$INFO->Query("UPDATE coups_coeur SET rub_1_id = '".$RUB[$XML->data["THEME_PER"]["attributs_xml"]["id"]]."' WHERE prod_ean = '".$XML->data["EAN"]."'")) return false;
		}
		
		// Déconnexion MySQL
		$INFO->Close();
		
		// Mise à jour du stock
		if(!empty($XML->data["STOCK"])) $this->upstock();
		
		return true;
	}
	
	function initxml() {
		global $XML;
		
		if(!isset($XML->data["THEME_EDL"]["attributs_xml"]["id"])) $XML->data["THEME_EDL"]["attributs_xml"]["id"] = "";
		if(!isset($XML->data["THEME_PER"]["attributs_xml"]["id"])) $XML->data["THEME_PER"]["attributs_xml"]["id"] = "";
		if(!isset($XML->data["EAN"])) $XML->data["EAN"] = "";
		if(!isset($XML->data["ISBN"])) $XML->data["ISBN"] = "";
		if(!isset($XML->data["DEVISE"])) $XML->data["DEVISE"] = "";
		if(!isset($XML->data["PRIXTTC"])) $XML->data["PRIXTTC"] = "";
		if(!isset($XML->data["PRIXHT"])) $XML->data["PRIXHT"] = "";
		if(!isset($XML->data["TVA1"]["valeur_xml"])) $XML->data["TVA1"]["valeur_xml"] = "";
		if(!isset($XML->data["TVA1"]["attributs_xml"]["id"])) $XML->data["TVA1"]["attributs_xml"]["id"] = "";
		if(!isset($XML->data["TVA2"]["valeur_xml"])) $XML->data["TVA2"]["valeur_xml"] = "";
		if(!isset($XML->data["TVA2"]["attributs_xml"]["id"])) $XML->data["TVA2"]["attributs_xml"]["id"] = "";
		if(!isset($XML->data["DATEPAR"])) $XML->data["DATEPAR"] = "";
		if(!isset($XML->data["TITRE"])) $XML->data["TITRE"] = "";
		if(!isset($XML->data["EDITEUR"]["valeur_xml"])) $XML->data["EDITEUR"]["valeur_xml"] = "";
		if(!isset($XML->data["EDITEUR"]["attributs_xml"]["id"])) $XML->data["EDITEUR"]["attributs_xml"]["id"] = "";
		if(!isset($XML->data["COLLECTION"]["valeur_xml"])) $XML->data["COLLECTION"]["valeur_xml"] = "";
		if(!isset($XML->data["COLLECTION"]["attributs_xml"]["id"])) $XML->data["COLLECTION"]["attributs_xml"]["id"] = "";
		if(!isset($XML->data["AUTEUR"])) $XML->data["AUTEUR"] = "";
		if(!isset($XML->data["DISTRIBUTEUR"]["valeur_xml"])) $XML->data["DISTRIBUTEUR"]["valeur_xml"] = "";
		if(!isset($XML->data["DISTRIBUTEUR"]["attributs_xml"]["id"])) $XML->data["DISTRIBUTEUR"]["attributs_xml"]["id"] = "";
		if(!isset($XML->data["THEME_EDL"]["valeur_xml"])) $XML->data["THEME_EDL"]["valeur_xml"] = "";
		if(!isset($XML->data["THEME_PER"]["valeur_xml"])) $XML->data["THEME_PER"]["valeur_xml"] = "";
		if(!isset($XML->data["SUPPORT"]["valeur_xml"])) $XML->data["SUPPORT"]["valeur_xml"] = "";
		if(!isset($XML->data["SUPPORT"]["attributs_xml"]["id"])) $XML->data["SUPPORT"]["attributs_xml"]["id"] = "";
		if(!isset($XML->data["POIDS"])) $XML->data["POIDS"] = "";
		if(!isset($XML->data["EPAISSEUR"])) $XML->data["EPAISSEUR"] = "";
		if(!isset($XML->data["LARGEUR"])) $XML->data["LARGEUR"] = "";
		if(!isset($XML->data["HAUTEUR"])) $XML->data["HAUTEUR"] = "";
		if(!isset($XML->data["IDDISPO"])) $XML->data["IDDISPO"] = "";
	}
	
	function upstock() {
		global $INFO, $XML;
		
		// Connexion MySQL
		$INFO->Connect();
		
		// Mise à jour du stock
		if(!$INFO->Query("SELECT stock_ean FROM stock WHERE stock_ean = '".urlencode($XML->data["EAN"])."'")) return false;
		$nbr = $INFO->Num();
		if(empty($nbr)) {
			if(!$INFO->Query("INSERT INTO stock (stock_ean, stock_qte) VALUES('".urlencode($XML->data["EAN"])."', '".$XML->data["STOCK"]."')")) return false;
		}
		else {
			if(!$INFO->Query("UPDATE stock SET stock_qte = '".$XML->data["STOCK"]."' WHERE stock_ean = '".urlencode($XML->data["EAN"])."'")) return false;
		}
		
		// MAIL UTILISATEUR LISTE RECHERCHE
		$INFO->FUNC->mail_listes_recherche(urlencode($XML->data["EAN"]));
		
		// Déconnexion MySQL
		$INFO->Close();
	}
	
	function delete( $data ) {
		global $AUTH, $XML, $INFO;
		
		/*
		// RETOUR //
		-1 : Erreur
		 1 : La notice a été désactivée
		*/
		
		// Lecture des données XML
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password )) return $AUTH->error;
		
		// Connexion MySQL
		if(!$INFO->Connect()) return $INFO->erreur;
		
		// Désactivation de la notice
		if(!$INFO->Query("UPDATE produits SET prod_deleted = '1' WHERE prod_ean = '".urlencode($XML->data["EAN"])."'")) return $INFO->erreur;
		if(!$INFO->Query("DELETE FROM produit_associations WHERE prod_assoc_ean = '".urlencode($XML->data["EAN"])."'")) return $INFO->erreur;
		
		// Suppression des nouveautés
		if(!$INFO->Query("DELETE FROM produit_selections WHERE prod_ean = '".urlencode($XML->data["EAN"])."'")) return $INFO->erreur;
		
		// Suppression des coups de coeur
		if(!$INFO->Query("DELETE FROM coups_coeur WHERE prod_ean = '".urlencode($XML->data["EAN"])."'")) return $INFO->erreur;
		
		// Déconnexion MySQL
		$INFO->Close();
		
		// La notice est désactivée
		return $INFO->returnxml( "1" );
	}
	
	function add_favorite( $data ) {
		global $AUTH, $XML, $INFO;
		
		/*
		// RETOUR //
		-1 : Erreur
		 1 : La notice a été mise à jour
		*/
		
		// Lecture des données XML
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password)) return $AUTH->error;
		
		// Controle des champs
		if(!$INFO->FUNC->control_var_xml( $XML->data, array( "EAN", "TYPE" ) )) {
			$INFO->erreur = $INFO->returnxml( "-1", "DONNEES: Le champ \"EAN\" doit être renseigné" );
			return false;
		}
		
		if($XML->data["TYPE"] == "CP") $table = "coups_coeur";
		else $table = "produit_selections";
		
		// Connexion MySQL
		if(!$INFO->Connect()) return $INFO->erreur;
		
		// Recherche de la rubrique
		if(!$INFO->Query("SELECT rub_id FROM rubriques WHERE rub_libri_id = '".$XML->data["THEME_PER"]["attributs_xml"]["id"]."'")) return $INFO->erreur;
		$ROW = $INFO->Result();
		if(!empty($XML->data["THEME_PER"]["attributs_xml"]["id"]) && !empty($ROW["rub_id"])) $XML->data["THEME_PER"]["attributs_xml"]["id"] = $ROW["rub_id"];
		else $XML->data["THEME_PER"]["attributs_xml"]["id"] = "";
		
		if(!$INFO->Query("INSERT INTO ".$table." 
												(
												prod_ean, 
												rub_1_id
												) 
												VALUES(
												'".urlencode($XML->data["EAN"])."', 
												'".$XML->data["THEME_PER"]["attributs_xml"]["id"]."'
												)
												")) return false;
		
		// Déconnexion MySQL
		$INFO->Close();
		
		return $INFO->returnxml( "1" );
	}
	
	function delete_favorite( $data ) {
		global $AUTH, $XML, $INFO;
		
		/*
		// RETOUR //
		-1 : Erreur
		 1 : La notice a été mise à jour
		*/
		
		// Lecture des données XML
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password)) return $AUTH->error;
		
		// Controle des champs
		if(!$INFO->FUNC->control_var_xml( $XML->data, array( "EAN", "TYPE" ) )) {
			$INFO->erreur = $INFO->returnxml( "-1", "DONNEES: Le champ \"EAN\" doit être renseigné" );
			return false;
		}
		
		if($XML->data["TYPE"] == "CP") $table = "coups_coeur";
		else $table = "produit_selections";
		
		// Connexion MySQL
		if(!$INFO->Connect()) return $INFO->erreur;
		
		if(!$INFO->Query("DELETE FROM ".$table." WHERE prod_ean = '".urlencode($XML->data["EAN"])."'")) return false;
		
		// Déconnexion MySQL
		$INFO->Close();
		
		return $INFO->returnxml( "1" );
	}

	function check( $data ) {
		global $AUTH, $XML, $INFO;
		
		/*
		// RETOUR //
		-1 : Erreur
		 0 : La notice n'existe pas
		 1 : La notice existe et est activée
		 2 : La notice existe et est desactivée
		*/
		
		// Lecture des données XML
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password )) return $AUTH->error;
		
		// Connexion MySQL
		if(!$INFO->Connect()) return $INFO->erreur;
		
		// Recherche de la notice
		if(!$INFO->Query("SELECT prod_ean, prod_deleted FROM produits WHERE prod_ean = '".urlencode($XML->data["EAN"])."'")) return $INFO->erreur;
		$ROW = $INFO->Result();
		$nb = $INFO->Num();
		
		if(!$INFO->Query("SELECT prod_assoc_ean FROM produit_associations WHERE prod_assoc_ean = '".urlencode($XML->data["EAN"])."'")) return $INFO->erreur;
		$nbb = $INFO->Num();
		
		// Déconnexion MySQL
		$INFO->Close();
		
		// La notice n'existe pas (comme la cuillère d'ailleur)
		if($nb <= 0 && $nbb <= 0) return $INFO->returnxml( "0" );
		// La notice existe et est activée
		elseif(empty($ROW["prod_deleted"])) return $INFO->returnxml( "1" );
		// La notice existe mais n'est pas active
		else return $INFO->returnxml( "2" );
	}
	
	function check_extended( $data ) {
		global $AUTH, $XML, $INFO;
		
		/*
		// RETOUR //
		-1 : Erreur
		 0 : La notice n'existe pas
		 1 : La notice existe et est activée
		 2 : La notice existe et est desactivée
		*/
		
		// Lecture des données XML
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password )) return $AUTH->error;
		
		// Connexion MySQL
		if(!$INFO->Connect()) return $INFO->erreur;
		
		// Recherche de la notice
		if(!$INFO->Query("SELECT prod_ean, prod_deleted FROM produits WHERE prod_ean = '".urlencode($XML->data["EAN"])."'")) return $INFO->erreur;
		$ROW = $INFO->Result();
		$nb = $INFO->Num();
		
		if(!$INFO->Query("SELECT prod_assoc_ean FROM produit_associations WHERE prod_assoc_ean = '".urlencode($XML->data["EAN"])."'")) return $INFO->erreur;
		$nbb = $INFO->Num();
		
		// COUP DE COEUR / SELECTION DU LIBRAIRE
		if(!$INFO->Query("SELECT prod_ean FROM coups_coeur WHERE prod_ean = '".urlencode($XML->data["EAN"])."'"))return $INFO->erreur;
		$CP = $INFO->Num();
		
		if(!$INFO->Query("SELECT prod_ean FROM produit_selections WHERE prod_ean = '".urlencode($XML->data["EAN"])."'")) return $INFO->erreur;
		$SELEC = $INFO->Num();
		
		// Mise en forme des résultats
		$return  = "<DATA>\n";
			$return .= "<CP>";
			if($CP > 0) $return .= "1";
			else $return .= "0";
			$return .= "</CP>\n";
			$return .= "<SELECTION>";
			if($SELEC > 0) $return .= "1";
			else $return .= "0";
			$return .= "</SELECTION>\n";
		$return .= "</DATA>\n";
		
		// Déconnexion MySQL
		$INFO->Close();
		
		// La notice n'existe pas (comme la cuillère d'ailleur)
		if($nb <= 0 && $nbb <= 0) return $INFO->returnxml( "0", false, false, $return );
		// La notice existe et est activée
		elseif(empty($ROW["prod_deleted"])) return $INFO->returnxml( "1", false, false, $return );
		// La notice existe mais n'est pas active
		else return $INFO->returnxml( "2", false, false, $return );
	}

	function listall( $data ) {
		global $AUTH, $XML, $INFO;
		
		/*
		// RETOUR //
		-1 : Erreur
		*/
		
		// Lecture des données XML
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password )) return $AUTH->error;
		
		// Connexion MySQL
		if(!$INFO->Connect()) return $INFO->erreur;
		
		// VARIABLES
		$lots = 10000;
		
		$start = (int)$XML->data["LIGNE"];
		$end   = $start + $lots;
		
		if($XML->data["LIGNE"] <= "1") {
			if(!$INFO->Query("SELECT 
									COUNT(prod_id) AS count 
									FROM 
									produits 
									WHERE 
									prod_deleted = '0' 
									AND prod_numerique = '0'")) return $INFO->erreur;
			$ROW = $INFO->Result();
			$NB = $ROW["count"];
		}
		else $NB = $XML->data["COUNT"];
		
		$eans   = array();
		$images = array();
		
		// RECHERCHE DES NOTICES
		if(!$INFO->Query("SELECT 
								prod_id, 
								prod_ean 
								FROM 
								produits 
								WHERE 
								prod_deleted = '0' 
								AND prod_numerique = '0' 
								ORDER BY prod_id ASC 
								LIMIT ".$start.", ".$lots)) return $INFO->erreur;
		while($ROW = $INFO->Result()) {
			$eans[$ROW["prod_ean"]] = $ROW["prod_id"];
		}
		
		// RECHERCHE DES IMAGES
		$sqlString = $INFO->FUNC->implodeSQLString(array_keys($eans));
		if(!empty($sqlString)) {
			if(!$INFO->Query("SELECT 
									prod_ean, 
									prod_image_front_hash, 
									prod_image_back_hash 
									FROM 
									produit_images 
									WHERE 
									prod_ean IN (".$sqlString.")")) return $INFO->erreur;
			while($ROW = $INFO->Result()) {
				$images[$ROW["prod_ean"]]["front"] 	   = $ROW["prod_image_front"];
				$images[$ROW["prod_ean"]]["frontHash"] = $ROW["prod_image_front_hash"];
				$images[$ROW["prod_ean"]]["back"] 	   = $ROW["prod_image_back"];
				$images[$ROW["prod_ean"]]["backHash"]  = $ROW["prod_image_back_hash"];
			}
		}
		
		// Déconnexion MySQL
		$INFO->Close();
		
		// Mise en forme des résultats
		$return  = "<DATA>\n";
		$return .= "<COUNT>".$NB."</COUNT>\n";
		$return .= "<LIGNE>".$end."</LIGNE>\n";
		while(list($key, $val) = each($eans)) {
			$return .= "<EAN id='".$val."'";
			if(!empty($images[$key]["frontHash"])) $return .= " imgFrontHash='".stripslashes($images[$key]["frontHash"])."'";
			if(!empty($images[$key]["backHash"])) $return .= " imgBackHash='".stripslashes($images[$key]["backHash"])."'";
			$return .= ">".urldecode($key)."</EAN>\n";
		}
		$return .= "</DATA>\n";
		
		// Envoi des résultats
		return $INFO->returnxml( "1", false, false, $return );
	}
	
	function listimg( $data ) {
		global $AUTH, $XML, $INFO;
		
		/*
		// RETOUR //
		-1 : Erreur
		*/
		
		// Lecture des données XML
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password )) return $AUTH->error;
		
		// Connexion MySQL
		if(!$INFO->Connect()) return $INFO->erreur;
		
		// VARIABLES
		$lots = 1000;
		
		$start = (int)$XML->data["LIGNE"];
		$end   = $start + $lots;
		
		$images = array();
		
		// RECHERCHE DES IMAGES
		if($XML->data["LIGNE"] <= "1") {
			if(!$INFO->Query("SELECT 
								prod_ean 
								FROM 
								produit_images")) return $INFO->erreur;
			
			$ROW = $INFO->Result();
			$NB = $INFO->Num();
		}
		else $NB = $XML->data["COUNT"];
		
		// RECHERCHE DES NOTICES
		if(!$INFO->Query("SELECT 
								prod_ean, 
								prod_image_front_hash, 
								prod_image_back_hash 
								FROM 
								produit_images 
								LIMIT ".$start.", ".$lots)) return $INFO->erreur;
		
		// Mise en forme des résultats
		$return  = "<DATA>\n";
		$return .= "<COUNT>".$NB."</COUNT>\n";
		$return .= "<LIGNE>".$end."</LIGNE>\n";
		
		while($ROW = $INFO->Result()) {
			$return .= "<IMG";
			if(!empty($ROW["prod_image_front_hash"])) $return .= " imgFrontHash='".stripslashes($ROW["prod_image_front_hash"])."'";
			if(!empty($ROW["prod_image_back_hash"])) $return .= " imgBackHash='".stripslashes($ROW["prod_image_back_hash"])."'";
			$return .= ">".urldecode($ROW["prod_ean"])."</IMG>\n";
		}
		$return .= "</DATA>\n";
		
		// Déconnexion MySQL
		$INFO->Close();
		
		// Envoi des résultats
		return $INFO->returnxml( "1", false, false, $return );
	}
	
	function syncinfo( $data ) {
		global $AUTH, $XML, $INFO;
		
		/*
		// RETOUR //
		-1 : Erreur
		*/
		
		// Lecture des données XML
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password )) return $AUTH->error;
		
		// Connexion MySQL
		if(!$INFO->Connect()) return $INFO->erreur;
		
		// VARIABLES
		$lots = 20;
		
		$start = (int)$XML->data["LIGNE"];
		$end   = $start + $lots;
		
		if($XML->data["LIGNE"] <= "1") {
			if(!$INFO->Query("SELECT 
									COUNT(prod_id) AS count 
									FROM produits 
									WHERE 
									prod_deleted = '0' 
									AND prod_numerique = '0'")) return $INFO->erreur;
			$ROW = $INFO->Result();
			$NB = $ROW["count"];
		}
		else $NB = $XML->data["COUNT"];
		
		$eans   = array();
		$images = array();
		
		// RECHERCHE DES NOTICES
		if(!$INFO->Query("SELECT 
								prod_id, 
								prod_ean, 
								prod_resume 
								FROM 
								produits 
								WHERE 
								prod_deleted = '0' 
								AND prod_numerique = '0' 
								ORDER BY prod_id ASC 
								LIMIT ".$start.", ".$lots)) return $INFO->erreur;
		while($ROW = $INFO->Result()) {
			$eans[$ROW["prod_ean"]]["id"] 	  = $ROW["prod_id"];
			$eans[$ROW["prod_ean"]]["resume"] = $ROW["prod_resume"];
		}
		
		// RECHERCHE DES IMAGES
		$sqlString = $INFO->FUNC->implodeSQLString(array_keys($eans));
		if(!empty($sqlString)) {
			if(!$INFO->Query("SELECT 
									prod_ean, 
									prod_image_front_hash, 
									prod_image_back_hash 
									FROM 
									produit_images 
									WHERE 
									prod_ean IN (".$sqlString.")")) return $INFO->erreur;
			while($ROW = $INFO->Result()) {
				$image  = array();
				$result = "";
				
				$image["front"] = $ROW["prod_image_front"];
				$image["back"]  = $ROW["prod_image_back"];
				
				while(list($key, $val) = each($image)) {
					$url   = "";
					$ext   = "";
					$path  = $INFO->static_dir."img/products/".stripslashes($val);
					
					if($INFO->static_host_type == "dir") $url = $INFO->abspath;
					else $url = STATIC_HOST;
					
					if(is_file($path)) {
						$tmp  = explode(".", stripslashes($val));
						$ext  = $tmp[(count($tmp) - 1)];
						$url .= "img/products/".stripslashes($val);
						
						$result .= " img".$key."ext='".$ext."' img".$key."url='".base64_encode($url)."'";
					}
				}
				$images[$ROW["prod_ean"]] = $result;
			}
		}
		
		// Déconnexion MySQL
		$INFO->Close();
		
		// Mise en forme des résultats
		$return  = "<DATA>\n";
		$return .= "<COUNT>".$NB."</COUNT>\n";
		$return .= "<LIGNE>".$end."</LIGNE>\n";
		
		while(list($key, $val) = each($eans)) {
			$return .= "<INFO id='".$val["id"]."' ean='".base64_encode(urldecode($key))."'";
			if(!empty($images[$key])) $return .= $images[$key];
			$return .= ">".base64_encode(stripslashes($val["resume"]))."</INFO>\n";
		}
		
		$return .= "</DATA>\n";
		
		// Envoi des résultats
		return $INFO->returnxml( "1", false, false, $return );
	}
	
	function getdata( $data ) {
		global $AUTH, $XML, $INFO;
		return $INFO->returnxml( "1" );
		/*
		// RETOUR //
		-1 : Erreur
		*/
		
		// Lecture des données XML
		/*if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password )) return $AUTH->error;
		
		// Connexion MySQL
		if(!$INFO->Connect()) return $INFO->erreur;
		
		// RESSOURCES STATIQUES
		if($INFO->static_host_type == "dir") $url = $INFO->abspath;
		else $url = STATIC_HOST;
		
		// Recherche de la notice
		if(!$INFO->Query("SELECT prod_id, prod_ean, prod_image_1, prod_resume FROM produits WHERE prod_ean = '".$XML->data["EAN"]."'")) return $INFO->erreur;
		$ROW = $INFO->Result();
		$NB = $INFO->Num();
		$image = "";
		if(is_file($INFO->static_dir."img/cache/cache".$ROW["prod_id"].".jpg")) $image = $url."img/cache/cache".$ROW["prod_id"].".jpg";
		elseif(!empty($ROW["prod_image_1"])) $image = $url."img/products/thumbs/".stripslashes($ROW["prod_image_1"]);
		
		// Mise en forme des résultats
		$return  = "<DATA>\n";
		$return .= "<IMAGE1>".$image."</IMAGE1>\n";
		$return .= "<IMAGE2></IMAGE2>\n";
		$return .= "<RESUME>".stripslashes($ROW["prod_resume"])."</RESUME>\n";
		$return .= "</DATA>\n";
		
		// Déconnexion MySQL
		$INFO->Close();
		
		// Envoi des résultats
		return $INFO->returnxml( "1", false, false, $return );*/
	}
}
?>
