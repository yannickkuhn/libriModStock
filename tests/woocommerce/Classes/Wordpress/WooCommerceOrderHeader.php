<?php

	// ---------------------------------
    // Version : 1.0
	// Date : 11/10/2017
    // ---------------------------------
    // CLASSE WORDPRESS_ORDER_HEADER
	// LIAISON BASE LIBRIWEB
	// ---------
	// TODO
	// ---------
	// > Selection du client
	// > Selection des pays
	// > Selection du mode d'expedition
    // ----------------------------------

	namespace Classes\Wordpress;
	
	use Classes\Libriweb\CommandeEntetes;

	class WooCommerceOrderHeader extends CommandeEntetes {

		function __construct($order) {

			parent::__construct();

			if(is_object($order)) {

				$this->cmd_ent_id 					= $order->order_number;
				$this->cmd_ent_montant_hfp 			= $order->subtotal + $order->total_tax;
				$this->cmd_ent_montant_fp 			= $order->total_shipping;
				$this->cmd_ent_poids				= 0;												// POIDS A DEFINIR

				$this->cmd_ent_date 				= date("Y-m-d", strtotime($order->created_at));
				$this->cmd_ent_heure 				= date("H:i:s", strtotime($order->created_at));
				$this->cmd_ent_date_statut 			= date("Y-m-d", strtotime($order->created_at));

				$this->expd_id 						= $this->setExpeditionId($order->shipping_lines[0]->method_title);

				$this->cmd_ent_valid				= 1;
				$this->statut_id 					= 1;
				$this->cmd_ent_pai_statut 			= 1;
				$this->pai_id 						= 1;
				$this->cmd_ent_download 			= 0;

				$this->cmd_ent_billingPrenom 		= $order->billing_address->first_name;
				$this->cmd_ent_billingNom 			= $order->billing_address->last_name;
				$this->cmd_ent_billingSociete 		= $order->billing_address->company;
				$this->cmd_ent_billingAdresse 		= $order->billing_address->address_1." ".$order->billing_address->address_2;
				$this->cmd_ent_billingVille 		= $order->billing_address->city;
				$this->cmd_ent_billingCodePostal 	= $order->billing_address->postcode;
				$this->cmd_ent_billingTel 			= $order->billing_address->phone;

				$this->cmd_ent_deliveryPrenom 		= $order->shipping_address->first_name;
				$this->cmd_ent_deliveryNom 			= $order->shipping_address->last_name;
				$this->cmd_ent_deliverySociete 		= $order->shipping_address->company;
				$this->cmd_ent_deliveryAdresse 		= $order->shipping_address->address_1." ".$order->shipping_address->address_2;
				$this->cmd_ent_deliveryVille 		= $order->shipping_address->city;
				$this->cmd_ent_deliveryCodePostal 	= $order->shipping_address->postcode;

				if(isset($order->shipping_address->phone))
					$this->cmd_ent_deliveryTel 			= $order->shipping_address->phone;

			}

		}

		function setWeight($)

		function setExpeditionId($expeditionTitle) {

			// TODO : Il faut faire une liaison avec une bdd (libellé - n° identifiant libriweb d'expd)

			switch($expeditionTitle) {
				case "Colissimo":
					return 1;
					break;
				case "Chronopost":
					return 2;
					break;
				case "Mondial Relay":
					return 3;
					break;
				case "Relais Colis":
					return 4;
					break;
				default:
					return 0;
					break;
			}

		}
	
	}