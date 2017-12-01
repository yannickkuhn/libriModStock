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
        $this->send_mail();
        return $this->render('main/homepage.html.twig');
    }

    private function send_mail($sujet = null, $message_txt = null, $mail = null, $header = null)
    {
        if($mail == null)
            $mail = 'yk@2dcom.fr'; 
        if($sujet == null)
            $sujet = "Test depuis le default controller ... !";
        if($message_txt == null)
            $message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
        
        if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail))
            $passage_ligne = "\r\n";
        else
            $passage_ligne = "\n";

        $message_html = "<html><head></head><body>".$message_txt."</body></html>";
        $boundary = "-----=".md5(rand());
        
        $header = "From: \"Librairie Zenobi\"<admin@librairiezenobi.com>".$passage_ligne;
        $header.= "Reply-to: \"Librairie Zenobi\" <admin@librairiezenobi.com>".$passage_ligne;
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
