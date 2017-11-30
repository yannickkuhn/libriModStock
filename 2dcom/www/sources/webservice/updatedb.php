<?php
class updatedb {
	private $datatempdir   = "./sources/webservice/data/";
	private $archiv_file   = "./sources/webservice/data/UPDATEDB.TXT_.ZIP";
	private $file 	 	   = "./sources/webservice/data/UPDATEDB.TXT";
	private $baseclifile   = "./sources/webservice/data/UPDATECLI.TXT";
	private $stockfile 	   = "./sources/webservice/data/UPDATEBASESTOCK.TXT";

	private $imgtempdir	   = "./sources/webservice/data/";
	private $imgfile 	   = "./sources/webservice/data/IMAGES.TXT";
	
	private $imgdir	 	   = "img/products/";
	private $imgdir_small  = "img/products/thumbs/";

	private $lots          = 1000;
	private $imglots 	   = 10;
	private $wrong_dispo   = array();
	private $wrong_section = array();
	private $rwAuthors	   = array();
	
	private $rwsummary 	   = false;
	private $rwcolor 	   = true;
	private $rwsize 	   = "";
	private $rwfont 	   = "";

	// ----------------------------
	// -    OPTIONS A DEFINIR     -
	// -----------------------------
	// > Options par défaut à compter du
	// 18/10/2016

	// logs de l'avancement (pour l'intégration du FEL la première fois)
	// lorsqu'on ne peut pas avoir la main sur le serveur
	// (attention : $forceFrontProcess doit être égal à false)
	private $log_avancement = true;
	private $log_step = 1000;

	// est-ce que le fichier UPDATEDB.TXT est envoyé archivé ou non ?
	private $is_archive	   = false;

	// type de commande PHP5 lancé sur le serveur,
	// est-on sur un serveur mutualisé OVH ? (lancement en tâche de fond)
	private $is_server_ovh_mutu = false;
	private $php5cmd = "php5";

	// Forcer le traitement sans tâche de fond ?
	// > Dans ce cas, activer le traitement des lots
	// > Sinon, le web service sera traité en tâche de fond 
	private $forceFrontProcess   = true;

	// Suppression de tous les articles (attention au cas du FEL où il faut l'option désactivé !)
	private $deleteAllProducts	= true;

	// Suppression de tous les articles de papeterie (ici on peut activer le FEL,
	// par exemple pour la petite marchande de prose)
	private $typePrixPapeterie = "6";
	private $deletePapeterieProducts = false;

	// Sectorisation des articles (rubriques de niveaux 0 = secteurs, niveaux 1 et 2 = rayons)
	private $sectorisationIpreFace = false;

	// Poids par défaut (false pour désactivé)
	private $poidspardefaut = false;

	// Lancer en mode LIBRIWEB uniquement
	// > cette option désactive la sectorisation IPreface et la catégorisation "normale"
	// > Cette option désactive les mailings de listes de recherches
	// > Cette option est valable uniquement si les tables "categories" et "categories_edilectre" existent
	// > Cette option créée automatiquement les catégories ci-dessus
	private $libriweb_uniquement = true;

	// Lancer en mode CLIENT COLLECTIVITE uniquement (COLIBRI)
	// > Cette option active la fonction de traitement du fichier UPDATECLIDB (mise à jour des clients)
	// > Cette option permet d'intégrer les données SOAP directement dans le nouveau format de la base
	// de données
	private $projet_colibri = false;

	// Lancer les commandes en mode "windows" ou "linux" (pour la tâche de fond)
	private $systeme = "linux";

	// -------------------------------- 


	
	function updatedb() {
		global $INFO;
		
		$this->imgdir 	     = $INFO->static_dir.$this->imgdir;
		$this->imgdir_small  = $INFO->static_dir.$this->imgdir_small;
	}
	
	function update( $data, $is_background_process = true ) {
		global $AUTH, $XML, $INFO, $LOG;
		
		/*
		// RETOUR //
		-1 : Erreur
		 1 : Stock mis à jour
		*/
		
		// PHP MUTUALISE
		if($this->is_server_ovh_mutu)
			$this->php5cmd = "/usr/local/php5.6/bin/php";

		// Paramètres
		$this->wrong_dispo   = $INFO->WBS["wrong_dispo"];
		$this->wrong_section = $INFO->WBS["wrong_section"];
		$this->rwsummary   	 = $INFO->WBS["rwsummary"];
		$this->rwcolor 		 = $INFO->WBS["rwcolor"];
		$this->rwsize   	 = $INFO->WBS["rwsize"];
		$this->rwfont 		 = $INFO->WBS["rwfont"];
		$this->rwAuthors	 = array();		
		if(isset($INFO->WBS["rwAuthors"])) {
			$this->rwAuthors = $INFO->WBS["rwAuthors"];
		}

		// Paramètres pour le web service
		if(isset($INFO->WBS["log_avancement"]))
		$this->log_avancement 			=	$INFO->WBS["log_avancement"]; 
		if(isset($INFO->WBS["log_step"]))
		$this->log_step 				=	$INFO->WBS["log_step"];
		if(isset($INFO->WBS["is_archive"]))
		$this->is_archive				=	$INFO->WBS["is_archive"];
		if(isset($INFO->WBS["is_server_ovh_mutu"]))
		$this->is_server_ovh_mutu		= 	$INFO->WBS["is_server_ovh_mutu"];
		if(isset($INFO->WBS["php5cmd"]))
		$this->php5cmd					= 	$INFO->WBS["php5cmd"];
		if(isset($INFO->WBS["forceFrontProcess"]))
		$this->forceFrontProcess		=	$INFO->WBS["forceFrontProcess"];
		if(isset($INFO->WBS["deleteAllProducts"]))
		$this->deleteAllProducts		=	$INFO->WBS["deleteAllProducts"];
		if(isset($INFO->WBS["typePrixPapeterie"]))
		$this->typePrixPapeterie		=	$INFO->WBS["typePrixPapeterie"];
		if(isset($INFO->WBS["deletePapeterieProducts"]))
		$this->deletePapeterieProducts	=	$INFO->WBS["deletePapeterieProducts"];
		if(isset($INFO->WBS["sectorisationIpreFace"]))
		$this->sectorisationIpreFace	=	$INFO->WBS["sectorisationIpreFace"];
		if(isset($INFO->WBS["poidspardefaut"]))
		$this->poidspardefaut			=	$INFO->WBS["poidspardefaut"];
		if(isset($INFO->WBS["libriweb_uniquement"]))
		$this->libriweb_uniquement		=	$INFO->WBS["libriweb_uniquement"];
		if(isset($INFO->WBS["projet_colibri"]))
		$this->projet_colibri			=	$INFO->WBS["projet_colibri"];

		// Lecture des données XML
		//$LOG->writeLog("Lecture des données XML...", "UpdateDb", "DEBUG - OK");
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
	
		// Lecture des paramètres dans les logs
		if($XML->data["LIGNE"] <= "1") {
			$LOG->writeLog("", "UpdateDb", "INFO - OK");
			$LOG->writeLog("", "UpdateDb", "INFO - OK");
			$LOG->writeLog("------------------------------------------------------------------------", "UpdateDb", "INFO - OK");
			$LOG->writeLog("Options de démarrage :", "UpdateDb", "INFO - OK");
			$LOG->writeLog("------------------------------------------------------------------------", "UpdateDb", "INFO - OK");
			$LOG->writeLog("> log_avancement : ".$this->log_avancement, "UpdateDb", "INFO - OK");
			$LOG->writeLog("> log_step : ".$this->log_step, "UpdateDb", "INFO - OK");
			$LOG->writeLog("> is_archive : ".$this->is_archive, "UpdateDb", "INFO - OK");
			$LOG->writeLog("> is_server_ovh_mutu : ".$this->is_server_ovh_mutu, "UpdateDb", "INFO - OK");
			$LOG->writeLog("> php5cmd : ".$this->php5cmd, "UpdateDb", "INFO - OK");
			$LOG->writeLog("> forceFrontProcess : ".$this->forceFrontProcess, "UpdateDb", "INFO - OK");
			$LOG->writeLog("> deleteAllProducts : ".$this->deleteAllProducts, "UpdateDb", "INFO - OK");
			$LOG->writeLog("> typePrixPapeterie : ".$this->typePrixPapeterie, "UpdateDb", "INFO - OK");
			$LOG->writeLog("> deletePapeterieProducts : ".$this->deletePapeterieProducts, "UpdateDb", "INFO - OK");
			$LOG->writeLog("> sectorisationIpreFace : ".$this->sectorisationIpreFace, "UpdateDb", "INFO - OK");
			$LOG->writeLog("> poidspardefaut : ".$this->poidspardefaut, "UpdateDb", "INFO - OK");
			$LOG->writeLog("> libriweb_uniquement : ".$this->libriweb_uniquement, "UpdateDb", "INFO - OK");
			$LOG->writeLog("> systeme utilise : ".$this->systeme, "UpdateDb", "INFO - OK");
			$LOG->writeLog("> projet_colibri : ".$this->projet_colibri, "UpdateDb", "INFO - OK");
			$LOG->writeLog("------------------------------------------------------------------------", "UpdateDb", "INFO - OK");
			$LOG->writeLog("", "UpdateDb", "INFO - OK");
			$LOG->writeLog("", "UpdateDb", "INFO - OK");
		}

		// Authentification de l'utilisateur
		//$LOG->writeLog("Authentification de l'utilisateur...", "UpdateDb", "DEBUG - OK");
		if(!$AUTH->load( $XML->user, $XML->password)) return $AUTH->error;
		
		// Archivage du fichier - décompression
		//$LOG->writeLog("Archive ? ($this->is_archive)...", "UpdateDb", "DEBUG - OK");
		if($this->is_archive) {
			if($this->forceFrontProcess == false || ($this->forceFrontProcess == true && $XML->data["LIGNE"] <= "1")) {
				$LOG->writeLog("Fonction de décompression de l'archive...", "UpdateDb", "DEBUG - OK");
				if(!$this->decompress_file($this->archiv_file)) return $INFO->erreur;
			}
		}

		// Lecture + MAJ des infos de stock
		//$LOG->writeLog("Fonction de lecture des données (UPDATEDB)...", "UpdateDb", "DEBUG - OK");
		if(!$this->read_data($data, $is_background_process)) return $INFO->erreur;
		
		// HISTORIQUE
		//$LOG->writeLog("Historique (enregistrement)...", "UpdateDb", "DEBUG - OK");
		if(!$this->whisto("UPDATEDB")) return $INFO->erreur;
		
		// Suppression du fichier stock
		if (!$is_background_process) {
            // Suppression du fichier stock
            // VERIFICATION DONNEE unlink($this->file);
        }
		
		// Retour
		return $INFO->returnxml( "1" );
	}

