<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Client;
use AppBundle\Entity\OrderHeader;
use AppBundle\Entity\OrderLine;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Psr\Log\LoggerInterface;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="home_page")
     */
    public function indexAction(Request $request)
    {

    	$mailer = $this->get('mailer');

    	$message = \Swift_Message::newInstance()
            ->setSubject('[SITE INTERNET] - Synchronisation des commandes à '.date('d/m/Y à H:i:s'))
            ->setFrom('yk@2dcom.fr')
            ->setTo('yk@2dcom.fr')
            ->setBody("Bonjour,\r\n\r\n"."Aucune commande en attente de téléchargement !\r\nA bientôt sur librairiezenobi.com");
        $retour = $mailer->send($message);

        var_dump($retour);

        return $this->render('main/homepage.html.twig');

    }
}
