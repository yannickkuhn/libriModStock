<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use AppBundle\Entity\Client;
use AppBundle\Entity\OrderHeader;
use AppBundle\Entity\OrderLine;
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

	public function __construct(EntityManagerInterface $em, LoggerInterface $logger, \Swift_Mailer $mailer)
    {
    	$this->em = $em;
        $this->logger = $logger;
        $this->mailer = $mailer;

        $this->consumer_key = 'ck_5c287f62388e2d18f6834fb8405f91289ffa3caa'; 
        $this->consumer_secret = 'cs_4cdb9f1cdecc1336fb35571bf2d4104ffd454012'; 
        $this->store_url = 'https://www.librairiezenobi.com/'; 
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
        $this->logStep = 25;

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
        
        $page = 1;
        $maxpage = 302;
        $per_page = 20;
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
                    'query_string_auth' => true
                ]
            );

            //var_dump($ws->get(''));

            $logger->info('OK pour le WS');

            for($currentPage = $page; $currentPage < $maxpage; $currentPage ++) {

                $currentUpdatedProductsNb = 0;
                $count_wsprod = 0;
                $cur_products = [];

                //$logger->info('Traitement de la page : '.$currentPage);
                $wsproducts = $ws->get('products', [ 'page' => (int)$currentPage, 'per_page' => $per_page ]);
                if(count($wsproducts) == 0)
                    break;

                foreach ($wsproducts as $wsproduct) {
                    $cur_products[$wsproduct['id']] = $wsproduct['sku'];
                    $products[$wsproduct['id']] = $wsproduct['sku'];
                    $count_wsprod ++;
                }
            
                //$logger->info('nb produits : '.$count_wsprod);
                //$logger->info('Consolidation des données de mise à jour en cours ...');

                $data_batch['update'] = array();

                foreach($cur_products as $idproduct => $ean) {
                    $data_product = $this->getLocalProduct($idproduct, $ean);
                    if(null !== $data_product && is_array($data_product)) {
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

            // TODO : Les produits d'occasions ne sont pas inclus pour l'instant
            $localProducts = $em->getRepository('AppBundle:Product')->findBy([
                'isDeleted' => '0'
            ]);

            $indice = 0;
            $created_per = 10;
            $currentCreatedProductsNb = 0;
            $data_batch['create'] = [];

            $logger->info('---- NOMBRE DE PRODUITS DEJA MIS A JOUR : '.count($products).' -----');

            foreach($localProducts as $localProduct) {

                if($indice > 0 && ($indice%$created_per == 0)) {
                    //$logger->info('Nombre de produits à ajouter : '.$currentUpdatedProductsNb);
                    $ws->post('products/batch', $data_batch);
                    $data_batch['create'] = [];
                    $createdProductsNb += $currentCreatedProductsNb;
                    $logger->info('Nombre de produits total ajoutés : '.$createdProductsNb);
                    $currentCreatedProductsNb = 0;
                }

                if(!in_array($localProduct->getEan(), $products)) {
                    // Si on peut continuer, on est dans le cas d'un ajout de produit
                    $ean = $localProduct->getEan();
                    //$logger->info('produit '.$ean.' à ajouter !');
                    $idproduct = 0;

                    $data_product = $this->getLocalProduct($idproduct, $ean, 'create');
                    if(null !== $data_product && is_array($data_product)) {
                        $data_batch['create'][] = $data_product;
                        $currentCreatedProductsNb ++;
                        $indice++;
                    }
                }

            }

            $logger->info('Nombre de produits à ajouter : '.$createdProductsNb.'');
            $logger->info('Nombre de produits mis à jour : '.$updatedProductsNb.'');
            $logger->info('Synchronisation terminée pour les mises à jour de produits !');

            $message = \Swift_Message::newInstance()
                        ->setSubject('[SITE INTERNET] - Synchronisation des produits à '.date('d/m/Y à H:i:s'))
                        ->setFrom($this->mailer_from)
                        ->setTo($this->mailer_to)
                        ->setBody("Bonjour,\r\n\r\nVoici ci-joint le résultat de la synchronisation de la base produits.\r\n".$createdProductsNb." nouveau(x) produit(s) vient/viennent d'être ajouté(s) sur le site Internet. ".$updatedProductsNb." produit(s) vient/viennent d'être mis à jour sur le site (produits déjà existants en base)\r\n. La base est complètement à jour.\r\n A bientôt sur librairiezenobi.com");
            $this->mailer->send($message);

        } catch (HttpClientException $e) {
            $logger->info($e->getMessage()); // Error message.
            $logger->info($e->getRequest()->getBody()); // Last request data.
            $logger->info($e->getResponse()->getBody()); // Last response data.
        }
    }

    private function getLocalProduct($idproduct, $ean, $action = 'update')
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
                $logger->error('Produit à supprimer (prod_deleted = 1'.$ean);
                return false;
            }

            //$logger->info('Produit trouvé');

            if(null !== $localProduct->getCategory1()) {
                $category1 = $em->getRepository('AppBundle:Category')->findOneById($localProduct->getCategory1());
                if(null !== $category1->getLibrisoftId() && in_array($category1->getLibrisoftId(), $this->excluded_librisoft_categories)) {
                    return false;
                }
            }

            $description = '';
            $description = (file_get_contents('http://bddi.2dcom.fr/GetResumeUtf8.php?user=wsbddi&pw=ErG2i8Aj&ean=' . $localProduct->getEan()) != 'erreur resume non trouve') ? file_get_contents('http://bddi.2dcom.fr/GetResumeUtf8.php?user=wsbddi&pw=ErG2i8Aj&ean=' . $localProduct->getEan()) : '';

            $price = $localProduct->getNetTotal();
                
            $category = '118';
            $vat = "réduit";
            if($localProduct->getVat1() == '20.00')
                $vat = "Standard";
            
            $stock = $em->getRepository('AppBundle:Stock')->findOneBy(["ean" => $localProduct->getEan()]);
            $stock_qte = 0;

            if(null !== $stock && 0 < $stock->getQuantity())
                $stock_qte = $stock->getQuantity(); 
            
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
            $data_product['images'] = array (
                    '0' => array( 'src' => 'http://bddi.2dcom.fr/LocalImageExists.php?ean='.$localProduct->getEan().'&isize=medium&gencod=3025594728601&key=mZfH7ltnWECPwoED', 'id' => '0', 'position' => '0' ),
                    );

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
}