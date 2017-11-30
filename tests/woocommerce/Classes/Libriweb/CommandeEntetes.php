<?php

	// ---------------------------------
    // Version : 1.0
	// Date : 10/10/2017
    // ---------------------------------
    // CLASSE COMMANDE_ENTETES
	// LIAISON BASE LIBRIWEB
    // ----------------------------------

	namespace Classes\Libriweb;
	
	use Classes\Common\Entity;

	class CommandeEntetes extends Entity {

		public $cmd_ent_id;		
		public $cmd_ent_montant_hfp;
		public $cmd_ent_montant_fp;
		public $cmd_ent_poids;

		public $cmd_ent_date;
		public $cmd_ent_heure;
		public $cmd_ent_date_statut;

		public $expd_id;

		public $cmd_ent_valid;
		public $statut_id;
		public $cmd_ent_pai_statut;
		public $pai_id;
		public $cmd_ent_download;
		
		// In relation with other entities

		public $cli_id;
		public $cmd_ent_billingPaysId;
		public $cmd_ent_deliveryPaysId;

		// Billing Address

		public $cmd_ent_billingPrenom;
		public $cmd_ent_billingNom;
		public $cmd_ent_billingSociete;
		public $cmd_ent_billingAdresse;
		public $cmd_ent_billingVille;
		public $cmd_ent_billingCodePostal;
		public $cmd_ent_billingTel;

		// Delivery Address

		public $cmd_ent_deliveryPrenom;
		public $cmd_ent_deliveryNom;
		public $cmd_ent_deliverySociete;
		public $cmd_ent_deliveryAdresse;
		public $cmd_ent_deliveryVille;
		public $cmd_ent_deliveryCodePostal;
		public $cmd_ent_deliveryTel;

		function __construct() {

			$this->table_name 	= "commande_entetes";
		}

		function setCliId($cli_id) {
			$this->cli_id 		= $cli_id;
		}

		function setCmdEntBillingPaysId($cmd_ent_billingPaysId) {
			$this->cmd_ent_billingPaysId 		= $cmd_ent_billingPaysId;
		}

		function setCmdEntDeliveryPaysId($cmd_ent_deliveryPaysId) {
			$this->cmd_ent_deliveryPaysId 		= $cmd_ent_deliveryPaysId;
		}

		function getSearchReqById($cmd_ent_id) {
			
			$ret 	= "SELECT ".implode(', ', $this->getKeys())." FROM ".$this->table_name." ";
			$ret   .= "WHERE cmd_ent_id = '".$cmd_ent_id."'";

			return $ret;
		}

		function getUpdateReqStatus($cmd_ent_id, $statut_id = 5) {

			// Cette fonction est surtout utile
			// pour mettre à jour le statut 5 
			// (5: Commande expédiée)

			$ret 	= "UPDATE ".$this->table_name." ";
			$ret   .= "SET statut_id = '".$statut_id."' ";
			$ret   .= "WHERE cmd_ent_id = '".$cmd_ent_id."'";

			return $ret;
		}
	}