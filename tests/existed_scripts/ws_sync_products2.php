<?php
//ini_set("display_errors", 1);
//error_reporting(-1);
set_time_limit(0);

$hostname_maconnexion = "localhost";
$database_maconnexion = "librair2_libriweb";
$username_maconnexion = "librair2_2dcom";
$password_maconnexion = "n96SR79cQ";

$consumer_key = 'ck_5c287f62388e2d18f6834fb8405f91289ffa3caa'; 
$consumer_secret = 'cs_4cdb9f1cdecc1336fb35571bf2d4104ffd454012'; 
$store_url = 'http://www.librairiezenobi.com/'; 

$template = (object)array
                (
                    'title' => "",
                    'id' => "",
                    'created_at' => "",
                    'updated_at' => "",
                    'type' => "product",
                    'status' => "publish",
                    'downloadable' => false,
                    'virtual' => false,
                    'permalink' => "",
                    'sku' => "",
                    'price' => "",
                    'regular_price' => "",
                    'sale_price' => "",
                    'price_html' => "",
                    'taxable' => true,
                    'tax_status' => "taxable",
                    'tax_class' => "",
                    'managing_stock' => true,
                    'stock_quantity' => null,
                    'in_stock' => true,
                    'backorders_allowed' => false,
                    'backordered' => "no",
                    'sold_individually' => false,
                    'purchaseable' => true,
                    'featured' => false,
                    'visible' => true,
                    'catalog_visibility' => "",
                    'on_sale' => "",
                    'product_url' => "",
                    'button_text' => "",
                    'weight' => "",
                    'dimensions' => (object)array
                        (
                            'length' => "",
                            'width' => "",
                            'height' => "",
                        ),
                    'shipping_required' => "",
                    'shipping_taxable' => "",
                    'shipping_class' => "",
                    'shipping_class_id' => "",
                    'description' => "",
                    'short_description' => "",
					'reviews_allowed' => "",
                    'average_rating' => "",
                    'rating_count' => "",
                    'related_ids' => array
                        (
                            '0' => "",
                            '1' => "",
                            '2' => "",
                        ),
                    'upsell_ids' => array
                        (
                        ),
                    'cross_sell_ids' => array
                        (
                        ),
                    'parent_id' => "",
                    'categories' => array
                        (
                            '0' => "",
                        ),
                    'tags' => array
                        (
                        ),
                    'images' => array
                        (
                            '0' => (object)array
                                (
                                    'id' => "",
                                    'created_at' => "",
                                    'updated_at' => "",
                                    'src' => "",
                                    'title' => "",
                                    'alt' => "",
                                    'position' => "",
                                )

                        ),
                    'featured_src' => "",
                    'attributes' => array
                        (
                        ),
                    'downloads' => array
                        (
                        ),
                    'download_limit' => "",
                    'download_expiry' => "",
                    'download_type' => "",
                    'purchase_note' => "",
                    'total_sales' => "",
                    'variations' => array
                        (
                        ),
                    'parent' => array
                        (
                        )
                );

$categ = array (
				"0"   => "118",
                "110" => "152",
                "115" => "153",
                "120" => "154",
				"125" => "210",
                "130" => "155",
                "140" => "156",
                "150" => "157",
                "160" => "158",
                "170" => "159",
                "180" => "160",
				"185" => "185",
                "190" => "161",
                "200" => "162",
                "210" => "163",
                "220" => "164",
                "230" => "165",
                "240" => "166",
                "250" => "167",
                "300" => "169",
                "310" => "170",
				"315" => "186",
                "320" => "171",
                "330" => "172",
                "340" => "173",
                "400" => "174",
                "410" => "175",
                "420" => "176",
                "500" => "178",
                "510" => "179",
                );
				
require_once( 'lib/woocommerce-api.php' );
 
$options = array(
    'ssl_verify'      => false
);

