<?php

	// ---------------------------------
    // Version : 1.0
	// Date : 10/10/2017
    // ---------------------------------
    // VARIABLES GLOBALES
	// DU PROJET
    // ----------------------------------

	$db_name			= 'zenobi';
	$db_host			= 'localhost';			
	$db_user			= 'root';
	$db_password		= '';

	$consumer_key 		= 'ck_5c287f62388e2d18f6834fb8405f91289ffa3caa'; 
	$consumer_secret 	= 'cs_4cdb9f1cdecc1336fb35571bf2d4104ffd454012'; 
	$store_url 			= 'http://www.librairiezenobi.com/'; 


	// Libelle WooCommerce => Identifiant Libriweb
	$expd_corres		= [
							"Colissimo"		=> 1,
							"Chronopost"	=> 2,
							"Mondial Relay" => 3,
							"Relais Colis"	=> 4,
						];

	// Objet test (sérialisé)
	$order_test_serial	= 'O:8:"stdClass":30:{s:2:"id";i:13009;s:12:"order_number";s:5:"13009";s:10:"created_at";s:20:"2017-10-12T06:00:00Z";s:10:"updated_at";s:20:"2017-10-12T09:49:06Z";s:12:"completed_at";s:20:"1970-01-01T00:00:00Z";s:6:"status";s:17:"awaiting-shipment";s:8:"currency";s:3:"EUR";s:5:"total";s:5:"80.45";s:8:"subtotal";s:5:"66.82";s:25:"total_line_items_quantity";i:3;s:9:"total_tax";s:4:"5.34";s:14:"total_shipping";s:4:"8.29";s:8:"cart_tax";s:4:"3.68";s:12:"shipping_tax";s:4:"1.66";s:14:"total_discount";s:4:"0.00";s:16:"shipping_methods";s:9:"Colissimo";s:15:"payment_details";O:8:"stdClass":3:{s:9:"method_id";s:12:"systempaystd";s:12:"method_title";s:27:"Paiement par carte bancaire";s:4:"paid";b:1;}s:15:"billing_address";O:8:"stdClass":11:{s:10:"first_name";s:4:"Luce";s:9:"last_name";s:6:"NADEAU";s:7:"company";s:0:"";s:9:"address_1";s:28:"4 rue du Lt-Colonel Vendôme";s:9:"address_2";s:0:"";s:4:"city";s:7:"GAILLAC";s:5:"state";s:0:"";s:8:"postcode";s:5:"81600";s:7:"country";s:2:"FR";s:5:"email";s:18:"luce.nadeau@me.com";s:5:"phone";s:10:"0623384731";}s:16:"shipping_address";O:8:"stdClass":9:{s:10:"first_name";s:4:"Luce";s:9:"last_name";s:6:"NADEAU";s:7:"company";s:0:"";s:9:"address_1";s:28:"4 rue du Lt-Colonel Vendôme";s:9:"address_2";s:0:"";s:4:"city";s:7:"GAILLAC";s:5:"state";s:0:"";s:8:"postcode";s:5:"81600";s:7:"country";s:2:"FR";}s:4:"note";s:61:"Ma première commande chez Zenobi! Le mot de Cambrone Sawsan!";s:11:"customer_ip";s:14:"92.149.221.112";s:19:"customer_user_agent";s:82:"mozilla/5.0 (macintosh; intel mac os x 10.11; rv:54.0) gecko/20100101 firefox/54.0";s:11:"customer_id";i:1648;s:14:"view_order_url";s:56:"https://www.librairiezenobi.com/account/view-order/13009";s:10:"line_items";a:3:{i:0;O:8:"stdClass":12:{s:2:"id";i:149;s:8:"subtotal";s:5:"42.65";s:12:"subtotal_tax";s:4:"2.35";s:5:"total";s:5:"42.65";s:9:"total_tax";s:4:"2.35";s:5:"price";s:5:"42.65";s:8:"quantity";i:1;s:9:"tax_class";s:6:"reduit";s:4:"name";s:14:"VIOLLET-LE-DUC";s:10:"product_id";i:9590;s:3:"sku";s:13:"9782757702925";s:4:"meta";a:0:{}}i:1;O:8:"stdClass":12:{s:2:"id";i:150;s:8:"subtotal";s:5:"17.35";s:12:"subtotal_tax";s:4:"0.95";s:5:"total";s:5:"17.35";s:9:"total_tax";s:4:"0.95";s:5:"price";s:5:"17.35";s:8:"quantity";i:1;s:9:"tax_class";s:6:"reduit";s:4:"name";s:18:"RECETTES IMMORALES";s:10:"product_id";i:12196;s:3:"sku";s:13:"9782914480611";s:4:"meta";a:0:{}}i:2;O:8:"stdClass":12:{s:2:"id";i:151;s:8:"subtotal";s:4:"6.82";s:12:"subtotal_tax";s:4:"0.38";s:5:"total";s:4:"6.82";s:9:"total_tax";s:4:"0.38";s:5:"price";s:4:"6.82";s:8:"quantity";i:1;s:9:"tax_class";s:6:"reduit";s:4:"name";s:21:"LES VILLES INVISIBLES";s:10:"product_id";i:10450;s:3:"sku";s:13:"9782070449408";s:4:"meta";a:0:{}}}s:14:"shipping_lines";a:1:{i:0;O:8:"stdClass":4:{s:2:"id";i:152;s:9:"method_id";s:20:"POFR_ColissimoAccess";s:12:"method_title";s:9:"Colissimo";s:5:"total";s:4:"8.29";}}s:9:"tax_lines";a:2:{i:0;O:8:"stdClass":6:{s:2:"id";i:153;s:7:"rate_id";i:8;s:4:"code";s:13:"FR-TVA 5,5%-1";s:5:"title";s:8:"TVA 5,5%";s:5:"total";s:4:"3.68";s:8:"compound";b:0;}i:1;O:8:"stdClass":6:{s:2:"id";i:154;s:7:"rate_id";i:10;s:4:"code";s:12:"FR-TVA 20%-1";s:5:"title";s:7:"TVA 20%";s:5:"total";s:4:"1.66";s:8:"compound";b:0;}}s:9:"fee_lines";a:0:{}s:12:"coupon_lines";a:0:{}s:8:"customer";O:8:"stdClass":14:{s:2:"id";i:1648;s:10:"created_at";s:20:"2017-10-12T03:55:10Z";s:5:"email";s:18:"luce.nadeau@me.com";s:10:"first_name";s:4:"Luce";s:9:"last_name";s:6:"NADEAU";s:8:"username";s:11:"luce.nadeau";s:4:"role";s:10:"subscriber";s:13:"last_order_id";i:13009;s:15:"last_order_date";s:20:"2017-10-12T06:00:00Z";s:12:"orders_count";i:1;s:11:"total_spent";s:4:"0.00";s:10:"avatar_url";s:75:"http://2.gravatar.com/avatar/b0d6c4dae81ada3d025b76faf2e34b46?s=96&d=mm&r=g";s:15:"billing_address";O:8:"stdClass":11:{s:10:"first_name";s:4:"Luce";s:9:"last_name";s:6:"NADEAU";s:7:"company";s:0:"";s:9:"address_1";s:28:"4 rue du Lt-Colonel Vendôme";s:9:"address_2";s:0:"";s:4:"city";s:7:"GAILLAC";s:5:"state";s:0:"";s:8:"postcode";s:5:"81600";s:7:"country";s:2:"FR";s:5:"email";s:18:"luce.nadeau@me.com";s:5:"phone";s:10:"0623384731";}s:16:"shipping_address";O:8:"stdClass":9:{s:10:"first_name";s:4:"Luce";s:9:"last_name";s:6:"NADEAU";s:7:"company";s:0:"";s:9:"address_1";s:28:"4 rue du Lt-Colonel Vendôme";s:9:"address_2";s:0:"";s:4:"city";s:7:"GAILLAC";s:5:"state";s:0:"";s:8:"postcode";s:5:"81600";s:7:"country";s:2:"FR";}}}';

	$product_test_serialize["9590"] = 'O:8:"stdClass":1:{s:7:"product";O:8:"stdClass":58:{s:5:"title";s:14:"VIOLLET-LE-DUC";s:2:"id";i:9590;s:10:"created_at";s:20:"2017-09-11T10:11:30Z";s:10:"updated_at";s:20:"2017-09-11T10:11:30Z";s:4:"type";s:6:"simple";s:6:"status";s:7:"publish";s:12:"downloadable";b:0;s:7:"virtual";b:0;s:9:"permalink";s:55:"https://www.librairiezenobi.com/produit/viollet-le-duc/";s:3:"sku";s:13:"9782757702925";s:5:"price";s:5:"45.00";s:13:"regular_price";s:5:"45.00";s:10:"sale_price";N;s:10:"price_html";s:237:"<span class="woocs_price_code" data-product-id="9590"><span class="woocommerce-Price-amount amount">45,00&nbsp;<span class="woocommerce-Price-currencySymbol">&euro;</span></span> <small class="woocommerce-price-suffix">TTC</small></span>";s:7:"taxable";b:1;s:10:"tax_status";s:7:"taxable";s:9:"tax_class";s:6:"reduit";s:14:"managing_stock";b:1;s:14:"stock_quantity";i:0;s:8:"in_stock";b:0;s:18:"backorders_allowed";b:0;s:11:"backordered";b:0;s:17:"sold_individually";b:0;s:12:"purchaseable";b:1;s:8:"featured";b:0;s:7:"visible";b:1;s:18:"catalog_visibility";s:7:"visible";s:7:"on_sale";b:0;s:11:"product_url";s:0:"";s:11:"button_text";s:0:"";s:6:"weight";s:7:"1510.00";s:10:"dimensions";O:8:"stdClass":4:{s:6:"length";s:5:"27.00";s:5:"width";s:6:"248.00";s:6:"height";s:6:"308.00";s:4:"unit";s:2:"cm";}s:17:"shipping_required";b:1;s:16:"shipping_taxable";b:1;s:14:"shipping_class";s:0:"";s:17:"shipping_class_id";N;s:11:"description";s:1184:"<p>Qui était Viollet-le-Duc (1814-1879)? À lheure du bicentenaire de la naissance de larchitecte, Françoise Bercé fait le point sur cette figure admirée autant que décriée. Fut-il un passeur du modernisme ou un attardé de lhistoricisme? Au gré des successives réinterprétations du passé et des récentes découvertes, elle nous fait rencontrer lhomme dans sa complexité et nous révèle limmense richesse de son oeuvre qui, côtoyant toutes sortes de disciplines, est loin de se limiter à larchitecture: architecte, mais aussi peintre, dessinateur, décorateur, écrivain, théoricien Pour la réalisation de cet ouvrage, un soin tout particulier a été apporté à la mise en page et à liconographie: le format de la collection "Monographies darchitectes" a été agrandi pour loccasion et louvrage, relié sous jaquette, est conçu pour être un très beau livre de fin dannée. Des portfolios animent régulièrement les chapitres, illustrant des lieux emblématiques de loeuvre de larchitecte (Pierrefonds, Carcassonne, Notre-Dame) ou en éclairant des aspects méconnus (ensemble magnifique des dessins de paysages de montagne).</p>
