<?php

	// ---------------------------------
    // Version : 1.0
	// Date : 11/10/2017
    // ---------------------------------
    // CLASSE ENTITY
	// LIAISON BASE LIBRIWEB
    // ----------------------------------

	namespace Classes\Common;

	class Entity {

		public $table_name;

		function __construct() {
		}

		function getKeys() {
			$keys = array();
			foreach($this as $key => $val) {
				if($key != "table_name") {
					$keys[] = $key;
				}
			}
			return $keys;
		}

		function getVals() {
			$vals = array();
			foreach($this as $key => $val) {
				if($key != "table_name") {
					$vals[] = "'".$val."'";
				}
			}
			return $vals;
		}

		function getInsertReq() {
			$ret 	= "INSERT INTO ".$this->table_name." ";
			$ret   .= "( ";
			$ret   .= implode(', ', $this->getKeys());
			$ret   .= ") VALUES ( ";
			$ret   .= implode(', ', $this->getVals());
			$ret   .= "); ";
			return $ret;
		}

	}