try {
	
	$client = new WC_API_Client( $store_url, $consumer_key, $consumer_secret, $options );
	
	$maconnexion = mysqli_connect($hostname_maconnexion, $username_maconnexion, $password_maconnexion, $database_maconnexion) or trigger_error(mysqli_error(),E_USER_ERROR);
	
	/*
	$products = $client->products->get('',array( 'filter[limit]' => 1000 ));
	echo "<pre>";
	print_r($products);
	echo "</pre><br>";
	*/
	
	$query_Recordset = "SELECT p.prod_id,p.rub_1_id,p.rub_2_id,p.prod_etat_id,p.prod_occasion,p.prod_numerique,p.prod_lie,p.prod_ean,p.prod_physical_ean,p.prod_prixht,p.prod_prixttc,p.prod_prixpromo,p.prod_tva1,p.prod_tva2,p.prod_titre,p.prod_soustitre,p.prod_auteurs,p.prod_editeur,p.prod_collection,p.prod_genre,p.prod_parution,p.prod_resume,p.prod_desc_promotional,p.prod_description,p.prod_desc_cover,p.prod_namedistrib,p.prod_glndistrib,p.prod_presentcc,p.prod_zone_1,p.prod_support,p.prod_nbpage,p.prod_poids,p.prod_epaisseur,p.prod_largeur,p.prod_hauteur,p.prod_public,p.prod_lang,p.prod_protection,p.prod_constraint_type,p.prod_constraint_status,p.prod_constraint_quantity,p.prod_constraint_unit,p.prod_keys,p.prod_dist_bl,p.prod_promo,p.prod_dispo,p.prod_validate,p.prod_ban,p.prod_deleted,p.prod_date_ajout,p.prod_heure_ajout,p.prod_user_ajout,p.prod_date_mod,p.prod_heure_mod,p.prod_user_mod,p.prod_distsend,p.prod_meta_titre,p.prod_meta_description,p.prod_type_prix,pa.prod_assoc_id,pa.prod_etat_id,pa.prod_assoc_etat,pa.prod_assoc_ean,pa.prod_assoc_prixht,pa.prod_assoc_prixttc,pa.prod_assoc_date_ajout,pa.prod_assoc_heure_ajout,pa.prod_assoc_date_mod,pa.prod_assoc_heure_mod,pa.prod_assoc_parution,pa.prod_assoc_dispo,pa.prod_assoc_titre,pa.prod_assoc_auteurs,pa.prod_assoc_editeur,pa.prod_assoc_collection,pa.prod_assoc_rub_per_id,pa.prod_assoc_support,pa.prod_assoc_poids,s.stock_ean,s.stock_qte,r.rub_libri_id FROM produits AS p LEFT JOIN produit_associations AS pa ON pa.prod_ean = p.prod_ean LEFT JOIN stock AS s ON s.stock_ean = p.prod_ean LEFT JOIN rubriques AS r ON r.rub_id = p.rub_1_id WHERE rub_libri_id != '700' AND rub_libri_id != '600'";
	$Recordset = $maconnexion->query($query_Recordset);
	
	while ($row=mysqli_fetch_array($Recordset)) {
		//$filter_sku = array('filter[sku]' => $row['prod_ean']); 
		echo $row['prod_ean'];
		$price = $row['prod_prixttc'];
		$stock = $row['stock_qte'];
		$rubrique = '118';
		$tva = "réduit";
		if($row['prod_tva1'] == '20.00')
			$tva = "Standard";
		if(!empty($row['rub_libri_id'])) 
			$rubrique = $categ[$row['rub_libri_id']];
		if ($row['prod_occasion'] == 1) { 
			$price = $row['prod_assoc_prixttc'];
			$stock = $row['prod_assoc_dispo'];
		}
		$description = (file_get_contents('http://bddi.2dcom.fr/GetResume.php?user=wsbddi&pw=ErG2i8Aj&ean=' . $row['prod_ean']) != 'erreur resume non trouve') ? file_get_contents('http://bddi.2dcom.fr/GetResume.php?user=wsbddi&pw=ErG2i8Aj&ean=' . $row['prod_ean']) : '';
		$description = iconv("ISO-8859-1", "UTF-8", $description);
		
		$product = $client->products->get( '', array('filter[sku]' => $row['prod_ean']) );
		if (isset($product->products) && is_array($product->products) && ! empty($product->products)) {
			// Mise à jour Produit
			
			$data = array (
				'title' 			=> $row['prod_titre'],
				'updated_at' 		=> date('Y-m-d\TH:i:s'),
				'sku' 				=> $row['prod_ean'],
				'price' 			=> $price,
				'regular_price' 	=> $price,
				'tax_class' 		=> $tva,
				'stock_quantity' 	=> $stock,
				'weight' 			=> $row['prod_poids'],
				'dimensions' 		=> array (
												'length' => $row['prod_epaisseur'],
												'width' => $row['prod_largeur'],
												'height' => $row['prod_hauteur'],
										),
				'description' 		=> $description,
				'categories' 		=> array ( '0' => $rubrique ),
				'downloadable' 		=> false,
                'virtual' 			=> false,
				'managing_stock' 	=> true,
                'in_stock' 			=> true,
				'purchaseable' 		=> true,	
			);
			
			echo " update<br>";
			
			/*
			echo "<pre>";
			print_r($data);
			echo "</pre><br>";	
			*/
				
			$update_product = $client->products->update( $product->products[0]->id, $data );
		} else {
			// Création Produit
			// Image :
			// 			isize = full, large, medium, thumb
			//			gencod / key = identifiants Distrimage
			$data = array (
				'title' 			=> $row['prod_titre'],
				'created_at' 		=> date('Y-m-d\TH:i:s'),
				'sku' 				=> $row['prod_ean'],
				'price' 			=> $price,
				'regular_price' 	=> $price,
				'tax_class' 		=> $tva,
				'stock_quantity' 	=> $stock,
				'weight' 			=> $row['prod_poids'],
				'dimensions' 		=> array (
												'length' => $row['prod_epaisseur'],
												'width' => $row['prod_largeur'],
												'height' => $row['prod_hauteur'],
										),
				'description' 		=> $description,
				'categories' 		=> array ( '0' => $rubrique ),
				'images' 		=> array (
												'0' => array( 'src' => 'http://bddi.2dcom.fr/LocalImageExists.php?ean='.$row['prod_ean'].'&isize=full&gencod=3025594728601&key=mZfH7ltnWECPwoED', 'id' => '0', 'position' => '0' ),
										),
				'downloadable' 		=> false,
                'virtual' 			=> false,
				'managing_stock' 	=> true,
                'in_stock' 			=> true,
				'purchaseable' 		=> true,	
			);
			
			echo " create<br>";
			$create_product = $client->products->create( $data );
		}
	}
	
	mysqli_close($maconnexion);

} catch ( WC_API_Client_Exception $e ) {

    echo $e->getMessage() . PHP_EOL;
    echo $e->getCode() . PHP_EOL;

    if ( $e instanceof WC_API_Client_HTTP_Exception ) {

        print_r( $e->get_request() );
        print_r( $e->get_response() );
    }
}
?>