";s:17:"short_description";s:0:"";s:15:"reviews_allowed";b:1;s:14:"average_rating";s:4:"0.00";s:12:"rating_count";i:0;s:11:"related_ids";a:5:{i:0;i:9600;i:1;i:9572;i:2;i:9566;i:3;i:9604;i:4;i:1989;}s:10:"upsell_ids";a:0:{}s:14:"cross_sell_ids";a:0:{}s:9:"parent_id";i:0;s:10:"categories";a:1:{i:0;s:16:"120 - Patrimoine";}s:4:"tags";a:0:{}s:6:"images";a:1:{i:0;O:8:"stdClass":7:{s:2:"id";i:9591;s:10:"created_at";s:20:"2017-09-11T10:11:30Z";s:10:"updated_at";s:20:"2017-09-11T10:11:30Z";s:3:"src";s:73:"https://www.librairiezenobi.com/wp-content/uploads/2017/09/image-214.jpeg";s:5:"title";s:0:"";s:3:"alt";s:0:"";s:8:"position";i:0;}}s:12:"featured_src";s:73:"https://www.librairiezenobi.com/wp-content/uploads/2017/09/image-214.jpeg";s:10:"attributes";a:0:{}s:9:"downloads";a:0:{}s:14:"download_limit";i:-1;s:15:"download_expiry";i:-1;s:13:"download_type";s:8:"standard";s:13:"purchase_note";s:0:"";s:11:"total_sales";i:1;s:10:"variations";a:0:{}s:6:"parent";a:0:{}}}';

	$product_test_serialize["12196"] = 'O:8:"stdClass":1:{s:7:"product";O:8:"stdClass":58:{s:5:"title";s:18:"RECETTES IMMORALES";s:2:"id";i:12196;s:10:"created_at";s:20:"2017-09-11T16:48:11Z";s:10:"updated_at";s:20:"2017-09-11T16:48:11Z";s:4:"type";s:6:"simple";s:6:"status";s:7:"publish";s:12:"downloadable";b:0;s:7:"virtual";b:0;s:9:"permalink";s:59:"https://www.librairiezenobi.com/produit/recettes-immorales/";s:3:"sku";s:13:"9782914480611";s:5:"price";s:5:"18.30";s:13:"regular_price";s:5:"18.30";s:10:"sale_price";N;s:10:"price_html";s:238:"<span class="woocs_price_code" data-product-id="12196"><span class="woocommerce-Price-amount amount">18,30&nbsp;<span class="woocommerce-Price-currencySymbol">&euro;</span></span> <small class="woocommerce-price-suffix">TTC</small></span>";s:7:"taxable";b:1;s:10:"tax_status";s:7:"taxable";s:9:"tax_class";s:6:"reduit";s:14:"managing_stock";b:1;s:14:"stock_quantity";i:0;s:8:"in_stock";b:0;s:18:"backorders_allowed";b:0;s:11:"backordered";b:0;s:17:"sold_individually";b:0;s:12:"purchaseable";b:1;s:8:"featured";b:0;s:7:"visible";b:1;s:18:"catalog_visibility";s:7:"visible";s:7:"on_sale";b:0;s:11:"product_url";s:0:"";s:11:"button_text";s:0:"";s:6:"weight";s:6:"182.00";s:10:"dimensions";O:8:"stdClass":4:{s:6:"length";s:4:"9.00";s:5:"width";s:6:"102.00";s:6:"height";s:6:"220.00";s:4:"unit";s:2:"cm";}s:17:"shipping_required";b:1;s:16:"shipping_taxable";b:1;s:14:"shipping_class";s:0:"";s:17:"shipping_class_id";N;s:11:"description";s:1021:"<p>Souvent connu comme un maître du roman noir à travers son fameux héros, Pepe Carvalho, le Catalan Manuel Vasquez Montalban, disparu en 2003, a laissé une oeuvre considérable dans laquelle la gastronomie a toujours tenu une place privilégiée. Recettes immorales ? Selon Manuel Vasquez Montalban, qui l\'écrivit dans sa préface : "Il faut dire avant tout que la morale n\'est pas une valeur absolue mais relative, et, par là, immorale également. Chacune de ces recettes est un pari pour une autre morale possible, pour une morale hédoniste à la portée des partisans du bonheur immédiat, consistant à user et même à abuser de connaissances innocentes : savoir cuisiner, savoir manger, essayer d\'apprendre à aimer..."Cet ouvrage, traduit par Georges Tyras, n\'avait pas été réédité depuis 1993. Plus encore que les recettes, on savourera avant tout, les textes introductifs à travers lesquels s\'esquisse un certain portrait de Manuel Vasquez Montalban et dans lesquels s\'exprime tout son humour.</p>
