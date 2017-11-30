<?php

	// ---------------------------------
    // Version : 1.0
	// Date : 11/10/2017
    // ---------------------------------
    // CLASSE SQL
	// LIAISON BASE LIBRIWEB
    // ----------------------------------

	namespace Classes\Common;
	
	use PDO;

	class AbstractSql {

		public $db_sgbd;
		public $db_name;
		public $db_host;			
		public $db_user;
		public $db_password;
		public $db_options;

		public $pdo;

		function __construct($db_name, $db_host, $db_user, $db_password) {

			$this->db_sgbd		= "mysql";
			$this->db_options 	= [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ];

			$this->db_name 		= $db_name;
			$this->db_host 		= $db_host;
			$this->db_user		= $db_user;
			$this->db_password 	= $db_password;

			try {
				$this->pdo = new PDO(
					$this->db_sgbd .':host='. $this->db_host .';dbname='. $this->db_name,
					$this->db_user,
					$this->db_password,
					$this->db_options);
				return true;
			}
			catch(Exception $e) {
				var_dump($e->getMessage(), E_USER_ERROR);
				return false;
			}			
		}
	}