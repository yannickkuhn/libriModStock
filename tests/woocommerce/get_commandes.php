<?php

    // ---------------------------------
    // Version : 1.0
    // Date : 10/10/2017
    // ---------------------------------
    // SCRIPT QUI PERMET DE RECUPERER
    // LA LISTE DES COMMANDES SUR
    // LE SITE WORDPRESS VIA
    // LE PLUGIN WOOCOMMERCE
    // ----------------------------------
    // TODO
    // -----
    // > Logs à gérer
    // ----------------------------------

    require_once( 'conf.php' );
    require_once( 'lib/woocommerce-api.php' );

    $options = array(
        'ssl_verify'      => false
    );

    try {
        $client = new WC_API_Client( $store_url, $consumer_key, $consumer_secret, $options );
        $orders = $client->orders->get( null, array( 'status' => 'all' ) );


        // --------------------------------------------------------------------------
        //
        // Récupération d'un produit (via l'id du produit : product->product_id, ou l'EAN)
        //
        // --------------------------------------------------------------------------

        // echo "<pre>";
        // print_r( $client->products->get( '9590' ) );
        // print_r( $client->products->get_by_sku( '9782914480611' ) );
        // echo "</pre><br>";


        // --------------------------------------------------------------------------
        //
        // Récupération d'une commande
        //
        // --------------------------------------------------------------------------

        echo "<pre>";
        foreach ($orders->orders as $order) { 
            if($order->order_number == "13009") {
                echo "<ul>\r\n";
                    echo "<li>".$order->order_number."</li>\r\n";
                    echo "<li>".$order->customer->last_name." ".$order->customer->first_name."</li>\r\n";
                    echo "<li>".$order->status." (complété ? ".$order->completed_at.")</li>\r\n";
                    foreach($order->line_items as $product) {
                        echo "<ul><li>".$product->product_id." (EAN : ".$product->sku.")</li></ul>\r\n";
                        echo "\r\n"."\r\n"."\r\n"."\r\n".serialize($client->products->get( $product->product_id ))."\r\n"."\r\n"."\r\n";
                    }
                    if($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'awaiting-shipment') {
                        echo "<li>Commande à vérifier</li>\r\n";
                        echo "\r\n"."\r\n"."\r\n"."\r\n".serialize($order)."\r\n"."\r\n"."\r\n";
                    }
                echo "</ul>\r\n";
            }
        }
        echo "</pre><br>";

    } catch ( WC_API_Client_Exception $e ) {
        echo $e->getMessage() . PHP_EOL;
        echo $e->getCode() . PHP_EOL;
        if ( $e instanceof WC_API_Client_HTTP_Exception ) {
            print_r( $e->get_request() );
            print_r( $e->get_response() );
        }
    }