";s:17:"short_description";s:0:"";s:15:"reviews_allowed";b:1;s:14:"average_rating";s:4:"0.00";s:12:"rating_count";i:0;s:11:"related_ids";a:5:{i:0;i:12138;i:1;i:12124;i:2;i:12144;i:3;i:12162;i:4;i:12130;}s:10:"upsell_ids";a:0:{}s:14:"cross_sell_ids";a:0:{}s:9:"parent_id";i:0;s:10:"categories";a:1:{i:0;s:22:"160 - Cuisine du monde";}s:4:"tags";a:0:{}s:6:"images";a:1:{i:0;O:8:"stdClass":7:{s:2:"id";i:12197;s:10:"created_at";s:20:"2017-09-11T16:48:12Z";s:10:"updated_at";s:20:"2017-09-11T16:48:12Z";s:3:"src";s:74:"https://www.librairiezenobi.com/wp-content/uploads/2017/09/image-1517.jpeg";s:5:"title";s:0:"";s:3:"alt";s:0:"";s:8:"position";i:0;}}s:12:"featured_src";s:74:"https://www.librairiezenobi.com/wp-content/uploads/2017/09/image-1517.jpeg";s:10:"attributes";a:0:{}s:9:"downloads";a:0:{}s:14:"download_limit";i:-1;s:15:"download_expiry";i:-1;s:13:"download_type";s:8:"standard";s:13:"purchase_note";s:0:"";s:11:"total_sales";i:1;s:10:"variations";a:0:{}s:6:"parent";a:0:{}}}
';

	$product_test_serialize["10450"] = 'O:8:"stdClass":1:{s:7:"product";O:8:"stdClass":58:{s:5:"title";s:21:"LES VILLES INVISIBLES";s:2:"id";i:10450;s:10:"created_at";s:20:"2017-09-11T12:01:41Z";s:10:"updated_at";s:20:"2017-09-11T12:01:41Z";s:4:"type";s:6:"simple";s:6:"status";s:7:"publish";s:12:"downloadable";b:0;s:7:"virtual";b:0;s:9:"permalink";s:62:"https://www.librairiezenobi.com/produit/les-villes-invisibles/";s:3:"sku";s:13:"9782070449408";s:5:"price";s:4:"7.20";s:13:"regular_price";s:4:"7.20";s:10:"sale_price";N;s:10:"price_html";s:237:"<span class="woocs_price_code" data-product-id="10450"><span class="woocommerce-Price-amount amount">7,20&nbsp;<span class="woocommerce-Price-currencySymbol">&euro;</span></span> <small class="woocommerce-price-suffix">TTC</small></span>";s:7:"taxable";b:1;s:10:"tax_status";s:7:"taxable";s:9:"tax_class";s:6:"reduit";s:14:"managing_stock";b:1;s:14:"stock_quantity";i:0;s:8:"in_stock";b:0;s:18:"backorders_allowed";b:0;s:11:"backordered";b:0;s:17:"sold_individually";b:0;s:12:"purchaseable";b:1;s:8:"featured";b:0;s:7:"visible";b:1;s:18:"catalog_visibility";s:7:"visible";s:7:"on_sale";b:0;s:11:"product_url";s:0:"";s:11:"button_text";s:0:"";s:6:"weight";s:6:"130.00";s:10:"dimensions";O:8:"stdClass":4:{s:6:"length";s:5:"13.00";s:5:"width";s:6:"110.00";s:6:"height";s:6:"175.00";s:4:"unit";s:2:"cm";}s:17:"shipping_required";b:1;s:16:"shipping_taxable";b:1;s:14:"shipping_class";s:0:"";s:17:"shipping_class_id";N;s:11:"description";s:849:"<p>«Les villes comme les rêves sont faites de désirs et de peurs, même si le fil de leur discours est secret, leurs règles absurdes, leurs perspectives trompeuses ; et toute chose en cache une autre. - Moi, je nai ni désirs, ni peurs, déclara le Khan, et mes rêves sont composés soit par mon esprit soit par le hasard. - Les villes aussi se croient luvre de lesprit ou du hasard, mais ni lun ni lautre ne suffisent pour faire tenir debout leurs murs. Tu ne jouis pas dune ville à cause de ses sept ou soixante-dix-sept merveilles, mais de la réponse quelle apporte à lune de tes questions.» À travers un dialogue imaginaire entre Marco Polo et lempereur Kublai Khan, Italo Calvino nous offre un «dernier poème damour aux villes» et une subtile réflexion sur le langage, lutopie et notre monde moderne.</p>
