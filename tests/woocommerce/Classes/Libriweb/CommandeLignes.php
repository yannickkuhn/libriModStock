<?php

	// ---------------------------------
    // Version : 1.0
	// Date : 10/10/2017
    // ---------------------------------
    // CLASSE COMMANDE_LIGNES
	// LIAISON BASE LIBRIWEB
    // ----------------------------------

	namespace Classes\Libriweb;
	
	use Classes\Common\Entity;

	class CommandeLignes extends Entity {

		public $cmd_ent_autoid;			
		public $cmd_lig_qte;
		public $cmd_lig_prixttc;
		public $cmd_lig_tva;
		public $cmd_lig_ean;

		public $cmd_lig_titre;
		public $cmd_lig_auteur;
		public $cmd_lig_editeur;

		public $cmd_lig_glndistrib;
		public $cmd_lig_stock;

		function __construct($line, $order_id) {

			$this->table_name 			= "commande_lignes";
			
			$this->cmd_ent_autoid 		= $order_id;

			$this->cmd_lig_qte 			= $line->quantity;
			$this->cmd_lig_prixttc 		= $line->price;
			$this->cmd_lig_tva 			= $line->tax_class == "reduit" || $line->tax_class == "réduit") ? "5.50" : "20.00";
			$this->cmd_lig_ean 			= $line->sku;

			$this->cmd_lig_titre		= "";
			$this->cmd_lig_auteur		= "";
			$this->cmd_lig_editeur  	= "";

			$this->cmd_lig_glndistrib	= 0;
			$this->cmd_lig_stock		= 0;

		}

		function getInsertReq() {

			$ret 	= "INSERT INTO ".$this->table_name." ";
			$ret   .= "( ";
				$ret   .= "cmd_ent_autoid, ";

				$ret   .= "cmd_lig_qte, ";
				$ret   .= "cmd_lig_prixttc, ";
				$ret   .= "cmd_lig_tva, ";
				$ret   .= "cmd_lig_ean, ";

				$ret   .= "cmd_lig_titre, ";
				$ret   .= "cmd_lig_auteur, ";
				$ret   .= "cmd_lig_editeur, ";
				$ret   .= "cmd_lig_glndistrib, ";
				$ret   .= "cmd_lig_stock ";

			$ret   .= ") VALUES ( ";

				$ret   .= "'".$this->cmd_ent_autoid."', ";
				
				$ret   .= "'".$this->cmd_lig_qte."', ";
				$ret   .= "'".$this->cmd_lig_prixttc."', ";
				$ret   .= "'".$this->cmd_lig_tva."', ";
				$ret   .= "'".$this->cmd_lig_ean."', ";

				$ret   .= "'".$this->cmd_lig_titre."', ";
				$ret   .= "'".$this->cmd_lig_auteur."', ";
				$ret   .= "'".$this->cmd_lig_editeur."', ";
				$ret   .= "'".$this->cmd_lig_glndistrib."', ";
				$ret   .= "'".$this->cmd_lig_stock."' ";

			$ret   .= "); ";

			return $ret;
		}
	}