	function decompress_file($file) {
		global $INFO, $XML, $LOG;
		$LOG->writeLog("Ouverture du fichier $this->archiv_file en cours...", "UpdateDb", "INFO - OK");
		$zip = new ZipArchive;
		if ($zip->open($file) === TRUE) {
			$LOG->writeLog("Decompression du fichier $file en cours (dans $this->datatempdir)...", "UpdateDb", "INFO - OK");
		    $zip->extractTo($this->datatempdir);
		    $zip->close();
		    $LOG->writeLog("Decompression terminée ...", "UpdateDb", "INFO - OK");
		    return true;
		} else {
		    $LOG->writeLog("Decompression échouée ...", "UpdateDb", "DEBUG - KO");
		    return false;
		}
	}

	function read_data($data, $is_background_process) {
		global $INFO, $XML, $LOG;
		
		if(file_exists($this->file) && $fp = @fopen($this->file, "r")) {

			//$LOG->writeLog("Valeur de la variable forceFrontProcess = $this->forceFrontProcess", "UpdateDb", "INFO - OK");

			if($this->forceFrontProcess == false) {

				// Enregistrement début de traitement
				// ----------------------------------
	            if ($is_background_process) {

	            	$LOG->writeLog("", "UpdateDb", "INFO - OK");
					$LOG->writeLog("", "UpdateDb", "INFO - OK");

	            	$LOG->writeLog("Lancement de la tâche UPDATEDB() en tâche de fond ($this->php5cmd ". getcwd() ."/ws.php background_process updatedb) ...", "UpdateDb", "INFO - OK");
	                $cmd = $this->php5cmd.' '. getcwd() . '/ws.php background_process updatedb ' . escapeshellarg($data);

	                if(isset($this->systeme) && $this->systeme != "windows")
	                	exec($cmd . ' > /dev/null & ');
	               	else
	               		exec($cmd . ' > NUL & ');

	                return true;
	            }
	        }

			// Connexion MySQL
			if(!$INFO->Connect()) return false;

			// LOG dans la table ws_reporting
			// ----------------------------------
			if($this->forceFrontProcess == false) {
	            $wsr_mt_debut    = microtime(true);
	            $id_ws_reporting = $this->setReportingStart($XML->user, "read_data");
	            if (!$id_ws_reporting) {
	                return $INFO->erreur;
	            }
	        }
			
			// On vide la table stock
			if($XML->data["LIGNE"] <= "1") {
				$request = "DELETE FROM stock WHERE 1";
				if(!$this->logexec_request($request)) return false;

				$LOG->writeLog("Lancement de la fonction UPDATEDB() avec les paramètres : user : ".$XML->user, "UpdateDb", "INFO - OK");
				$LOG->writeLog("Codes de dispo supprimés : ".print_r($INFO->WBS["wrong_dispo"], true), "UpdateDb", "INFO - OK");

				// Nombre d'enregistrements à supprimer 

				if($this->deleteAllProducts || $this->deletePapeterieProducts) {
					$nb=0;

					// ajout de l'option pour la papeterie
					$add_request = "";
					if($this->deletePapeterieProducts)
						$add_request = " WHERE prod_type_prix = \"".$this->typePrixPapeterie."\"";

					// Compter le nombre d'enregistrements (logs)

					$request = "SELECT COUNT(*) FROM produits WHERE prod_deleted=\"0\"".$add_request;
					if(!$this->logexec_request($request)) return false;
					if($ROW = $INFO->Result()) {
						$nb = $ROW[0];
					}

					// Suppression de tous les produits 

					$request = "UPDATE produits SET prod_deleted=\"1\"".$add_request;
					if(!$this->logexec_request($request)) return false;
					
					$LOG->writeLog("Nombre de produits supprimés (PROD_DELETED) : ".$nb, "UpdateDb", "INFO - OK");
				}
			}

			// On charge les catégories uniquement si le mode LIBRIWEB uniquement est désactivé
			if((!isset($this->libriweb_uniquement)) || (isset($this->libriweb_uniquement) && $this->libriweb_uniquement == false)) {
				if($this->sectorisationIpreFace) {

					//$LOG->writeLog("Utilisation de la sectorisation IpreFace ...", "INFO - OK");

					// 1/ - On charge les rubriques de niveau 1 pour chercher les secteurs (IPreFace)
					// > PREREQUIS : il ne faut pas deux secteurs identiques !
					$RUB = array();
					$request = "SELECT 
									rub_id, 
									rub_libri_id, 
									rub_poids_moyen 
									FROM rubriques 
									WHERE rub_libri_id != ''
									AND rub_level = '0'";
					if(!$this->logexec_request($request)) return false;
					while($ROW = $INFO->Result()) {
						if(!empty($ROW["rub_libri_id"])) {
							$tmp = explode(";", urldecode($ROW["rub_libri_id"]));
							foreach($tmp as $element) {
								if(!isset($RUB[$element]["id"])) {
									$RUB[$element]["id"] 		  = $ROW["rub_id"];
									$RUB[$element]["poids_moyen"] = $ROW["rub_poids_moyen"];
								}
							}
						}
					}
					//var_dump($RUB);

					// 2.a/ - On charge les rubriques de niveau 2 pour chercher les rayons (IPreFace) et les parents
					// ------------------------------------------
					// > Gérer les doublons de rubriques ... : OK
					// ------------------------------------------
					$SUBRUB = array();
					$request = "SELECT 
									rub_id, 
									rub_libri_id, 
									rub_poids_moyen,
									rub_assoc
									FROM rubriques 
									WHERE rub_libri_id != ''
									AND ( rub_level = '1' )";
					if(!$this->logexec_request($request)) return false;
					while($ROW = $INFO->Result()) {
						if(!empty($ROW["rub_libri_id"])) {
							$tmp = explode(";", urldecode($ROW["rub_libri_id"]));
							foreach($tmp as $element) {
								if(!isset($SUBRUB[$element])) {
									$SUBRUB[$element] = array();
									$SUBRUB[$element]["compteur"] = 0;
								}
								$i = $SUBRUB[$element]["compteur"];
								$SUBRUB[$element]["rubs"][$i]["id"] 		  	= $ROW["rub_id"];
								$SUBRUB[$element]["rubs"][$i]["poids_moyen"] 	= $ROW["rub_poids_moyen"];
								$SUBRUB[$element]["rubs"][$i]["parent"] 		= $ROW["rub_assoc"];
								$SUBRUB[$element]["compteur"] ++;
							}
						}
					}

					// 2.b/ - On charge les rubriques de niveau 3 pour chercher les rayons (IPreFace) et les sous-parents
					// ------------------------------------------------------------------
					// Il faut chercher l'identifiant dans la table (BDD) : OK
					// ------------------------------------------------------------------
					$request = "SELECT 
									rub_id, 
									rub_libri_id, 
									rub_poids_moyen,
									rub_assoc
									FROM rubriques 
									WHERE rub_libri_id != ''
									AND ( rub_level = '2' )";
					if(!$this->logexec_request($request)) return false;
					while($ROW = $INFO->Result()) {
						if(!empty($ROW["rub_libri_id"])) {
							$tmp = explode(";", urldecode($ROW["rub_libri_id"]));
							$rub_assoc = $ROW["rub_assoc"]; 	// parent ...
							foreach($tmp as $element) {
								if(!isset($SUBRUB[$element])) {

									$SUBRUB[$element] = array();
									$SUBRUB[$element]["compteur"] = 0;
									
								}
								
								$i = $SUBRUB[$element]["compteur"];
								$SUBRUB[$element]["rubs"][$i]["id"] 		  	= $ROW["rub_id"];
								$SUBRUB[$element]["rubs"][$i]["poids_moyen"] 	= $ROW["rub_poids_moyen"];
								$request = "SELECT 
												rub_id, 
												rub_libri_id, 
												rub_assoc
												FROM rubriques 
												WHERE rub_id = '".$rub_assoc."'
												AND ( rub_level = '1' )";
								//echo $request;
								if(!$this->logexec_request($request, 2)) return false;
								if($ROWB = $INFO->Result(2)) {
									if($ROWB["rub_libri_id"] != "0")
										$SUBRUB[$element]["rubs"][$i]["parent"] = $ROWB["rub_assoc"];
								}
								$SUBRUB[$element]["compteur"] ++;
							}
						}
					}

					//var_dump($SUBRUB);
					//exit();
				} else {

					//$LOG->writeLog("Utilisation de la sectorisation normal (LIBRIWEB) ...", "INFO - OK");

					// On charge les rubriques
					$RUB = array();
					$request = "SELECT 
								rub_id, 
								rub_libri_id, 
								rub_poids_moyen 
								FROM rubriques 
								WHERE rub_libri_id != ''";
					if(!$this->logexec_request($request)) return false;
										//	WHERE rub_level = '2'")) return false;
					while($ROW = $INFO->Result()) {
						if(!empty($ROW["rub_libri_id"])) {
							$tmp = explode(",", urldecode($ROW["rub_libri_id"]));
							foreach($tmp as $element) {
								if(is_numeric($element) && $element >= 0) {
									if(!isset($RUB[$element]["id"])) {
										$RUB[$element]["id"] 		  = $ROW["rub_id"];
										$RUB[$element]["poids_moyen"] = $ROW["rub_poids_moyen"];
									}
									else {
										$RUB[$element]["id_2"] 		    = $ROW["rub_id"];
										$RUB[$element]["poids_moyen_2"] = $ROW["rub_poids_moyen"];
									}
								}
							}
						}
					}
				}
			}
			
			// On charge le mode d'ajout
			$request = "SELECT sc_value FROM soapconfig WHERE sc_name = 'addmod'";
			if(!$this->logexec_request($request)) return false;
			$ROW = $INFO->Result();
			switch($ROW["sc_value"]) {
				case "1":
					$prod_validate = 1;
					break;
				case "2":
					$prod_validate = 0;
					break;
				default:
					$prod_validate = 0;
					break;
			}
			
			// On charge les états occasion
			$ETATABRV = array();
			$request = "SELECT prod_etat_id, prod_etat_abrv FROM produit_etats";
			if(!$this->logexec_request($request)) return false;
			while($ROW = $INFO->Result()) {
				$ETATABRV[strtolower($ROW["prod_etat_abrv"])] = $ROW["prod_etat_id"];
			}

			// Nombre de lignes
			$contenu_fichier = file_get_contents($this->file);
			$nbLignesFichier = substr_count($contenu_fichier, "\n");
			
			// Compteur Ligne, Update, Insert
			$noline = 1;
			$nbLine   = 0;
            $nbUpdate = 0;
            $nbInsert = 0;
            $nbDelete = 0;
			
			// Lecture du fichier
			while(!feof($fp)) {

				// Ligne 0
				if((int)$XML->data["LIGNE"]==1)
					$XML->data["LIGNE"] = 0;


				// S'il faut changer de lignes au début du traitement (à partir du deuxième lot)
				if($this->forceFrontProcess == true) {
					if($noline == 1 && $XML->data["LIGNE"] >= $this->lots) {
						//$LOG->writeLog("Le script doit aller de la ligne ".$XML->data["LIGNE"]." sur ".$this->lots." lignes/lot", "UpdateDb", "INFO - OK");
						$this->GoToLine($fp, $XML->data["LIGNE"]);
						$noline += $XML->data["LIGNE"];
					}
				}

				// ON LIT CHAQUE LIGNE JUSQU'A TROUVER LE SEPARATEUR
				$buffer = fgets($fp, 4096);
				while(strpos($buffer, ";#<SEPARATOR>#") === false && !feof($fp)) {
					$buffer .= fgets($fp, 4096);
				}

				// -----------------------------
				// - PATCH YK
				// - 29/08/2016
				// - > possibilité de forcer la tâche avec le traitement par lots (tâche en front)
				// -----------------------------

				$traitement = true;
				if($this->forceFrontProcess == true) {
					if($noline >= $XML->data["LIGNE"] && $noline < ($XML->data["LIGNE"] + $this->lots)) {
						$traitement = true;
					}
					elseif($noline >= ($XML->data["LIGNE"] + $this->lots)) {
						$traitement = false;
					}
				}

				if($traitement == true) {
					if(!empty($buffer)) {
						$line = explode(";", $buffer);
						
						$add_data	  = addslashes($INFO->FUNC->point_virgule(trim($line[0])));
						$ean 	  	  = urlencode($INFO->FUNC->point_virgule(trim($line[1])));
						$isbn 	   	  = addslashes($INFO->FUNC->point_virgule(trim($line[2])));
						$titre 		  = addslashes($INFO->FUNC->point_virgule(trim($line[12])));
						$qte 		  = addslashes($INFO->FUNC->point_virgule(trim($line[30])));
						
						$add_data=1;
						
						// SI MAJ DE LA BASE PRODUITS
						if(!empty($add_data) && (int)$add_data == 1) {
							if($this->forceFrontProcess == true) {
								//if($noline == (1 + (int)$XML->data["LIGNE"]))
								//	$LOG->writeLog("Premier Ean traité ($noline) : $ean", "UpdateDb", "INFO - OK");
								//if($noline == ((int)$XML->data["LIGNE"] + $this->lots - 1))
								//	$LOG->writeLog("Dernier Ean traité ($noline) : $ean", "UpdateDb", "INFO - OK");
							}

							//$LOG->writeLog("Premier Ean traité ($noline) : $ean", "UpdateDb", "INFO - OK");


							$devise 	  = addslashes($INFO->FUNC->point_virgule(trim($line[3])));
							$prixttc 	  = addslashes($INFO->FUNC->point_virgule(trim($line[4])));
							$prixht 	  = addslashes($INFO->FUNC->point_virgule(trim($line[5])));
							$idtva1 	  = addslashes($INFO->FUNC->point_virgule(trim($line[6])));
							$tva1 		  = addslashes($INFO->FUNC->point_virgule(trim($line[7])));
							$idtva2 	  = addslashes($INFO->FUNC->point_virgule(trim($line[8])));
							$tva2 	      = addslashes($INFO->FUNC->point_virgule(trim($line[9])));
							$datepar 	  = addslashes($INFO->FUNC->point_virgule(trim($line[10])));
							$iddispo 	  = addslashes($INFO->FUNC->point_virgule(trim($line[11])));
							$editeur 	  = addslashes($INFO->FUNC->point_virgule(trim($line[13])));
							$idediteur 	  = addslashes($INFO->FUNC->point_virgule(trim($line[14])));
							$collection   = addslashes($INFO->FUNC->point_virgule(trim($line[15])));
							$idcollection = addslashes($INFO->FUNC->point_virgule(trim($line[16])));
							$auteur 	  = addslashes($INFO->FUNC->point_virgule(trim($line[17])));

							$theme_edl_g 	= addslashes($INFO->FUNC->point_virgule(trim($line[18])));
							$id_theme_edl_g	= addslashes($INFO->FUNC->point_virgule(trim($line[19])));
							$theme_per_g 	= addslashes($INFO->FUNC->point_virgule(trim($line[20])));
							$id_theme_per_g = addslashes($INFO->FUNC->point_virgule(trim($line[21])));

							$support 	  = addslashes($INFO->FUNC->point_virgule(trim($line[22])));
							$idsupport 	  = addslashes($INFO->FUNC->point_virgule(trim($line[23])));
							$distributeur = addslashes($INFO->FUNC->point_virgule(trim($line[24])));
							$iddistrib 	  = addslashes($INFO->FUNC->point_virgule(trim($line[25])));
							$poids 		  = addslashes($INFO->FUNC->point_virgule(trim($line[26])));
							$epaisseur 	  = addslashes($INFO->FUNC->point_virgule(trim($line[27])));
							$largeur 	  = addslashes($INFO->FUNC->point_virgule(trim($line[28])));
							$hauteur 	  = addslashes($INFO->FUNC->point_virgule(trim($line[29])));
							$occasion     = addslashes($INFO->FUNC->point_virgule(trim($line[31])));
							$ean_editeur  = addslashes($INFO->FUNC->point_virgule(trim($line[32])));
							$etat		  = addslashes($INFO->FUNC->point_virgule(trim($line[33])));
							$isref		  = addslashes($INFO->FUNC->point_virgule(trim($line[34])));
							$resume		  = addslashes($INFO->FUNC->point_virgule(base64_decode(trim($line[35]))));
							$typeDePrix    = addslashes($INFO->FUNC->point_virgule(trim($line[39])));

							if($tva1 == '')
								$tva1 = 0;
							if($tva2 == '')
								$tva2 = 0;

							if((int)$poids <= 0) {
								if($this->poidspardefaut != false && (int)$this->poidspardefaut > 0)
								$poids = $this->poidspardefaut;
							}
							
							// TABLEAU ASSOCIATION AUTEURS
							if(is_array($this->rwAuthors) && !empty($this->rwAuthors)) {
								if(isset($this->rwAuthors[stripslashes($auteur)])) $auteur = addslashes($this->rwAuthors[stripslashes($auteur)]);
							}
							
							if($this->rwsummary) $resume = addslashes($INFO->FUNC->rewrite_summary($resume, $this->rwsize, $this->rwfont, $this->rwcolor));
							else $resume = addslashes($resume);
							
							// RUBRIQUE BLACK LISTEE
							$wrong_section = false;
							if(in_array($id_theme_per_g, $this->wrong_section)) {
								$wrong_section = true;
							}
							// CODE DISPO BLACK LISTE
							$wrong_dispo = false;
							if(in_array($iddispo, $this->wrong_dispo)) {
								$wrong_dispo = true;
							}

							if((!isset($this->libriweb_uniquement)) || (isset($this->libriweb_uniquement) && $this->libriweb_uniquement == false)) {
								if($this->sectorisationIpreFace) {
									// Secteurs (utilise $RUB)
									// --------
									if(!empty($RUB[$id_theme_edl_g]["id"])) $id_theme_per = $RUB[$id_theme_edl_g]["id"];
									else {
										if(!empty($RUB[$id_theme_edl_g]["id_2"])) $id_theme_per = $RUB[$id_theme_edl_g]["id_2"];
										else $id_theme_per = 0;
									}

									$id_theme_per_2 = 0;

									// Rayons (utilise $SUBRUB)
									// ------
									// > Tester si le secteur existe 
									if($id_theme_per > 0) {
										// chercher le rayon correspondant avec le rayon (IPreface)
										if(!empty($SUBRUB[$id_theme_per_g])) {
											foreach($SUBRUB[$id_theme_per_g]["rubs"] as $key=>$val) {
												if($val["parent"] == $id_theme_per) {
													$id_theme_per_2 = $val["id"];
												}
											}
										}
									}
								} else {
									// RUBRIQUE
									// > PATCH YK 12/09/2016 : on peut désormais
									// utiliser à la fois les thèmes CLIC/les thèmes PERSO
									// dans les rubriques. Remarque : dans IPreWeb, on peut
									// remplacer les thèmes CLIC par les types de produits (secteurs)
									// > Prévoir dans le futur une modification LIBRIWEB pour envoyer les
									// secteurs à la place des thèmes CLIC !

									// Thèmes PERSO
									// ------------
									if(!empty($RUB[$id_theme_per_g]["id"])) $id_theme_per = $RUB[$id_theme_per_g]["id"];
									else {
										if(!empty($RUB[$id_theme_per_g]["id_2"])) $id_theme_per = $RUB[$id_theme_per_g]["id_2"];
										else $id_theme_per = "";
									}
									
									// Thèmes CLIC
									// -----------
									if(!empty($RUB[$id_theme_edl_g]["id"])) $id_theme_per_2 = $RUB[$id_theme_edl_g]["id"];
									else {
										if(!empty($RUB[$id_theme_edl_g]["id_2"])) $id_theme_per_2 = $RUB[$id_theme_edl_g]["id_2"];
										else $id_theme_per_2 = "";
									}
								}
							}
							if(isset($this->libriweb_uniquement) && $this->libriweb_uniquement == true) {

								// --------------------------------------
								// -- CREATION AUTOMATIQUE DES RUBRIQUES
								// --------------------------------------

								// Thèmes PERSO
								// ------------
								$id_theme_per = '';
								$request = "SELECT * FROM rubriques WHERE rub_libri_id = '".$id_theme_per_g."'";
								if(!$this->logexec_request($request)) return false;
								$nb  = $INFO->Num();
								if($nb <= 0) {
									$request = "INSERT INTO rubriques (rub_libri_id, rub_libelle) VALUES('".$id_theme_per_g."', '".$theme_per_g."')";
									if(!$this->logexec_request($request)) return false;
									$id_theme_per = $INFO->LastInsertId();
								}
								else {
									$ROW = $INFO->Result();
									$id_theme_per = $ROW["rub_id"];
								}

								// Thèmes CLIC
								// -----------
								$id_theme_per_2 = '';
								$request = "SELECT * FROM rubriques_edilectre WHERE rub_libri_id = '".$id_theme_edl_g."'";
								if(!$this->logexec_request($request)) return false;
								$nb  = $INFO->Num();
								if($nb <= 0) {
									$request = "INSERT INTO rubriques_edilectre (rub_libri_id, rub_libelle) VALUES('".$id_theme_edl_g."', '".$theme_edl_g."')";
									if(!$this->logexec_request($request)) return false;
									$id_theme_per_2 = $INFO->LastInsertId();
								}
								else {
									$ROW = $INFO->Result();
									$id_theme_per_2 = $ROW["rub_id"];
								}

							}
							
							// POIDS MOYEN
							if(empty($poids) && !empty($RUB[$id_theme_per_g]["poids_moyen"])) $poids = $RUB[$id_theme_per_g]["poids_moyen"];
							
							// A PARAITRE
							//$realiddispo = $iddispo;
							//if(!empty($iddispo) && $iddispo == "2") $aparaitre = "1";
							//else $aparaitre = "0";
							//if(empty($iddispo) or (!empty($iddispo) && $iddispo != "1")) $iddispo = 0;

							// DATES DE PARUTION VIDES 
							if($datepar == "")
								$datepar = "00000000";
							
							// ETAT OCCASION
							if(!empty($ETATABRV[strtolower($etat)])) $idetat = $ETATABRV[strtolower($etat)];
							else $idetat = "";
							
							// VERIFIE SI FICHE OCCASION EXISTE DEJA EN PRODUIT ASSOCIES
							if((int)$occasion == 1 && $isref != "1") {
								if(!empty($ean_editeur)) $tmp_ean = $ean_editeur;
								else $tmp_ean = $ean;
								
								// SUPPRESSION DE L'ARTICLE DE LA TABLE PRODUIT
								$request = "UPDATE produits SET prod_deleted = \"1\" WHERE prod_ean = '".$ean."'";
								if(!$this->logexec_request($request)) return false;
								
								$request = "SELECT prod_assoc_id FROM produit_associations WHERE prod_assoc_ean = '".$ean."'";
								if(!$this->logexec_request($request)) return false;
								$nb  = $INFO->Num();
								$ROW = $INFO->Result();
								
								if($nb > 0) {
									// RUBRIQUE BLACK LISTEE
									if($wrong_section) {
										$request = "DELETE FROM produit_associations WHERE prod_assoc_ean = '".$ean."'";
										if(!$this->logexec_request($request)) return false;
									}
									else {
										$reqassoc = "";
										if(!empty($idetat)) $reqassoc .= "prod_etat_id = '".$idetat."', ";

										// Uniquement le thème perso doit être pris en compte pour
										// les produits d'occasions !
										if(!empty($id_theme_per)) $reqassoc .= "prod_assoc_rub_per_id = '".$id_theme_per."', ";
										
										$request = "UPDATE produit_associations 
													SET ".$reqassoc."prod_ean = '".$tmp_ean."', 
														prod_assoc_etat = '".$etat."', 
														
														prod_assoc_parution = '".$INFO->FUNC->simpledateen($datepar)."',
														prod_assoc_dispo = '".$iddispo."',
														prod_assoc_titre = '".$titre."',
														prod_assoc_auteurs = '".$auteur."',
														prod_assoc_editeur = '".$editeur."',
														prod_assoc_collection = '".$collection."',
														prod_assoc_support = '".$support."',
														prod_assoc_poids = '".$poids."',

														prod_assoc_prixttc = '".$INFO->FUNC->format_decimal($prixttc)."', 
														prod_assoc_prixht = '".$INFO->FUNC->format_decimal($prixht)."', 
														prod_assoc_date_mod = '".date("Y-m-d")."', 
														prod_assoc_heure_mod = '".date("H:i:s")."' 
													WHERE prod_assoc_ean = '".$ean."'";
										if(!$this->logexec_request($request)) return false;
									}
								}
								// RUBRIQUE NON BLACK LISTEE
								elseif(!$wrong_section) {

									$addReq  = "";
									$addData = "";
 									if(!empty($id_theme_per)) {
 										$addReq 	.= "prod_assoc_rub_per_id, ";
 										$addData 	.= "'".$id_theme_per."', ";
 									}
 									if(!empty($idetat)) {
 										$addReq 	.= "prod_etat_id, ";
 										$addData 	.= "'".$idetat."', ";
 									}

									$request = "INSERT INTO produit_associations 
												(
												".$addReq."
												prod_ean, 
												prod_assoc_ean, 
												prod_assoc_etat, 
												prod_assoc_prixttc, 
												prod_assoc_prixht, 
												prod_assoc_date_ajout, 
												prod_assoc_heure_ajout,

												prod_assoc_parution,
												prod_assoc_dispo,
												prod_assoc_titre,
												prod_assoc_auteurs,
												prod_assoc_editeur,
												prod_assoc_collection,
												prod_assoc_support,
												prod_assoc_poids
												) 
												VALUES 
												(
												".$addData."
												'".$tmp_ean."', 
												'".$ean."', 
												'".$etat."', 
												'".$INFO->FUNC->format_decimal($prixttc)."', 
												'".$INFO->FUNC->format_decimal($prixht)."', 
												'".date("Y-m-d")."', 
												'".date("H:i:s")."',

												'".$INFO->FUNC->simpledateen($datepar)."',
												'".$iddispo."',
												'".$titre."',
												'".$auteur."',
												'".$editeur."',
												'".$collection."',
												'".$support."',
												'".$poids."'

												)";
									if(!$this->logexec_request($request)) return false;
								}
								
								// VERIFIE SI FICHE OCCASION EXISTE DEJA EN PRODUIT SANS ASSOCIATION
								if($ean != $tmp_ean) {
									$request = "SELECT prod_id FROM produits WHERE prod_occasion = '1' AND prod_ean = '".$ean."'";
										if(!$this->logexec_request($request)) return false;
									$nb = $INFO->Num();
									
									if($nb > 0) {
										$request = "DELETE FROM produits WHERE prod_ean = '".$ean."'";
										if(!$this->logexec_request($request)) return false;
									}
								}
							}
							
							if(((int)$occasion != 1) or ((int)$occasion == 1 && empty($ean_editeur))) {
								// VERIF SI FICHE EXISTE + UPDATE OU INSERT
								$request = "SELECT 
											prod_ean, 
											prod_titre, 
											prod_auteurs, 
											prod_editeur, 
											prod_collection, 
											prod_support, 
											prod_poids, 
											prod_epaisseur, 
											prod_largeur, 
											prod_hauteur, 
											prod_resume, 
											prod_deleted 
											FROM produits 
											WHERE prod_ean = '".$ean."'";
								if(!$this->logexec_request($request)) return false;
								$nb  = $INFO->Num();
								
								if($nb > 0) {
									// RUBRIQUE BLACK LISTEE
									if($wrong_section) {
										$request = "DELETE FROM produits WHERE prod_ean = '".$ean."'";
										if(!$this->logexec_request($request)) return false;
											$request = "DELETE FROM produit_selections WHERE prod_ean = \"".$ean."\"";
										if(!$this->logexec_request($request)) return false;
											$request = "DELETE FROM coups_coeur WHERE prod_ean = \"".$ean."\"";
										if(!$this->logexec_request($request)) return false;
											$nbDelete++;
									}
									else {
										$ROW = $INFO->Result();
										
										$req = "";
										
										$req .= "prod_titre = '".$titre."', ";
										$req .= "prod_editeur = '".$editeur."', ";
										$req .= "prod_collection = '".$collection."', ";
										$req .= "prod_auteurs = '".$auteur."', ";
										$req .= "prod_support = '".$support."', ";
										$req .= "prod_poids = '".$poids."', ";
										$req .= "prod_epaisseur = '".$epaisseur."', ";
										$req .= "prod_largeur = '".$largeur."', ";
										$req .= "prod_hauteur = '".$hauteur."', ";
										if(empty($ROW["prod_resume"])) $req .= "prod_resume = '".$resume."', ";
										if((int)$typeDePrix > 0)
											$req .= "prod_type_prix = '".$typeDePrix."', ";

										if(is_numeric($iddistrib)) {
											$req .= "prod_glndistrib = '".$iddistrib."', ";
										}

										// -----------------------------------------
										// PATCH YK - 13/10/2016 - si la section est 
										// black listée et que le stock est à 0, le 
										// produit est supprimé. A l'inverse, il est
										// réactivé..
										if($wrong_dispo && (int)urldecode($qte) <= 0) {
											$req .= "prod_deleted = 1, ";
										} else {
											$req .= "prod_deleted = 0, ";
										}
										
									
										$thm = "";
										if(!empty($id_theme_per)) $thm .= "rub_1_id = '".(int)$id_theme_per."', ";
										if(!empty($id_theme_per_2)) $thm .= "rub_2_id = '".(int)$id_theme_per_2."', ";
										$request = "UPDATE produits 
														SET ".$thm.$req."
														prod_ean = '".$ean."', 
														prod_prixttc = '".$INFO->FUNC->format_decimal($prixttc)."', 
														prod_prixht = '".$INFO->FUNC->format_decimal($prixht)."', 
														prod_tva1 = '".$INFO->FUNC->format_decimal($tva1)."', 
														prod_tva2 = '".$INFO->FUNC->format_decimal($tva2)."', 
														prod_parution = '".$INFO->FUNC->simpledateen($datepar)."', 
														prod_date_mod = '".date("Y-m-d")."', 
														prod_heure_mod = '".date("H:i:s")."', 
														prod_dispo = '".$iddispo."', 
														prod_occasion = '".$occasion."',
														prod_namedistrib = '".$distributeur."'
														WHERE prod_ean = '".$ean."'";
										if(!$this->logexec_request($request)) return false;
										$nbUpdate++;
										
										// MAJ RUBRIQUE
										if(!empty($id_theme_per)) {
											$request = "UPDATE produit_selections SET rub_1_id = '".$id_theme_per."', rub_2_id = '".(int)$id_theme_per_2."' WHERE prod_ean = '".$ean."'";
											if(!$this->logexec_request($request)) return false;
											$request = "UPDATE coups_coeur SET rub_1_id = '".$id_theme_per."', rub_2_id = '".(int)$id_theme_per_2."' WHERE prod_ean = '".$ean."'";
											if(!$this->logexec_request($request)) return false;
										}
									}
								}
								elseif(!$wrong_section) {
									if(!in_array($iddispo, $this->wrong_dispo) && $prixttc > 0) {

										$reqVar = "";
										$valVar = "";

										if(is_numeric($iddistrib)) {
											$reqVar .= "prod_glndistrib, ";
											$valVar .= "'".$iddistrib."', ";
										}
										if((int)$typeDePrix > 0) {
											$reqVar .= "prod_type_prix, ";
											$valVar .= "'".$typeDePrix."', ";
										}

										$reqVar .= "prod_deleted, ";
										$valVar .= "0, ";

										$request = "INSERT INTO produits 
													(
													$reqVar
													rub_1_id, 
													rub_2_id, 
													prod_etat_id, 
													prod_ean, 
													prod_prixttc, 
													prod_prixht, 
													prod_tva1, 
													prod_tva2, 
													prod_parution, 
													prod_titre, 
													prod_editeur, 
													prod_collection, 
													prod_auteurs, 
													prod_support, 
													prod_resume, 
													prod_poids, 
													prod_epaisseur, 
													prod_largeur, 
													prod_hauteur, 
													prod_dispo, 
													prod_occasion, 
													prod_validate, 
													prod_date_ajout, 
													prod_heure_ajout,
													prod_namedistrib
													) 
													VALUES 
													(
													$valVar
													'".(int)$id_theme_per."', 
													'".(int)$id_theme_per_2."', 
													'1', 
													'".$ean."', 
													'".$INFO->FUNC->format_decimal($prixttc)."', 
													'".$INFO->FUNC->format_decimal($prixht)."', 
													'".$INFO->FUNC->format_decimal($tva1)."', 
													'".$INFO->FUNC->format_decimal($tva2)."', 
													'".$INFO->FUNC->simpledateen($datepar)."', 
													'".$titre."', 
													'".$editeur."', 
													'".$collection."', 
													'".$auteur."', 
													'".$support."', 
													'".$resume."', 
													'".$poids."', 
													'".$epaisseur."', 
													'".$largeur."', 
													'".$hauteur."', 
													'".$iddispo."', 
													'".$occasion."', 
													'".$prod_validate."', 
													'".date("Y-m-d")."', 
													'".date("H:i:s")."',
													'".$distributeur."'
													)";
										if(!$this->logexec_request($request)) return false;
										$nbInsert++;
									}
								}
							}

						}
						
						// MAJ DU STOCK
						if((int)urldecode($qte) > 0 && !empty($add_data) && (int)$add_data == 1) {

							// --
							// Problème : le stock est ajouté plusieurs fois
							// --
							// On cache le problème en cherchant si le code EAN a déjà été ajouté
							// --

							$request = "SELECT * FROM stock WHERE stock_ean = '".$ean."'";
							if(!$this->logexec_request($request)) return false;
							$nb  = $INFO->Num();
							if($nb > 0) {
								$request = "UPDATE stock SET stock_qte = '".$qte."' WHERE stock_ean = '".$ean."'";
								if(!$this->logexec_request($request)) return false;
							}
							else {
								$request = "INSERT INTO stock (stock_ean, stock_qte) VALUES('".$ean."', '".$qte."')";
								if(!$this->logexec_request($request)) return false;
							}

							// MAIL UTILISATEUR LISTE RECHERCHE
							if((!isset($this->libriweb_uniquement)) || (isset($this->libriweb_uniquement) && $this->libriweb_uniquement == false)) {
								$INFO->FUNC->mail_listes_recherche($ean);
							}
						}

						$nbLine++;
					}
				}
				else {
					// Fermeture du fichier
					fclose($fp);
					// Déconnexion MySQL
					$INFO->Close();
					
					//$LOG->writeLog("Nombre de lignes traitées : $noline du fichier (sur $nbLine lues)", "UpdateDb", "INFO - OK");
					$INFO->erreur = $INFO->returnxml( "1", "LIGNE ".$noline, $noline );
					return false;
				}

				// -------------------------------------------
				// PATCH YK - 30/08/2016 - suivi avancement
				//  en tâche de fond (logs)
				// -------------------------------------------
				
				if($this->log_avancement == true) {
					if($nbLine%$this->log_step == 0) {
						$LOG->writeLog("Tache de ". ($this->forceFrontProcess == true ? 'front' : 'fond') ." : nombre de lignes traitees : $noline du fichier (sur $nbLine lues)", "UpdateDb", "INFO - OK");
					}
				}

				$noline++;
			}
			
			// Fermeture du fichier
			fclose($fp);

			// Reporting
			$wsr_commentaire = "LIBRIWEB s'est terminé avec succès";
			
			$LOG->writeLog($wsr_commentaire, "UpdateDb", "INFO - OK");

			if($this->forceFrontProcess == true) {
				$LOG->writeLog("", "UpdateDb", "INFO - OK");
				$LOG->writeLog("", "UpdateDb", "INFO - OK");
			}

			if($this->forceFrontProcess == false) {
				$wsr_report_commentaire = "";
				$wsr_report_commentaire .= $wsr_commentaire."\n";
				
				$wsr_commentaire = 'Ligne' . ($nbLine > 1 ? 's' : '') . ' : ' . number_format($nbLine, 0, ', ', ' ');
				$wsr_report_commentaire .= $wsr_commentaire."\n";
	            $LOG->writeLog($wsr_commentaire, "UpdateDb", "INFO - OK");

	            $wsr_commentaire = 'Insertion' . ($nbInsert > 1 ? 's' : '') . ' : ' . number_format($nbInsert, 0, ', ', ' ');
	            $wsr_report_commentaire .= $wsr_commentaire."\n";
	            $LOG->writeLog($wsr_commentaire, "UpdateDb", "INFO - OK");

	            $wsr_commentaire = 'Suppression' . ($nbDelete > 1 ? 's' : '') . ' : ' . number_format($nbDelete, 0, ', ', ' ');
	            $wsr_report_commentaire .= $wsr_commentaire."\n";
	            $LOG->writeLog($wsr_commentaire, "UpdateDb", "INFO - OK");

	            $wsr_commentaire = 'Mise' . ($nbUpdate > 1 ? 's' : '') . ' à jour : ' . number_format($nbUpdate, 0, ', ', ' ');
	            $wsr_report_commentaire .= $wsr_commentaire."\n";
				$LOG->writeLog($wsr_commentaire, "UpdateDb", "INFO - OK");
				$LOG->writeLog("", "UpdateDb", "INFO - OK");
				$LOG->writeLog("", "UpdateDb", "INFO - OK");

			
				if (!$this->setReportingEnd($id_ws_reporting, $wsr_mt_debut, $wsr_report_commentaire)) {
	                return false;
	            }
	        }

			// Déconnexion MySQL
			$INFO->Close();
			
			return true;
		}
		
		$INFO->erreur = $INFO->returnxml( "-1", "DONNEES: aucune information de stock à intégrer" );
		return false;
	}

