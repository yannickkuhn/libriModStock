<?php

	// ---------------------------------
    // Version : 1.0
	// Date : 12/10/2017
    // ---------------------------------
    // CLASS ADDRESS (UNIT TESTS)
	// WOOCOMMERCE
    // ----------------------------------

	namespace Classes\Wordpress;

	class WooCommerceUtAddress {

		public $id;		

		public $first_name;
		public $last_name;
		public $compagny;
		public $address_1;
		public $address_2;
		public $city;
		public $postcode;
		public $phone;

		public $type;		// 'B' : billingAddress, 'S' : shippingAddress

		function __construct($first_name, $last_name, $compagny, $address_1, $address_2, $postcode, $city, $phone, $type = 'B') {

			$this->first_name	= $first_name;
			$this->last_name	= $last_name;
			$this->compagny		= $compagny;
			$this->address_1	= $address_1;
			$this->address_2	= $address_2;
			$this->city			= $city;
			$this->postcode		= $postcode;
			$this->phone		= $phone;
			if($type == 'B' || $type == 'S')
				$this->type 	= $type;
		}

		function __toString() {
			$ret = "<b>(type ".$this->type.")</b> : ";
			$ret .= $this->first_name." ".$this->last_name." - ".$this->compagny." - ".$this->address_1." ".$this->address_2." ".$this->postcode." ".$this->city." - ".$this->phone;
			$ret .= "<br/>";
			return $ret;
		}

	}