<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class TestCommand extends Command
{
	private $logger;
	private $em;

	public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
    	$this->em = $em;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure()
    {
        $this
        	->setName('app:test')
        	->setDescription('Commande de test.')
        	->setHelp('Cette commande vous permet de tester le bon lancement d\'une tache ...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$em = $this->em;
    	$logger = $this->logger;

        $logger->info('----------------------------------------------------------------------');
        $logger->info('Test de lancement d\'une tache synchronisée ...');
        $logger->info('----------------------------------------------------------------------');
        $output->writeln('ok');
    }

}