	function GoToLine( $handle, $line ) {
		fseek($handle,0);  // seek to 0
		$i = 0; 
		$bufcarac = 0;                    

		for($i = 1;$i<$line;$i++)
		{
		  $ligne = fgets($handle);
		  $bufcarac += strlen($ligne); 
		}  

		fseek($handle,$bufcarac);
	}
	
	function updateimg( $data ) {
		global $AUTH, $XML, $INFO;
		
		/*
		// RETOUR //
		-1 : Erreur
		 1 : Stock mis à jour
		*/
		
		// Lecture des données XML
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password)) return $AUTH->error;
		
		// Lecture + MAJ des infos de stock
		if(!$this->read_imgdata()) return $INFO->erreur;
		
		// Suppression du fichier stock
		//unlink($this->imgfile);
		
		// Retour
		return $INFO->returnxml( "1" );
	}
	
	function read_imgdata() {
		global $INFO, $XML, $LOG;
		
		if(file_exists($this->imgfile) && $fp = @fopen($this->imgfile, "r")) {
			// Connexion MySQL
			if(!$INFO->Connect()) return false;
			
			// Numéro de ligne
			$noline = 1;
			
			// Lecture du fichier
			while(!feof($fp)) {
				$buffer = fgets($fp, 4096);
				
				if($noline >= $XML->data["LIGNE"] && $noline < ($XML->data["LIGNE"] + $this->imglots)) {
					if(!empty($buffer)) {
						$line  = explode(";", $buffer);
						$image = array();
						
						$ean 	  	    = urlencode($INFO->FUNC->point_virgule(trim($line[0])));
						$image["front"] = $INFO->FUNC->point_virgule(trim($line[1]));
						
						// image 2 non vide et image 1 vide (peut être une deuxième nouvelle image ?)
						if(empty($image["front"]) && !empty($line[2]) ) {
							$request = "SELECT
										prod_image_front
										FROM produit_images 
										WHERE 
										prod_ean = '".$ean."'";
							if(!$this->logexec_request($request)) return false;
							$NB = $INFO->Num();
							if($NB > 0) {
								$ROW = $INFO->Result();
								//$LOG->writeLog("Valeur de prod_image_front : ".$XML->user, "UpdateDb", "INFO - OK");
								if($ROW["prod_image_front"] != "") {
									$image["back"] = $INFO->FUNC->point_virgule(trim($line[2]));
								} else {
									$image["front"] = $INFO->FUNC->point_virgule(trim($line[2]));
								}
							}
							else 
								$image["front"] = $INFO->FUNC->point_virgule(trim($line[2]));
						} 
						else if(!empty($line[2])) {
							$image["back"] = $INFO->FUNC->point_virgule(trim($line[2]));
						}
						
						//if (empty($image["front"]))
						//{
						//	$request = "DELETE FROM 
						//				produit_images 
						//				WHERE 
						//				prod_ean = '".$ean."'";
						//	if(!$this->logexec_request($request)) return false;
						//}
						//else
						//{
						while(list($key, $val) = each($image)) {
							if(!empty($ean) && !empty($val)) {
								if(is_file($this->imgtempdir.$val)) {
									// SUPPRESSION DES CARACTERES INTERDITS
									//$img = $INFO->FUNC->make_html_link($val);
									$tmp = explode(".", $val);
									$ext = $tmp[count($tmp) - 1];
									unset($tmp[count($tmp) - 1]);
									$img = $INFO->FUNC->make_html_link(implode("", $tmp)).".".$ext;
									
									// DEPLACEMENT DANS LE BON REPERTOIRE IMAGE
									if(rename($this->imgtempdir.$val, $this->imgdir.$img)) {
										// HASH MD5
										$hash = md5_file($this->imgdir.$img);
										
										// MINIATURE
										$thumbs = $this->thumbs($this->imgdir, $this->imgdir_small, $img);
										if($thumbs) {
											$request = "SELECT 
														prod_image_id, 
														prod_image_protected 
														FROM 
														produit_images 
														WHERE 
														prod_ean = '".$ean."'";
											if(!$this->logexec_request($request)) return false;
											$NB = $INFO->Num();
											
											if($NB > 0) {
												$ROW = $INFO->Result();
												
												if($ROW["prod_image_protected"] == "0") {
													$request = "UPDATE 
																produit_images 
																SET 
																prod_image_".$key." = '".addslashes($img)."', 
																prod_image_".$key."_hash = '".$hash."' 
																WHERE 
																prod_ean = '".$ean."'";
													if(!$this->logexec_request($request)) return false;
												}
											}
											else {
												$request = "INSERT INTO 
															produit_images 
															(
															prod_ean, 
															prod_image_httplink, 
															prod_image_".$key.", 
															prod_image_".$key."_hash 
															) 
															VALUES 
															(
															'".$ean."', 
															'0', 
															'".addslashes($img)."', 
															'".$hash."' 
															)";
												if(!$this->logexec_request($request)) return false;
											}
										}
									}
								}
							}
						}
						//}
					}
				}
				elseif($noline >= ($XML->data["LIGNE"] + $this->imglots)) {
					// Fermeture du fichier
					fclose($fp);
					// Déconnexion MySQL
					$INFO->Close();
					
					$INFO->erreur = $INFO->returnxml( "1", "LIGNE ".$noline, $noline );
					return false;
				}
				
				$noline++;
			}
			
			// Fermeture du fichier
			fclose($fp);
			// Déconnexion MySQL
			$INFO->Close();
			return true;
		}
		
		$INFO->erreur = $INFO->returnxml( "-1", "DONNEES: aucune image à intégrer" );
		return false;
	}
	
	function thumbs($img_src, $img_dest, $img_name) {
		$img = $img_src.$img_name;
		$thumbs = true;
		
		$dst_w = "80";
		$dst_h = "80";
		
		// TAILLE DE L IMAGE
		$size = @getimagesize($img);
		$src_w = $size[0];
		$src_h = $size[1];
		
		// RATIO
		if($src_w < $dst_w && $src_h < $dst_h) {
			$dst_w = $src_w;
			$dst_h = $src_h;
		}
		else
		$dst_h = round(($dst_w / $src_w) * $src_h);
		
		// CREATION EN FONTION DU TYPE
		switch($size[2]) {
			case 1 :
				if(imagetypes() & IMG_GIF) $src = @imagecreatefromgif($img);
			break;
				
			case 2 :
				if(imagetypes() & IMG_JPG) $src = @imagecreatefromjpeg($img);
			break;
			
			case 3 :
				if(imagetypes() & IMG_PNG) $src = @imagecreatefrompng($img);
			break;
			
			default :
				if(preg_match("/\.wbmp$/", $img) && (imagetypes() & IMG_WBMP)) {
					$src = @imagecreatefromwbmp($img);
					
					$size[0] = imagesx($src);
					$size[1] = imagesy($src);
					
					if(!isset($format)) $format = 4;
				}
			break;
		}
		
		// PAS DE MINIATURE POSSIBLE
		if(empty($src)) {
			$thumbs = false;
		}
		// MINIATURE
		else {
			$dest = imagecreatetruecolor($dst_w, $dst_h);
			imagecopyresampled($dest, $src, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
			
			$tn_name = $img;
			$tn_name = preg_replace("/\.(gif|jpe|jpg|jpeg|png|wbmp)$/i", "", $tn_name);
			$tn_name = preg_replace("/.*\/([^\/]+)$/i", $img_dest."\\1", $tn_name);
			
			if(isset($format)) $type = $format;
			else $type = $size[2];
			
			switch($type) {
				case 1 :
					if(file_exists($tn_name.".gif")) unlink($tn_name.".gif");
					if(imagetypes() & IMG_GIF) imagegif($dest, $tn_name.".gif");
				break;
				
				case 2 :
					if(file_exists($tn_name.".jpg")) unlink($tn_name.".jpg");
					if(imagetypes() & IMG_JPG) imagejpeg($dest, $tn_name.".jpg");
				break;
				
				case 3 :
					if(file_exists($tn_name.".png")) unlink($tn_name.".png");
					if(imagetypes() & IMG_PNG) imagepng($dest, $tn_name.".png");
				break;
				
				default :
					if(file_exists($tn_name.".wbmp")) unlink($tn_name.".wbmp");
					if(imagetypes() & IMG_WBMP) imagewbmp($dest, $tn_name.".wbmp");
				break;
			}
			
			if(!($thumbs)) $thumbs = false;
		}
		
		return $thumbs;
	}
	
	function whisto( $type ) {
		global $INFO;
		
		// Connexion MySQL
		if(!$INFO->Connect()) return false;
		
		$request = "INSERT INTO histo_maj
					(
					histo_maj_date, 
					histo_maj_heure, 
					histo_maj_type
					) 
					VALUES 
					(
					'".date("Y-m-d")."', 
					'".date("H:i:s")."', 
					'".$type."'
					)";
		if(!$this->logexec_request($request)) return false;
		
		// Déconnexion MySQL
		$INFO->Close();
		
		return true;
	}

	function updatestock( $data ) {
		global $AUTH, $XML, $INFO;
		
		/*
		// RETOUR //
		-1 : Erreur
		 1 : Stock mis à jour
		*/
		
		// Lecture des données XML
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password)) return $AUTH->error;
		
		// Lecture + MAJ des infos de stock
		if(!$this->read_basestock()) return $INFO->erreur;
		
		// Suppression du fichier stock
		//unlink($this->stockfile);
		
		// Retour
		return $INFO->returnxml( "1" );
	}

	function read_basestock() {
		global $INFO, $XML, $LOG;
		
		if(file_exists($this->stockfile) && $fp = @fopen($this->stockfile, "r")) {
			// Connexion MySQL
			if(!$INFO->Connect()) return false;
			
			// LOG dans la table ws_reporting
			// ----------------------------------
            $wsr_mt_debut    = microtime(true);
            $id_ws_reporting = $this->setReportingStart($XML->user, "read_basestock");
            if (!$id_ws_reporting) {
                return $INFO->erreur;
            }

			// Numéro de ligne
			$noline = 1;
			$nbline = 1;
			
			// Suppression du stock
			$request = "DELETE FROM stock";
			if(!$this->logexec_request($request)) return false;

			// Lecture du fichier
			while(!feof($fp)) {
				$buffer = fgets($fp, 4096);

				if(!empty($buffer)) {
					$line  = explode(";", $buffer);
					$ean = urlencode($INFO->FUNC->point_virgule(trim($line[0])));
					$stock = urlencode($INFO->FUNC->point_virgule(trim($line[1])));
					// Si le produit existe, on mets à jour son stock
					$request = "SELECT * FROM produits WHERE prod_ean = '".$ean."'";
					if(!$this->logexec_request($request)) return false;
					if($ROW = $INFO->Result()) {
						$request = "UPDATE stock SET stock_qte = '".$stock."' WHERE stock_ean = '".$ean."'";
						if(!$this->logexec_request($request)) return false;
						$nbline++;
					}
					
					$noline++;
				}
			}

			// Reporting
			$wsr_report_commentaire = "";

			$wsr_commentaire = "LIBRIWEB s'est terminé avec succès";
			$wsr_report_commentaire .= $wsr_commentaire."\n";
			$LOG->writeLog($wsr_commentaire, "UpdateDb", "INFO - OK");

            $wsr_commentaire = 'Insertion dans le stock' . ' : ' . number_format($nbline, 0, ', ', ' ');
            $wsr_report_commentaire .= $wsr_commentaire."\n";
            $LOG->writeLog($wsr_commentaire, "UpdateDb", "INFO - OK");
			
            if (!$this->setReportingEnd($id_ws_reporting, $wsr_mt_debut, $wsr_report_commentaire)) {
                return false;
            }

			// Fermeture du fichier
			fclose($fp);
			// Déconnexion MySQL
			$INFO->Close();
			return true;
		}
		
		$INFO->erreur = $INFO->returnxml( "-1", "DONNEES: pas de stock à remonter" );
		return false;
	}

	function updatecli( $data ) {
		global $AUTH, $XML, $INFO, $LOG;
		
		/*
		// RETOUR //
		-1 : Erreur
		 1 : Stock mis à jour
		*/

		$LOG->writeLog("Traitement du fichier clients (COLIBRI) ?", "UpdateCLIDB", "INFO - OK");

		if($this->projet_colibri) {

			$LOG->writeLog("Oui, traitement en cours ...", "UpdateCLIDB", "INFO - OK");
		
			// Lecture des données XML
			if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
			
			// Authentification de l'utilisateur
			if(!$AUTH->load( $XML->user, $XML->password)) return $AUTH->error;
			
			// Lecture + MAJ des infos de stock
			if(!$this->read_basecli()) return $INFO->erreur;
			
			// Suppression du fichier stock
			//unlink($this->stockfile);

		}
		
		// Retour
		return $INFO->returnxml( "1" );
	}

	function read_basecli() {
		global $INFO, $XML, $LOG;
	}

	function logexec_request($request, $num = 1) {
		global $INFO, $LOG;

		if(!$INFO->Query($request, $num)) {
			$LOG->writeLog($request, "UpdateDb", "DEBUG - KO");
			return false;
		}
		return true;
	}

	/**
     *
     * Enregistrement d'un début de lancement
     * @param string $su_login - Identifiant soapusers en cours
     * @param string $wsr_method - Nom de Methode appelante
     * @return mixed Identifiant ou false en cas d'erreur
     */
    function setReportingStart($su_login, $wsr_method) {
    	global $INFO, $LOG;

        $sql = 'INSERT INTO ws_reporting (su_login, wsr_method, wsr_debut) value (\'' . mysql_real_escape_string($su_login) . '\', \'' . mysql_real_escape_string($wsr_method) . '\', now())';
        $LOG->writeLog($sql, "SetReportingStart", "INFO");
        if ($INFO->Query($sql)) {
            return mysql_insert_id();
        }
        return false;
    }

    /**
     *
     * Enregistrement d'une fin de lancement
     * @param int $id_ws_reporting - Identifiant du lancement en cours
     * @param float $wsr_mt_debut - microtime de début
     * @param string $wsr_commentaire - Commentaire
     * @return boolean
     */
    function setReportingEnd($id_ws_reporting, $wsr_mt_debut, $wsr_commentaire) {
    	global $INFO;

        if (is_numeric($id_ws_reporting) && intval($id_ws_reporting) > 0) {
            $sql = 'UPDATE ws_reporting  set wsr_fin = now(), wsr_execution_time = ' . (microtime(true) - $wsr_mt_debut) . ', wsr_commentaire = \'' . mysql_real_escape_string($wsr_commentaire) . '\' where id_ws_reporting = ' . intval($id_ws_reporting);
            if ($INFO->Query($sql)) {
                return true;
            }
        }
        return false;
    }
}
?>