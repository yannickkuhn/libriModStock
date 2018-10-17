<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use AppBundle\Entity\Client;
use AppBundle\Entity\OrderHeader;
use AppBundle\Entity\OrderLine;
use AppBundle\Entity\Product;
use AppBundle\Entity\AssocProduct;
use Symfony\Component\HttpFoundation\Request;

use Automattic\WooCommerce\Client as WsClient;
use Automattic\WooCommerce\HttpClient\HttpClientException;

class SyncProductsCommand extends Command
{
    private $logger;
    private $em;
    private $mailer;

    private $consumer_key;
    private $consumer_secret;
    private $store_url;
    private $options;

    private $mailer_to;
    private $mailer_from;

    private $excluded_librisoft_categories;
    private $sync_categories;
    private $categorie_occasion;
    
    private $md5_sans_visuel;
    private $id_sans_visuel;
    private $url_sans_visuel;
    private $url_image;
    private $url_resume;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, \Swift_Mailer $mailer)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->mailer = $mailer;

        $this->consumer_key = 'ck_5c287f62388e2d18f6834fb8405f91289ffa3caa'; 
        $this->consumer_secret = 'cs_4cdb9f1cdecc1336fb35571bf2d4104ffd454012'; 
        $this->store_url = 'http://zenobi.local/'; 
        $this->options = [];

        $this->mailer_to = "yk@2dcom.fr";
        $this->mailer_from = "site@librairiezenobi.com";

        $this->excluded_librisoft_categories = [ "600", "700" ];
        $this->sync_categories = [ 
              "0" => "118", "110" => "152", "115" => "153", "120" => "154", 
            "125" => "210", "130" => "155", "140" => "156", "150" => "157", 
            "160" => "158", "170" => "159", "180" => "160", "185" => "185", 
            "190" => "161", "200" => "162", "210" => "163", "220" => "164", 
            "230" => "165", "240" => "166", "250" => "167", "300" => "169", 
            "310" => "170", "315" => "186", "320" => "171", "330" => "172", 
            "340" => "173", "400" => "174", "410" => "175", "420" => "176", 
            "500" => "178", "510" => "179"];
        $this->categorie_occasion = "2416";
        $this->logStep = 50;

        $this->md5_sans_visuel = "6304f32c669087188273de7c34ec40b8";
        $this->id_sans_visuel = 166734;
        $this->url_sans_visuel = "http://zenobi.local/wp-content/uploads/2018/10/sans-visuel-2.png";
        $this->url_image = "http://bddi.2dcom.fr/LocalImageExists.php?isize=medium&gencod=3025594728601&key=mZfH7ltnWECPwoED&ean=%EAN%";
        $this->url_resume = "http://bddi.2dcom.fr/GetResumeUtf8.php?user=wsbddi&pw=ErG2i8Aj&ean=%EAN%";

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:sync-products')
            ->setDescription('Synchronisation des produits WooCommerce.')
            ->setHelp('Cette commande vous permet de mettre à jour la base de produits WooCommerce ...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        set_time_limit(0); 

        $em = $this->em;
        $logger = $this->logger;

        $updatedProductsNb = 0;
        $createdProductsNb = 0;
        $deletedProductsNb = 0;
        
        $page = 1;
        $maxpage = 2000000;
        $per_page = 10;
        $products = [];

        $logger->info('----------------------------------------------------------------------');
        $logger->info('Lancement de la procédure de synchronisation des produits en cours ...');
        $logger->info('----------------------------------------------------------------------');

        try {

            $ws = new WsClient(
                $this->store_url, 
                $this->consumer_key, 
                $this->consumer_secret,
                [
                    'wp_api' => true,
                    'version' => 'wc/v2',
                    'query_string_auth' => true,
                    'debug' => false,
                    'ssl_verify' => false,
                    'return_as_array' => false,
                    'timeout' => 7200
                ]
            );

            $logger->info('OK pour le WS');

            for($currentPage = $page; $currentPage < $maxpage; $currentPage ++) {

                $currentUpdatedProductsNb = 0;
                $count_wsprod = 0;
                $cur_products = [];
                $cur_images = [];

                $logger->info("Page : $currentPage, per_page : $per_page");

                //$logger->info('Traitement de la page : '.$currentPage);
                $wsproducts = $ws->get('products', [ 'page' => (int)$currentPage, 'per_page' => $per_page ]);

                if(count($wsproducts) == 0)
                    break;

                foreach ($wsproducts as $wsproduct) {
                    $cur_products[$wsproduct['id']] = $wsproduct['sku'];

                    // s'il y a une image dans le produit, on cherche l'id
                    if(is_array($wsproduct["images"]) && !empty($wsproduct["images"][0])) {
                        $cur_images[$wsproduct['id']] = $wsproduct["images"][0];
                    } else {
                        $cur_images[$wsproduct['id']] = false;
                    }

                    $products[$wsproduct['id']] = $wsproduct['sku'];
                    $count_wsprod ++;
                }
            
                //$logger->info('nb produits : '.$count_wsprod);
                //$logger->info('Consolidation des données de mise à jour en cours ...');

                $data_batch['update'] = array();

                foreach($cur_products as $idproduct => $ean) {

                    $data_product = $this->getLocalProduct($idproduct, $ean, $cur_images[$idproduct]);
                    if($data_product == false) {
                        //var_dump("Pour cette mise à jour, le produit $ean n'est pas un produit neuf, Recherche dans les occasions ...");
                        $data_product = $this->getLocalAssocProduct($idproduct, $ean, $cur_images[$idproduct]);
                        if($data_product == false) {
                            //var_dump("Produit d'occasion trouvé !");
                        }
                    }
                    if($data_product == "todelete") {
                        $ws->delete('products/'.$idproduct, ['force' => true]);
                        $deletedProductsNb++;
                    }   
                    else if(null !== $data_product && is_array($data_product)) {
                        $currentUpdatedProductsNb++;
                        $data_batch['update'][] = $data_product;
                    }
                }

                //$logger->info('Nombre de produits à mettre à jour : '.$currentUpdatedProductsNb);
                $ws->post('products/batch', $data_batch);

                $updatedProductsNb += $currentUpdatedProductsNb;
                $logger->info('Nombre de produits total mis à jour : '.$updatedProductsNb);
            }

            $logger->info('Process de mise à jour des produits terminé');
            
            // Produits neufs
            $localNewProducts =
                $em->getRepository('AppBundle:Product')->findBy([
                ], [], 50);

            // Produits d'occasions
            $localAssocProducts =
                $em->getRepository('AppBundle:AssocProduct')->findBy([
                ], []);

            $localProducts = array_merge($localNewProducts, $localAssocProducts);

            $indice = 0;
            $created_per = 10;
            $currentCreatedProductsNb = 0;
            $msg_ajouts = [];
            $data_batch['create'] = [];

            $logger->info('---- NOMBRE DE PRODUITS DEJA MIS A JOUR : '.count($products).' -----');

            foreach($localProducts as $localProduct) {

                if($indice > 0 && ($indice%$created_per == 0)) {
                    //$logger->info('Nombre de produits à ajouter : '.$currentUpdatedProductsNb);
                    $ws->post('products/batch', $data_batch);
                    $data_batch['create'] = [];
                    $createdProductsNb += $currentCreatedProductsNb;
                    if(!isset($msg_ajouts[$createdProductsNb])) {
                        // Eviter d'afficher 100 fois le même message dans le log
                        $logger->info('Nombre de produits total ajoutés : '.$createdProductsNb);
                    }
                    $msg_ajouts[$createdProductsNb] = true;
                    $currentCreatedProductsNb = 0;
                }

                // PRODUITS NEUFS
                if($localProduct instanceof Product || $localProduct instanceof AssocProduct) {

                    if($localProduct instanceof Product) {
                        $ean = $localProduct->getEan();
                    } else {
                        $ean = $localProduct->getAssocEan();
                    }

                    if(!in_array($ean, $products)) {
                        // Si on peut continuer, on est dans le cas d'un ajout de produit
                        
                        //$logger->info('produit '.$ean.' à ajouter !');
                        $idproduct = 0;

                        if($localProduct instanceof Product) {
                            $data_product = $this->getLocalProduct($idproduct, $ean, false, 'create');
                        } else {
                            $data_product = $this->getLocalAssocProduct($idproduct, $ean, false, 'create');
                        }
                        if(null !== $data_product && is_array($data_product)) {
                            $data_batch['create'][] = $data_product;
                            $currentCreatedProductsNb ++;
                            $indice++;
                        }
                    }
                }

            }

            // Dernière boucle
            if($currentCreatedProductsNb > 0) {
                //$logger->info(print_r($data_batch, true));
                $ws->post('products/batch', $data_batch);
                $data_batch['create'] = [];
                $createdProductsNb += $currentCreatedProductsNb;
                $logger->info('Nombre de produits total ajoutés : '.$createdProductsNb);
                $currentCreatedProductsNb = 0;
            }

            $logger->info('[FIN] - Nombre de produits à ajouter : '.$createdProductsNb.'');
            $logger->info('[FIN] - Nombre de produits mis à jour : '.$updatedProductsNb.'');
            $logger->info('[FIN] - Synchronisation terminée pour les mises à jour de produits !');

            $this->send_mail(
                '[SITE INTERNET] - Synchronisation des produits à '.date('d/m/Y à H:i:s'),
                "Bonjour,\r\n\r\nVoici ci-joint le résultat de la synchronisation de la base produits.\r\n".$createdProductsNb." nouveau(x) produit(s) vient/viennent d'être ajouté(s) sur le site Internet. ".$updatedProductsNb." produit(s) vient/viennent d'être mis à jour sur le site (produits déjà existants en base)\r\n. La base est complètement à jour.\r\n A bientôt sur librairiezenobi.com"
            );

        } catch (HttpClientException $e) {
            $logger->info($e->getMessage()); // Error message.
            $logger->info($e->getRequest()->getBody()); // Last request data.
            $logger->info($e->getResponse()->getBody()); // Last response data.
        }
    }

    private function getLocalProduct($idproduct, $ean, $distImage = false, $action = 'update')
    {
        $em = $this->em;
        $logger = $this->logger;

        if('update' !== $action && 'create' !== $action) {
            $logger->error('L\'action demandée pour cette fonction est incorrecte');
            return false;
        }

        //$logger->info('Recherche du produit local avec l\'ean '.$ean);
        $localProduct = $em->getRepository('AppBundle:Product')->findOneBy([
            'ean' => $ean
        ]);
        if(null !== $localProduct) {

            if($localProduct->getIsDeleted() == 1) {
                if('update' === $action) {
                    $logger->info('Produit à supprimer (prod_deleted = 1 - '.$ean);
                } else {
                    $logger->info('Produit inexistant, qui ne sera pas ajouté (prod_deleted = 1 - '.$ean);
                }
                return "todelete";
            }

            $stock = $em->getRepository('AppBundle:Stock')->findOneBy(["ean" => $localProduct->getEan()]);
            $stock_qte = 0;

            if(null !== $stock && 0 < $stock->getQuantity())
                $stock_qte = $stock->getQuantity(); 
            else {
                // Product not in stock
                if($action == "update") {
                    return "todelete";
                } else {
                    return false;
                }
            }
            //$logger->info('Produit trouvé');

            if(null !== $localProduct->getCategory1()) {
                $category1 = $em->getRepository('AppBundle:Category')->findOneById($localProduct->getCategory1());
                if(null !== $category1->getLibrisoftId() && in_array($category1->getLibrisoftId(), $this->excluded_librisoft_categories)) {
                    return false;
                }
            }

            $description = '';
            $url_description = str_replace("%EAN%", $localProduct->getEan(), $this->url_resume);
            $description = (file_get_contents($url_description) != 'erreur resume non trouve') ? file_get_contents($url_description) : '';

            $price = $localProduct->getNetTotal();
                
            $category = '118';
            $vat = "réduit";
            if($localProduct->getVat1() == '20.00')
                $vat = "Standard";
            
            if(null !== $localProduct->getCategory1() && '0' !== $localProduct->getCategory1()) {
                $category1 = $em->getRepository('AppBundle:Category')->findOneById($localProduct->getCategory1());

                if(null !== $category1 && '0' !== $category1->getLibrisoftId()) {
                    $category = $this->sync_categories[$category1->getLibrisoftId()];
                }
            }

            $data_product = array (
                'name'              => $localProduct->getTitle(),
                'sku'               => $localProduct->getEan(),
                'price'             => $price,
                'regular_price'     => $price,
                'tax_class'         => $vat,
                'stock_quantity'    => $stock_qte,
                'weight'            => $localProduct->getWeight(),
                'dimensions'        => array (
                                                'length' => $localProduct->getWideness(),
                                                'width' => $localProduct->getThickness(),
                                                'height' => $localProduct->getHeight(),
                                        ),
                'description'       => $description,
                'categories'        => [ [ 'id' => $category ] ],
                'downloadable'      => false,
                'virtual'           => false,
                'managing_stock'    => true,
                'in_stock'          => true,
                'purchaseable'      => true,
                'attributes'        => [
                    [
                        'name'      => 'Auteur',
                        'slug'      => 'auteur',
                        'position'  => '0',
                        'visible'   => true,
                        'variation' => false,
                        'options'   => [
                                0   => $localProduct->getAuthor()
                        ]
                    ],
                    [
                        'name'      => 'Editeur',
                        'slug'      => 'editeur',
                        'position'  => '1',
                        'visible'   => true,
                        'variation' => false,
                        'options'   => [
                                0   => $localProduct->getPublisher()
                        ]
                    ]
                ]    
            );

            $data_product["images"] = $this->getDataProductImage($localProduct->getEan(), $distImage);

            if($action == 'update') {
                $data_product['id'] = $idproduct;
                $data_product['updated_at'] = date('Y-m-d\TH:i:s');
            }
            else
                $data_product['created_at'] = date('Y-m-d\TH:i:s');

            return $data_product;
        } else {
            return false;
        }
    }

    private function getLocalAssocProduct($idproduct, $assocEan, $distImage = false, $action = 'update')
    {
        $em = $this->em;
        $logger = $this->logger;

        if('update' !== $action && 'create' !== $action) {
            $logger->error('L\'action demandée pour cette fonction est incorrecte');
            return false;
        }

        $localProduct = $em->getRepository('AppBundle:AssocProduct')->findOneBy([
            'assocEan' => $assocEan
        ]);

        if(null !== $localProduct) {

            $stock = $em->getRepository('AppBundle:Stock')->findOneBy(["ean" => $assocEan]);
            $stock_qte = 0;

            if(null !== $stock && 0 < $stock->getQuantity())
                $stock_qte = $stock->getQuantity(); 
            else {
                // Product not in stock
                if($action == "update") {
                    return "todelete";
                } else {
                    //var_dump("Produit d'occasion ".$localProduct->getAssocEan()." pas en stock");
                    return false;
                } 
            }

            //var_dump('ok');

            // Utiliser la catégorie d'occasion
            $category = $this->categorie_occasion;

            // Récupérer le produit neuf

            $vat = "réduit";
            $description = null;
            $price = $localProduct->getAssocNetTotal();
            $sale_price = $price;
            $length = 0;
            $width = 0;
            $height = 0;

            $localOriginalProduct = $em->getRepository('AppBundle:Product')->findOneBy(["ean" => $localProduct->getEan()]);
            if(null !== $localOriginalProduct) {

                //var_dump('Produit trouve : '.$localOriginalProduct->getEan());

                $length = $localOriginalProduct->getWideness();
                $width = $localOriginalProduct->getThickness();
                $height = $localOriginalProduct->getHeight();

                $price = $localOriginalProduct->getNetTotal();

                $description = '';
                $url_description = str_replace("%EAN%", $localProduct->getEan(), $this->url_resume);
                $description = (file_get_contents($url_description) != 'erreur resume non trouve') ? file_get_contents($url_description) : '';

                if($localOriginalProduct->getVat1() == '20.00')
                    $vat = "Standard";
            }  

            $data_product = array (
                'name'              => $localProduct->getAssocTitle(),
                'sku'               => $localProduct->getAssocEan(),
                'price'             => $price,
                'regular_price'     => $price,
                'sale_price'        => $sale_price,
                'tax_class'         => $vat,
                'stock_quantity'    => $stock_qte,
                'weight'            => $localProduct->getAssocWeight(),
                'dimensions'        => array (
                                                'length' => $length,
                                                'width' => $width,
                                                'height' => $height,
                                        ),
                'description'       => $description,
                'categories'        => [ [ 'id' => $category ] ],
                'downloadable'      => false,
                'virtual'           => false,
                'managing_stock'    => true,
                'in_stock'          => true,
                'purchaseable'      => true,
                'attributes'        => [
                    [
                        'name'      => 'Produit d\'occasion',
                        'slug'      => 'produit-occasion',
                        'position'  => '0',
                        'visible'   => true,
                        'variation' => false,
                        'options'   => [
                                0   => 'oui'
                        ]
                    ],
                    [
                        'name'      => 'Référence du produit neuf',
                        'slug'      => 'reference-du-produit-neuf',
                        'position'  => '0',
                        'visible'   => true,
                        'variation' => false,
                        'options'   => [
                                0   => ($localProduct->getAssocEan() !== $localProduct->getEan()) ? $localProduct->getEan() : 'aucune'
                        ]
                    ],
                    [
                        'name'      => 'Auteur',
                        'slug'      => 'auteur',
                        'position'  => '0',
                        'visible'   => true,
                        'variation' => false,
                        'options'   => [
                                0   => $localProduct->getAssocAuthor()
                        ]
                    ],
                    [
                        'name'      => 'Editeur',
                        'slug'      => 'editeur',
                        'position'  => '1',
                        'visible'   => true,
                        'variation' => false,
                        'options'   => [
                                0   => $localProduct->getAssocPublisher()
                        ]
                    ]
                ]    
            );

            if(preg_match('/^978[0-9]+/', $localProduct->getEan(), $output_array) || preg_match('/^979[0-9]+/', $localProduct->getEan(), $output_array)) {
                // Produit d'origine en 978
                $data_product["images"] = $this->getDataProductImage($localProduct->getEan(), $distImage);
            } else {
                // Sinon visuel par défaut
                $data_product["images"] = $this->getDataProductImage('sans-visuel', $distImage);
            }

            if($action == 'update') {
                $data_product['id'] = $idproduct;
                $data_product['updated_at'] = date('Y-m-d\TH:i:s');
            }
            else
                $data_product['created_at'] = date('Y-m-d\TH:i:s');

            return $data_product;
        } else {
            return false;
        }
    }

    private function getDataProductImage($ean, $distImage = false)
    {
        $data_product_images = null;

        $product_sans_visuel = $this->md5_sans_visuel;
        $url_sans_visuel = $this->url_sans_visuel;
        $id_sans_visuel = $this->id_sans_visuel;
        $url = str_replace("%EAN%", $ean, $this->url_image);

        if( (md5(file_get_contents($url)) === $product_sans_visuel) || $ean == 'sans-visuel' ) {
            $nameImage = 'sans-visuel';
            $url = $url_sans_visuel;
            $id = $id_sans_visuel;
        } else {
            $nameImage = $ean;
            $id = null;
        }

        if($distImage == false) {

            // Pour l'instant, on mets à jour l'image uniquement s'il n'y en a pas sur le serveur

            $data_product_images = [
                [
                    'id' => $id,
                    'src' => $url, 
                    'position' => 0,
                    'name' => $nameImage
                ]
            ];

        } else {
            $distImageId = $distImage['id'];
            $distImageSrc = $distImage['src'];
            //$logger->info(md5(file_get_contents($distImageSrc))." - ".md5(file_get_contents($url)));
            if( 
                ( (md5(file_get_contents($distImageSrc)) === $product_sans_visuel) && (md5(file_get_contents($url)) !== $product_sans_visuel) )
                
                // CODE NON FONCTIONNEL - LES CODES MD5 SONT REMIS A JOUR SYSTEMATIQUEMENT ALORS
                // QU'IL S'AGIT DE LA MEME IMAGE
                //||
                //(md5(file_get_contents($distImageSrc)) !== md5(file_get_contents($url))) 
            
            ) {
                // On ne mets à jour l'image que si c'est nécessaire
                $logger->info('Image de produit à mettre à jour : '.$ean);
                $data_product_images = [
                    [
                        'src' => $url, 
                        'position' => 0,
                        'name' => $nameImage
                    ]
                ];
            }
        }

        return $data_product_images;
    }

    private function send_mail($sujet = null, $message_txt = null, $mail = null, $header = null)
    {

        $from = "admin@librairiezenobi.com";
        $reply_to = "admin@librairiezenobi.com";

        if($mail == null)
            $mail = 'sawsan0907@gmail.com'; 
        if($sujet == null)
            $sujet = "Test envoi de mail depuis le site Internet (ceci est un message auto pour les scripts de synchronisation web) ... !";
        if($message_txt == null)
            $message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
        
        if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail))
            $passage_ligne = "\r\n";
        else
            $passage_ligne = "\n";

        $message_html = "<html><head></head><body>".$message_txt."</body></html>";
        $boundary = "-----=".md5(rand());
        
        $header = "From: \"Librairie Zenobi\"<".$from.">".$passage_ligne;
        $header.= "Reply-to: \"Librairie Zenobi\" <".$reply_to.">".$passage_ligne;
        $header.= "MIME-Version: 1.0".$passage_ligne;
        $header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
        $message = $passage_ligne."--".$boundary.$passage_ligne;
        $message.= "Content-Type: text/plain; charset=\"UTF-8\"".$passage_ligne;
        $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
        $message.= $passage_ligne.$message_txt.$passage_ligne;
        $message.= $passage_ligne."--".$boundary.$passage_ligne;
        $message.= "Content-Type: text/html; charset=\"UTF-8\"".$passage_ligne;
        $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
        $message.= $passage_ligne.$message_html.$passage_ligne;
        $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
        $message.= $passage_ligne."--".$boundary."--".$passage_ligne;
        mail($mail,$sujet,$message,$header);
    }
}