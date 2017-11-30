<?php

	// ---------------------------------
    // Version : 1.0
	// Date : 10/10/2017
    // ---------------------------------
    // CLASSE CLIENTS
	// LIAISON BASE LIBRIWEB
    // ----------------------------------

	namespace Classes\Libriweb;
	
	use Classes\Common\Entity;

	class Clients extends Entity {

		public $cli_prenom;			
		public $cli_nom;
		public $cli_mail;
		public $cli_date_inscr;

		function __construct($order) {

			$this->table_name 			= "clients";
			
			$this->cli_prenom 			= $order->billing_address->first_name;
			$this->cli_nom 				= $order->billing_address->last_name;
			$this->cli_mail 			= $order->billing_address->email;
			$this->cli_date_inscr 		= date("Y-m-d");
		}

		function getInsertReq() {

			$ret 	= "INSERT INTO ".$this->table_name." ";
			$ret   .= "( ";
				$ret   .= "cli_prenom, ";
				$ret   .= "cli_nom, ";
				$ret   .= "cli_mail, ";
				$ret   .= "cli_date_inscr ";
			$ret   .= ") VALUES ( ";
				$ret   .= "'".$this->cli_prenom."', ";
				$ret   .= "'".$this->cli_nom."', ";
				$ret   .= "'".$this->cli_mail."', ";
				$ret   .= "'".$this->cli_date_inscr."', ";
			$ret   .= "); ";

			return $ret;
		}

		function getSearchReqByCliMail($cli_mail) {
			$ret 	= "SELECT * FROM ".$this->table_name." ";
			$ret   .= "WHERE cli_mail = '".$cli_mail."'";

			return $ret;
		}
	}