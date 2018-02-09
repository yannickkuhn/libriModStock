<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

use HikashopBundle\Entity\HikashopProduct;
use HikashopBundle\Entity\HikashopFile;
use AppBundle\Entity\Product;



class SyncProductsHikashop extends Command
{
	private $logger;
	private $em;
	private $hkem;
    private $mailer;

    private $mailer_to;
    private $mailer_from;

    private $createdProductsCount;
    private $updatedProductsCount;


    public function __construct(EntityManagerInterface $em, EntityManagerInterface $hkem, LoggerInterface $logger, \Swift_Mailer $mailer)
    {
    	$this->em = $em;
    	$this->hkem = $hkem;
        $this->logger = $logger;
        $this->mailer = $mailer;

        $this->createdProductsCount = 0;
        $this->updatedProductsCount = 0;

        $this->mailer_to = "yk@2dcom.fr";
        $this->mailer_from = "yk@2dcom.fr";

        $this->logStep = 50;

        parent::__construct();
    }

    protected function configure()
    {
        $this
        	->setName('app:sync-products-hikashop')
        	->setDescription('Synchronisation des produits Hikashop.')
        	->setHelp('Cette commande vous permet de mettre à jour la base de produits Hikashop ...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$em = $this->em;
    	$hkem = $this->hkem;
    	$logger = $this->logger;

        set_time_limit(0); 

        $logger->info('----------------------------------------------------------------------');
        $logger->info('PROCEDURE HIKASHOP');
        $logger->info('----------------------------------------------------------------------');
        $logger->info('Lancement de la procédure de synchronisation des produits en cours ...');
        $logger->info('----------------------------------------------------------------------');

        $ddcomProducts = $em->getRepository('AppBundle:Product')->findBy([
            'isDeleted' => '0'
        ]);

        $indice = 0;
        $created_per = 50;

        foreach($ddcomProducts as $ddcomProduct) {

        	// test if product ever existed in hikashop database

        	$hkproduct = $hkem->getRepository('HikashopBundle:HikashopProduct')->findOneBy([
	            'isbn' => $ddcomProduct->getEan()
	        ]);

            $this->createOrUpdateProduct($ddcomProduct, $hkproduct);

            if ($indice > 0 && ($indice%$created_per == 0)) 
            {
                $logger->info('[ETAPE] - Nombre de produits traités : '.$indice.'');
            }
            $indice++;
   		}

        $logger->info('Nombre de produits à ajouter : '.$this->createdProductsCount.'');
        $logger->info('Nombre de produits mis à jour : '.$this->updatedProductsCount.'');
        $logger->info('Synchronisation terminée pour les mises à jour de produits !');
    }

    private function createOrUpdateProduct(Product $ddcomProduct, HikashopProduct $hkproduct = null) 
	{
        $em = $this->em;
        $hkem = $this->hkem;
        $logger = $this->logger;

        // get stock
        // =========

        $stock = $em->getRepository('AppBundle:Stock')->findOneBy(["ean" => $ddcomProduct->getEan()]);
        $stock_qte = 0;
        if (null !== $stock && 0 < $stock->getQuantity())
        {
            $stock_qte = $stock->getQuantity();  
        }

        // get summary
        // ===========

        $summary = $this->getSummary($ddcomProduct->getEan());

        // create hikashop product
        // =======================

        if ($hkproduct === null) {
            $hikashopProduct = new HikashopProduct();
        } else {
            $hikashopProduct = $hkproduct;
        }

        $hikashopProduct->setFromLibrisoft(
            $ddcomProduct->getTitle(), 
            $this->sluggify($ddcomProduct->getTitle()),
            $ddcomProduct->getAuthor(), 
            $ddcomProduct->getPublisher(), 
            $ddcomProduct->getParutionDate(), 
            $summary, 
            $ddcomProduct->getEan(),
            $ddcomProduct->getVat1(),
            $stock_qte,
            $ddcomProduct->getWeight(),            
            $ddcomProduct->getThickness(),             
            $ddcomProduct->getWideness(),             
            $ddcomProduct->getHeight()    
        );

        $hkem->persist($hikashopProduct);
        $hkem->flush();

        // create image from 2dcom url
        // ===========================

        $ean = $ddcomProduct->getEan();
        $this->getCover($hikashopProduct->getId(), $ean);

        if ($hkproduct === null) {
            $this->createdProductsCount ++;
        }
        else {
            $this->updatedProductsCount ++;
        }
	}

    private function getCover($hikashopProductId, $ean, $path = "../hikashop_images/") 
    {
        $hkem = $this->hkem;

        if (!file_exists($path.$ean.'.jpg')) 
        {
        	$image = 'http://bddi.2dcom.fr/LocalImageExists.php?ean='.$ean.'&isize=medium&gencod=3025594728601&key=mZfH7ltnWECPwoED';
        	$content = file_get_contents($image);

        	file_put_contents($path.$ean.'.jpg', $content);

            $hikashopFile = new HikashopFile();
            $hikashopFile->setFromLibrisoft(
                $ean,
                $ean.'.jpg',
                $hikashopProductId
            );

            $hkem->persist($hikashopFile);
            $hkem->flush();
        }
    }

    private function getSummary($ean) 
    {
    	$url = 'http://bddi.2dcom.fr/GetResumeUtf8.php?user=wsbddi&pw=ErG2i8Aj&ean='.$ean;
    	$description = (file_get_contents($url) != 'erreur resume non trouve') ? file_get_contents($url) : '';

    	return $description;
    }

    private function sluggify($string, $separator = '-')
	{
	    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
	    $special_cases = array( '&' => 'and', "'" => '');
	    $string = mb_strtolower( trim( $string ), 'UTF-8' );
	    $string = str_replace( array_keys($special_cases), array_values( $special_cases), $string );
	    $string = preg_replace( $accents_regex, '$1', htmlentities( $string, ENT_QUOTES, 'UTF-8' ) );
	    $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
	    $string = preg_replace("/[$separator]+/u", "$separator", $string);
	    return $string;
	}


}

