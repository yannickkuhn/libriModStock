<?php
class order {
	function dlcmd( $data ) {
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
		
		// Entête des commandes
		$CMD    = array();
		$return = "";
		$i 		= 0;
		
		if(!$INFO->Query("SELECT 
								a.cmd_ent_autoid, 
								a.cmd_ent_id, 
								a.cmd_ent_billingCiv, 
								a.cmd_ent_billingPrenom, 
								a.cmd_ent_billingNom, 
								a.cmd_ent_billingSociete, 
								a.cmd_ent_billingAdresse, 
								a.cmd_ent_billingCodePostal, 
								a.cmd_ent_billingVille, 
								a.cmd_ent_billingTel, 
								a.cmd_ent_deliveryCiv, 
								a.cmd_ent_deliveryPrenom, 
								a.cmd_ent_deliveryNom, 
								a.cmd_ent_deliverySociete, 
								a.cmd_ent_deliveryAdresse, 
								a.cmd_ent_deliveryCodePostal, 
								a.cmd_ent_deliveryVille, 
								a.cmd_ent_deliveryTel, 
								a.cmd_ent_date, 
								a.cmd_ent_montant_hfp, 
								a.cmd_ent_montant_fp, 
								a.cmd_ent_pcadeau, 
								a.cmd_ent_commentaire, 
								a.cmd_ent_pai_statut,
								b.cli_autoid, 
								b.cli_mail, 
								b.cli_nom, 
								b.cli_prenom, 
								b.cli_id,
								c.pays_nom AS billingPaysNom, 
								f.pays_nom AS deliveryPaysNom, 
								d.expd_tva, 
								d.expd_libelle, 
								e.pai_libelle 
								FROM 
								commande_entetes AS a 
								LEFT JOIN clients AS b 
								ON a.cli_id = b.cli_id 
								LEFT JOIN pays AS c 
								ON a.cmd_ent_billingPaysId = c.pays_id 
								LEFT JOIN pays AS f 
								ON a.cmd_ent_deliveryPaysId = f.pays_id 
								LEFT JOIN expedition AS d 
								ON a.expd_id = d.expd_id 
								LEFT JOIN paiement AS e 
								ON a.pai_id = e.pai_id 
								WHERE 
								a.cmd_ent_valid = \"1\" 
								AND 
								a.statut_id <> \"98\" 
								AND 
								a.statut_id <> \"99\" 
								AND 
								a.statut_id <> \"6\" 
								AND 
								a.cmd_ent_pai_statut <> \"9\" 
								AND 
								a.cmd_ent_download = \"0\"")) return $INFO->erreur;
		
		$nbresult = $INFO->Num();
		
		while($ROW = $INFO->Result()) {
			$CMD[$i]["autoid"]    	  	   = urldecode($ROW["cmd_ent_autoid"]);
			$CMD[$i]["idcommande"]    	   = urldecode($ROW["cmd_ent_id"]);
			$CMD[$i]["idclientweb"]    	   = urldecode($ROW["cli_autoid"]);
			$CMD[$i]["customerid"]		= urldecode($ROW["cli_id"]);
			$CMD[$i]["customerCivilite"]   = $INFO->FUNC->civilite($ROW["cli_civ"]);
			$CMD[$i]["customerPrenom"]     = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cli_prenom"]));
			$CMD[$i]["customerNom"]        = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cli_nom"]));
			$CMD[$i]["billingCivilite"]    = $INFO->FUNC->civilite($ROW["cmd_ent_billingCiv"]);
			$CMD[$i]["billingPrenom"]      = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_billingPrenom"]));
			$CMD[$i]["billingNom"]         = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_billingNom"]));
			$CMD[$i]["billingSociete"]     = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_billingSociete"]));
			$CMD[$i]["billingAdresse"]     = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_billingAdresse"]));
			$CMD[$i]["billingVille"]       = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_billingVille"]));
			$CMD[$i]["billingCodePostal"]  = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_billingCodePostal"]));
			$CMD[$i]["billingTel"]         = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_billingTel"]));
			$CMD[$i]["billingPays"]        = urldecode($ROW["billingPaysNom"]);
			$CMD[$i]["deliveryCivilite"]   = $INFO->FUNC->civilite($ROW["cmd_ent_deliveryCiv"]);
			$CMD[$i]["deliveryPrenom"]     = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_deliveryPrenom"]));
			$CMD[$i]["deliveryNom"]        = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_deliveryNom"]));
			$CMD[$i]["deliverySociete"]    = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_deliverySociete"]));
			$CMD[$i]["deliveryAdresse"]    = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_deliveryAdresse"]));
			$CMD[$i]["deliveryVille"]      = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_deliveryVille"]));
			$CMD[$i]["deliveryCodePostal"] = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_deliveryCodePostal"]));
			$CMD[$i]["deliveryTel"]        = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_ent_deliveryTel"]));
			$CMD[$i]["deliveryPays"]       = urldecode($ROW["deliveryPaysNom"]);
			$CMD[$i]["date"]     	  	   = $INFO->FUNC->nodate($ROW["cmd_ent_date"]);
			$CMD[$i]["montant"]       	   = urldecode($ROW["cmd_ent_montant_hfp"]);
			$CMD[$i]["fp"]            	   = urldecode($ROW["cmd_ent_montant_fp"]);
			$CMD[$i]["fp_tva"]        	   = urldecode($ROW["expd_tva"]);
			$CMD[$i]["commentaire"]        = urldecode($ROW["cmd_ent_commentaire"]);
			$CMD[$i]["pcadeau"]       	   = urldecode($ROW["cmd_ent_pcadeau"]);
			$CMD[$i]["expedition"]    	   = urldecode($ROW["expd_libelle"]);
			$CMD[$i]["paiement"]      	   = urldecode($ROW["pai_libelle"]);
			$CMD[$i]["etat_paiement"] 	   = urldecode($ROW["cmd_ent_pai_statut"]);
			$CMD[$i]["email"] 		 	   = urldecode($ROW["cli_mail"]);
			
			$i++;
		}
		
		// Mise en forme des résultats
		$return .= "<DATA count=\"".$nbresult."\">\n";
		
		while(list($key, $val) = each($CMD)) {
			// Mise en forme des résultats
			$return .= "<COMMANDE>\n";
			
			// ENTETE commande
			$return .= "<ENTETE>\n";
			//$return .= "<IDCOMMANDE>".str_pad($val["idcommande"], 11, 0, STR_PAD_LEFT)."</IDCOMMANDE>\n";
			$return .= "<IDCOMMANDE>".$val["idcommande"]."</IDCOMMANDE>\n";
			$return .= "<CLIENTID>".$val["customerid"]."</CLIENTID>\n";
			$return .= "<IDCLIENTWEB>".$val["idclientweb"]."</IDCLIENTWEB>\n";
			$return .= "<CLIENTCIVILITE>".$val["customerCivilite"]."</CLIENTCIVILITE>\n";
			$return .= "<CLIENTNOM>".$val["customerNom"]."</CLIENTNOM>\n";
			$return .= "<CLIENTPRENOM>".$val["customerPrenom"]."</CLIENTPRENOM>\n";
			$return .= "<FACTURECIVILITE>".$val["billingCivilite"]."</FACTURECIVILITE>\n";
			$return .= "<FACTURENOM>".$val["billingNom"]."</FACTURENOM>\n";
			$return .= "<FACTUREPRENOM>".$val["billingPrenom"]."</FACTUREPRENOM>\n";
			$return .= "<FACTURESOCIETE>".$val["billingSociete"]."</FACTURESOCIETE>\n";
			$return .= "<FACTUREADRESSE>".$val["billingAdresse"]."</FACTUREADRESSE>\n";
			$return .= "<FACTUREVILLE>".$val["billingVille"]."</FACTUREVILLE>\n";
			$return .= "<FACTURECP>".$val["billingCodePostal"]."</FACTURECP>\n";
			$return .= "<FACTUREPAYS>".$val["billingPays"]."</FACTUREPAYS>\n";
			$return .= "<FACTURETEL>".$val["billingTel"]."</FACTURETEL>\n";
			$return .= "<LIVRAISONCIVILITE>".$val["deliveryCivilite"]."</LIVRAISONCIVILITE>\n";
			$return .= "<LIVRAISONNOM>".$val["deliveryNom"]."</LIVRAISONNOM>\n";
			$return .= "<LIVRAISONPRENOM>".$val["deliveryPrenom"]."</LIVRAISONPRENOM>\n";
			$return .= "<LIVRAISONSOCIETE>".$val["deliverySociete"]."</LIVRAISONSOCIETE>\n";
			$return .= "<LIVRAISONADRESSE>".$val["deliveryAdresse"]."</LIVRAISONADRESSE>\n";
			$return .= "<LIVRAISONVILLE>".$val["deliveryVille"]."</LIVRAISONVILLE>\n";
			$return .= "<LIVRAISONCP>".$val["deliveryCodePostal"]."</LIVRAISONCP>\n";
			$return .= "<LIVRAISONPAYS>".$val["deliveryPays"]."</LIVRAISONPAYS>\n";
			$return .= "<LIVRAISONTEL>".$val["deliveryTel"]."</LIVRAISONTEL>\n";
			$return .= "<DATECMD>".$val["date"]."</DATECMD>\n";
			$return .= "<MONTANT>".$val["montant"]."</MONTANT>\n";
			$return .= "<FRAISPORT tva=\"".$val["fp_tva"]."\">".$val["fp"]."</FRAISPORT>\n";
			$return .= "<COMMENTAIRE>".$val["commentaire"]."</COMMENTAIRE>\n";
			$return .= "<PCADEAU>".$val["pcadeau"]."</PCADEAU>\n";
			$return .= "<MEXPEDITION>".$val["expedition"]."</MEXPEDITION>\n";
			$return .= "<MPAIEMENT>".$val["paiement"]."</MPAIEMENT>\n";
			$return .= "<STATPAIEMENT>".$val["etat_paiement"]."</STATPAIEMENT>\n";
			$return .= "<EMAIL>".$val["email"]."</EMAIL>\n";
			$return .= "</ENTETE>\n";
			
			// Mise en forme des résultats
			$return .= "<ARTICLES>\n";
			
			// LIGNES commande
			if(!$INFO->Query("SELECT 
									cmd_lig_ean, 
									cmd_lig_qte, 
									cmd_lig_remise, 
									cmd_lig_titre, 
									cmd_lig_prixttc, 
									cmd_lig_promo, 
									cmd_lig_prixpromo, 
									cmd_lig_tva,
									cmd_lig_isNum
									FROM 
									commande_lignes 
									WHERE 
									cmd_ent_autoid = \"".$val["autoid"]."\"")) return $INFO->erreur;
			while($ROW = $INFO->Result()) {
				$ean      = urldecode($ROW["cmd_lig_ean"]);
				$qte      = urldecode($ROW["cmd_lig_qte"]);
				$titre    = $INFO->FUNC->replace_wrong_charxml(urldecode($ROW["cmd_lig_titre"]));
				$prix_ttc = urldecode($ROW["cmd_lig_prixttc"]);
				$tva      = urldecode($ROW["cmd_lig_tva"]);
				$typeprod = urldecode($ROW["cmd_lig_isNum"]);
				
				$PRIX = $INFO->FUNC->format_decimal($INFO->FUNC->return_remise($prix_ttc, $ROW["cmd_lig_remise"], true, $ROW["cmd_lig_promo"], $ROW["cmd_lig_prixpromo"]));
				
				$return .= "<LIGNE>\n";
				$return .= "<EAN>".$ean."</EAN>";
				$return .= "<QTE>".$qte."</QTE>";
				$return .= "<TITRE>".$titre."</TITRE>";
				$return .= "<PRIXTTC>".$PRIX."</PRIXTTC>";
				$return .= "<TVA>".$tva."</TVA>";
				$return .= "<TYPE>".$typeprod."</TYPE>";
				$return .= "</LIGNE>\n";
			}
			
			// Mise en forme des résultats
			$return .= "</ARTICLES>\n";
			$return .= "</COMMANDE>\n";
		}
		
		$return .= "</DATA>\n";
		
		// Déconnexion MySQL
		$INFO->Close();
		
		// Envoi des résultats
		return $INFO->returnxml( "1", false, false, $return );
	}

