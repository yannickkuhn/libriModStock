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



class SyncProductsHikashop extends Command
{
	private $logger;
	private $em;
	private $hkem;
    private $mailer;

    private $mailer_to;
    private $mailer_from;

    public function __construct(EntityManagerInterface $em, EntityManagerInterface $hkem, LoggerInterface $logger, \Swift_Mailer $mailer)
    {
    	$this->em = $em;
    	$this->hkem = $hkem;
        $this->logger = $logger;
        $this->mailer = $mailer;

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
        set_time_limit(0); 

        $this->logger->info('----------------------------------------------------------------------');
        $this->logger->info('PROCEDURE HIKASHOP');
        $this->logger->info('----------------------------------------------------------------------');
        $this->logger->info('Lancement de la procédure de synchronisation des produits en cours ...');
        $this->logger->info('----------------------------------------------------------------------');

        // create image from 2dcom url

        $ean = "1122334455";
        $this->get_image($ean);

        // create product

        $hikashopProduct = new HikashopProduct();
        $hikashopProduct->setFromLibrisoft(
            "Petit traité de manipulation", 
            $this->sluggify("Petit traité de manipulation"),
            "Robert-Vincent Joule, Jean-Léon Beauvois", 
            "PUG", 
            "2014", 
            "<p>Le Petit traité de manipulation à l'usage des honnêtes gens est un essai de psychologie sociale de Robert-Vincent Joule et Jean-Léon Beauvois paru en 1987 et réédité en 2002 puis en 2014 aux Presses universitaires de Grenoble.</p>", 
            "1122334455", 
            5.5,            // vat in %
            -1,             // quantity
            100,            // weight
            5,              // width
            10,             // length
            15              // height
        );

        $this->hkem->persist($hikashopProduct);
        $this->hkem->flush();

        $this->logger->info('Fin de la synchronisation !');
    }

    private function get_image($ean, $path = "../hikashop_images/") 
    {
    	$image = 'http://bddi.2dcom.fr/LocalImageExists.php?ean='.$ean.'&isize=medium&gencod=3025594728601&key=mZfH7ltnWECPwoED';
    	$content = file_get_contents($image);
    	file_put_contents($path.$ean.'.jpg', $content);
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

