<?php

	// ---------------------------------
    // Version : 1.0
	// Date : 12/10/2017
    // ---------------------------------
    // CLASS ORDER (UNIT TESTS)
	// WOOCOMMERCE
    // ----------------------------------

	namespace Classes\Wordpress;

	class WooCommerceUtOrder {

		public $id;		

		public $shipping_address;
		public $billing_address;

		function __construct($shipping_address, $billing_address) {

			$this->shipping_address 	= $shipping_address;
			$this->billing_address 		= $billing_address; 

		}

		function __toString() {

			$ret = "<b>Adresse de livraison</b> ".$this->shipping_address;
			return $ret;

		}

	}