	function edstatut( $data ) {
		global $AUTH, $XML, $INFO;
		
		/*
		// RETOUR //
		-1 : Erreur
		 1 : Statut mis à jour
		*/
		
		// Lecture des données XML
		if(!$XML->read( $INFO->decode($data) )) return $INFO->returnxml( "-1", $XML->erreur );
		
		// Authentification de l'utilisateur
		if(!$AUTH->load( $XML->user, $XML->password )) return $AUTH->error;
		
		// Vérification des paramètres
		if(empty($XML->data["ID"]) or empty($XML->data["IDLIBRI"]) or empty($XML->data["IDSTATUT"])) {
			return $INFO->returnxml( "-1", "DONNEES: Les champs \"ID\", \"IDLIBRI\" et \"IDSTATUT\" doivent Ãªtre renseignÃ©s" );
		}
		
		// Connexion MySQL
		if(!$INFO->Connect()) return $INFO->erreur;
		
		if(!$INFO->Query("SELECT 
								cmd_ent_id_libri, 
								statut_id 
								FROM 
								commande_entetes 
								WHERE 
								cmd_ent_id = \"".trim($XML->data["ID"])."\"")) return $INFO->erreur;
		$NB  = $INFO->Num();
		$ROW = $INFO->Result();
		
		// Déconnexion MySQL
		$INFO->Close();
		
		// MAJ de la commande
		if($NB > 0) {
			// Connexion MySQL
			if(!$INFO->Connect()) return $INFO->erreur;
			
			if(empty($ROW["cmd_ent_id_libri"])) {
				if($ROW["statut_id"] == "1") {
					if(!$INFO->Query("UPDATE 
											commande_entetes 
											SET 
											cmd_ent_id_libri = \"".$XML->data["IDLIBRI"]."\", 
											cmd_ent_download = \"1\", 
											statut_id = \"".$XML->data["IDSTATUT"]."\", 
											cmd_ent_date_statut = \"".date("Y-m-d")."\" 
											WHERE 
											cmd_ent_id = \"".trim($XML->data["ID"])."\"")) return $INFO->erreur;
				}
				else {
					if(!$INFO->Query("UPDATE 
											commande_entetes 
											SET 
											cmd_ent_id_libri = \"".$XML->data["IDLIBRI"]."\", 
											cmd_ent_download = \"1\" 
											WHERE 
											cmd_ent_id = \"".trim($XML->data["ID"])."\"")) return $INFO->erreur;
				}
			}
			else {
				if($ROW["statut_id"] == "1") {
					if(!$INFO->Query("UPDATE 
											commande_entetes 
											SET 
											statut_id = \"".$XML->data["IDSTATUT"]."\", 
											cmd_ent_download = \"1\", 
											cmd_ent_date_statut = \"".date("Y-m-d")."\" 
											WHERE 
											cmd_ent_id = \"".trim($XML->data["ID"])."\" 
											AND 
											cmd_ent_id_libri = \"".$XML->data["IDLIBRI"]."\"")) return $INFO->erreur;
				}
				else {
					if(!$INFO->Query("UPDATE 
											commande_entetes 
											SET 
											cmd_ent_download = \"1\" 
											WHERE 
											cmd_ent_id = \"".trim($XML->data["ID"])."\" 
											AND 
											cmd_ent_id_libri = \"".$XML->data["IDLIBRI"]."\"")) return $INFO->erreur;
				}
			}
			
			// Déconnexion MySQL
			$INFO->Close();
		}
		
		// Retour
		return $INFO->returnxml( "1" );
	}
}
?>