";s:17:"short_description";s:0:"";s:15:"reviews_allowed";b:1;s:14:"average_rating";s:4:"0.00";s:12:"rating_count";i:0;s:11:"related_ids";a:5:{i:0;i:4559;i:1;i:4851;i:2;i:4539;i:3;i:2019;i:4;i:4705;}s:10:"upsell_ids";a:0:{}s:14:"cross_sell_ids";a:0:{}s:9:"parent_id";i:0;s:10:"categories";a:1:{i:0;s:18:"180 - Littérature";}s:4:"tags";a:0:{}s:6:"images";a:1:{i:0;O:8:"stdClass":7:{s:2:"id";i:10451;s:10:"created_at";s:20:"2017-09-11T12:01:41Z";s:10:"updated_at";s:20:"2017-09-11T12:01:41Z";s:3:"src";s:73:"https://www.librairiezenobi.com/wp-content/uploads/2017/09/image-644.jpeg";s:5:"title";s:0:"";s:3:"alt";s:0:"";s:8:"position";i:0;}}s:12:"featured_src";s:73:"https://www.librairiezenobi.com/wp-content/uploads/2017/09/image-644.jpeg";s:10:"attributes";a:0:{}s:9:"downloads";a:0:{}s:14:"download_limit";i:-1;s:15:"download_expiry";i:-1;s:13:"download_type";s:8:"standard";s:13:"purchase_note";s:0:"";s:11:"total_sales";i:1;s:10:"variations";a:0:{}s:6:"parent";a:0:{}}}
';




