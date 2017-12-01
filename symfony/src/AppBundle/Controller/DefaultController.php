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

    	/*$mailer = $this->get('mailer');
    	$message = \Swift_Message::newInstance()
            ->setSubject('[SITE INTERNET] - Synchronisation des commandes à '.date('d/m/Y à H:i:s'))
            ->setFrom('yk@2dcom.fr')
            ->setTo('yk@2dcom.fr')
            ->setBody("Bonjour,\r\n\r\n"."Aucune commande en attente de téléchargement !\r\nA bientôt sur librairiezenobi.com");
        $retour = $mailer->send($message);*/

        $this->send_mail();

        return $this->render('main/homepage.html.twig');

    }

    private function send_mail($mail = null, $sujet = null, $message = null, $header = null)
    {
        $mail = 'yk@2dcom.fr'; 
        if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) {
            $passage_ligne = "\r\n";
        }
        else {
            $passage_ligne = "\n";
        }
        $message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
        $message_html = "<html><head></head><body><b>Salut à tous</b>, voici un e-mail envoyé par un <i>script PHP</i>.</body></html>";
        $boundary = "-----=".md5(rand());
        $sujet = "Test depuis le default controller ... !";
        $header = "From: \"WeaponsB\"<weaponsb@mail.fr>".$passage_ligne;
        $header.= "Reply-to: \"WeaponsB\" <yk@2dcom.fr>".$passage_ligne;
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
