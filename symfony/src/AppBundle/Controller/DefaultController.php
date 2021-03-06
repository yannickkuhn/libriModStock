<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use HikashopBundle\Entity\HikashopProduct;
use HikashopBundle\Entity\HikashopPrice;
use HikashopBundle\Entity\HikashopCategory;
use HikashopBundle\Entity\HikashopProductCategory;
use HikashopBundle\Entity\HikashopTax;
use HikashopBundle\Entity\HikashopFile;

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
        return $this->render('main/homepage.html.twig');
    }

    /**
     * @Route("/hikashop/product/new", name="productTestCreate_page")
     */
    public function productTestCreateAction(Request $request)
    {

        // USE HIKASHOP CONNECTION
        // ------------------------

        $em = $this->getDoctrine()->getManager('hikashop');

        // 1 - SET PRODUCT
        // ---------------

        $hikashopProduct = new HikashopProduct();
        $hikashopProduct->setFromLibrisoft(
            "Petit traité de manipulation", 
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

        // 2 - SET PRICE
        // ---------------

        $hikashopPrice = new HikashopPrice();
        $hikashopPrice->setFromLibrisoft(
            "1",
            "19.90521"
        );

        // 3 - SET CATEGORY
        // ---------------

        $hikashopCategory = new HikashopCategory();
        $hikashopCategory->setFromLibrisoft(
            "Psychologie du travail, des organisations et du personnel"
        );

        $em->persist($hikashopCategory);
        $em->persist($hikashopProduct);
        $em->persist($hikashopPrice);
        $em->flush();

        $hikashopCategoryId = $hikashopCategory->getId();
        $hikashopProductId = $hikashopProduct->getId();



        // 5 - LINK PRODUCT/CATEGORY
        // -------------------------

        $hikashopProductCategory = new HikashopProductCategory();
        $hikashopProductCategory->setFromLibrisoft(
            $hikashopCategoryId, 
            $hikashopProductId
        );

        // 6 - SET FILE
        // ------------------
        
        $hikashopFile = new HikashopFile();
        $hikashopFile->setFromLibrisoft(
            'petit-traite',
            'petit-traite.jpg',
            $hikashopProductId
        );

        $em->persist($hikashopProductCategory);
        $em->persist($hikashopFile);
        $em->flush();


        var_dump('création OK');
        die();
    }
}
