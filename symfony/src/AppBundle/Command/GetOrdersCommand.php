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

class GetOrdersCommand extends Command
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

    private $order_status_to_test;
    private $shippings;

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

        $this->order_status_to_test = ['processing', 'shipped', 'awaiting-shipment'];
        $this->shippings = [
            "Colissimo"             => 1,
            "Chronopost"            => 2,
            "Mondial Relay"         => 3,
            "Relais Colis"          => 4,
            "Livraison gratuite"    => 5
        ];

        parent::__construct();
    }

    protected function configure()
    {
        $this
        	->setName('app:get-orders')
        	->setDescription('Synchronisation des commandes WooCommerce.')
        	->setHelp('Cette commande vous permet de synchroniser les commandes WooCommerce depuis le CLI ...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$em = $this->em;
    	$logger = $this->logger;

    	$totalOrdersDownloaded = 0;
    	$totalOrderLinesDownloaded = 0;
        $ordersDownloaded = [];

        $logger->info('----------------------------------------------------------------------');
        $logger->info('Lancement de la procédure de téléchargement des commandes en cours ...');
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


            $page = 1;
            $maxpage = 10;

            $logger->info('OK pour le WS');

            for($currentPage = $page; $currentPage < $maxpage; $currentPage ++) {

                $wsorders = $ws->get('orders', array( 'status' => 'any', 'page' => (int)$currentPage ) );

                if(count($wsorders) == 0) {
                    break;
                }

                foreach ($wsorders as $wsorder) {
                    if(in_array($wsorder["status"], $this->order_status_to_test)) {

                        // ORDER EVER EXIST ?

                        $order = $em->getRepository('AppBundle:OrderHeader')->findOneBy(['id' => $wsorder["id"]]);

                        if(null === $order) {

                            $logger->info("Commande ".$wsorder["number"]." inexistante (statut: ".$wsorder["status"].")...");

                            // 1- CREATE CLIENT ID NOT EXIST

                            $client = $em->getRepository('AppBundle:Client')->findOneBy(['id' => $wsorder["customer_id"]]);

                            if(null === $client) {

                                $logger->info("Client inexistant en cours de création ".$wsorder["customer_id"]." ...");

                                $wscustomer = $ws->get('customers/'.$wsorder["customer_id"] );

                                $lastOrderDate = new \DateTime($wscustomer["date_modified"]);
                                $subscribedDate = new \DateTime($wscustomer["date_created"]);
                                $client = new Client();
                                $client
                                    ->setId($wsorder["customer_id"])
                                    ->setCliId($wsorder["customer_id"])
                                    ->setCivility($this->getGender($output, $wscustomer["first_name"]))
                                    ->setFirstName($wscustomer["first_name"])
                                    ->setLastName($wscustomer["last_name"])
                                    ->setMail($wscustomer["email"])
                                    ->setSubscribedDate($subscribedDate)
                                    ->setCliLastVisit($lastOrderDate)
                                ;
                                $em->persist($client);

                                $logger->info("Client créé !");
                            }

                            // 2- CREATE ORDER HEADER

                            // Search Countries
                            $shippingCountry = $em->getRepository('AppBundle:Country')->findOneBy(['iso' => $wsorder["shipping"]["country"]]);
                            $billingCountry = $em->getRepository('AppBundle:Country')->findOneBy(['iso' => $wsorder["billing"]["country"]]);

                            $createdAt = new \DateTime($wsorder["date_created"]);

                            if(!isset($wsorder["shipping"]["phone"]))
                                $shippingAddressPhone = "";
                            else
                                $shippingAddressPhone = $wsorder["shipping"]["phone"];

                            $logger->info("Méthode de paiement utilisée : ".$wsorder["payment_method"]." !");

                            $payment_method = $wsorder["payment_method"];
                            $paymentId = '1';
                            $payed = '1';

                            if($payment_method == "cod") {
                                $paymentId = '3';
                                $payed = '0';
                            }

                            $order = new OrderHeader();
                            $order
                                ->setId($wsorder["id"])
                                ->setIdWeb($wsorder["id"])
                                ->setStatusId('1')
                                ->setPaymentStatus($payed)
                                ->setPaymentId($paymentId)
                                ->setIdLibrisoft(null)
                                ->setCreatedAtDate($createdAt)
                                ->setCreatedAtTime($createdAt)
                                ->setStatusDate($createdAt)
                                ->setShippingId($this->shippings[$wsorder["shipping_lines"][0]["method_title"]])
                                ->setBilledCustomer($wsorder["customer_id"])
                                ->setNetTotal($wsorder["total"] - $wsorder["shipping_tax"] - $wsorder["shipping_total"])          
                                ->setShippingValue($wsorder["shipping_total"] + $wsorder["shipping_tax"])
                                ->setBillingTitle($this->getGender($output, $wsorder["billing"]["first_name"]))
                                ->setBillingFirstName($wsorder["billing"]["first_name"])
                                ->setBillingLastName($wsorder["billing"]["last_name"])
                                ->setBillingSocietyName($wsorder["billing"]["company"])
                                ->setBillingStreetAddress($wsorder["billing"]["address_1"]." ".$wsorder["billing"]["address_2"])
                                ->setBillingTown($wsorder["billing"]["city"])
                                ->setBillingPostCode($wsorder["billing"]["postcode"])
                                ->setBillingCountry($billingCountry->getId())
                                ->setBillingPhone($wsorder["billing"]["phone"])
                                ->setShippingTitle($this->getGender($output, $wsorder["shipping"]["first_name"]))
                                ->setShippingFirstName($wsorder["shipping"]["first_name"])
                                ->setShippingLastName($wsorder["shipping"]["last_name"])
                                ->setShippingSocietyName($wsorder["shipping"]["company"])
                                ->setShippingStreetAddress($wsorder["shipping"]["address_1"]." ".$wsorder["shipping"]["address_2"])
                                ->setShippingTown($wsorder["shipping"]["city"])
                                ->setShippingPostCode($wsorder["shipping"]["postcode"])
                                ->setShippingCountry($shippingCountry->getId())
                                ->setShippingPhone($shippingAddressPhone)
                            ;

                            $em->persist($order);

                            $logger->info("Commande créée ".$wsorder["number"]." ...");
                            $ordersDownloaded[] = 'Commande web numéro '.$wsorder["number"];
                            $totalOrdersDownloaded++;                

                            // 3- CREATE ORDER LINES
                            
                            $totalWeight = 0;

                            foreach ($wsorder["line_items"] as $wsorderline) {

                            	$logger->info("Ligne numéro ".$wsorderline["id"]." de la commande ".$wsorder["number"]." en cours de création ...");

                                // SEARCH PRODUCT
                                $product = $ws->get('products/'.$wsorderline["product_id"] );

                                // TODO : Cette recherche exclue les produits d'occasions ... !!!
                                $localProduct = $em->getRepository('AppBundle:Product')->findOneBy(['ean' => $wsorderline["sku"]]);
                            
                                $orderLine = new OrderLine();
                                $orderLine
                                    ->setId($wsorderline["id"])
                                    ->setOrderHeader($order->getId())
                                    ->setQuantity($wsorderline["quantity"])
                                    ->setNetPrice($wsorderline["total"] + $wsorderline["total_tax"])
                                    ->setTotalVat(($wsorderline["tax_class"] == "reduit" || $wsorderline["tax_class"] == "réduit") ? "5.50" : "20.00")
                                    ->setDiscountPrice('0')
                                    ->setIsPromote('0')
                                    ->setStock($wsorderline["quantity"])
                                    ->setEan($wsorderline["sku"])
                                    ->setTitle($wsorderline["name"])
                                    ->setWeight((int)$product["weight"])
                                    ->setIsChecked('1');
                                if(null !== $localProduct) {
                                    $orderLine->setAuthor($localProduct->getAuthor());
                                    $orderLine->setPublisher($localProduct->getPublisher());
                                } else {
                                    $orderLine->setAuthor('');
                                    $orderLine->setPublisher('');
                                }
                                $totalWeight += (int)$product["weight"];
                                $em->persist($orderLine);

                                $logger->info("Ligne créée ".$wsorderline["id"]." ...");
                                $totalOrderLinesDownloaded++;
                            }

                            $logger->info("Mise à jour du poids total de la commande ".$wsorder["number"]." (valeur : $totalWeight) ...");

                            $order->setWeight($totalWeight);
                            $em->persist($order);

                            $em->flush();
                        }
                    }
                }
            }

            if($totalOrdersDownloaded == 0) {
            	$logger->info('Aucune commande en attente de téléchargement ...');
                $this->send_mail(
                    '[SITE INTERNET] - Synchronisation des commandes à '.date('d/m/Y à H:i:s'),
                    "Bonjour,\r\n\r\n"."Aucune commande en attente de téléchargement !\r\nA bientôt sur librairiezenobi.com"
                );
            } else {
            	$logger->info('Nombre de commandes téléchargées : '.$totalOrdersDownloaded);
            	$logger->info('Nombre de lignes de commandes téléchargées : '.$totalOrderLinesDownloaded);
                $this->send_mail(
                    '[SITE INTERNET] - Synchronisation des commandes à '.date('d/m/Y à H:i:s'),
                    "Bonjour,\r\n\r\n".$totalOrdersDownloaded." nouvelle(s) commande(s) vient/viennent d'être téléchargée(s) dans la base intermédiaire de Librisoft. Les commandes Internet seront récupérées lors de la prochaine exécution de l'interface Librisoft/Internet (dans Librisoft : menu Outil/Interface librisoft/Internet)\r\n. Numéros de commandes concernées : ".print_r($ordersDownloaded, true).".\r\n A bientôt sur librairiezenobi.com"
                );
            }
            $logger->info('Synchronisation des commandes terminée !');

        } catch (HttpClientException $e) {
            $logger->info($e->getMessage()); // Error message.
            $logger->info($e->getRequest()->getBody()); // Last request data.
            $logger->info($e->getResponse()->getBody()); // Last response data.
        }
    }

    private function getGender(OutputInterface $output, string $firstName)
    {
    	$logger = $this->logger;

    	$logger->info("test d'appel de GetGender avec le paramètre prénom = $firstName ...");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.genderize.io?name='.$firstName);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

    	//$headers = array('Accept' => 'application/json');
    	//$response = \Unirest\Request::get('https://api.genderize.io?name='.$firstName, $headers);

        //$logger->info("GetGender : réponse renvoyée : ".print_r($response, true)." ...");
        $data = json_decode($response);
        if(!empty($data)) {
            $gender = $data->gender;
            $logger->info("Appel getGender OK, valeur de retour du service : $gender ...");
            if($gender == "female") {
            	$logger->info("Valeur retournée : 3 ...");
                return 3;
            }
            else if($gender == "male") {
            	$logger->info("Valeur retournée : 1 ...");
                return 1;
            }
        }
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