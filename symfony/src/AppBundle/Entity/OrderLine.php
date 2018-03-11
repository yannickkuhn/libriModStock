<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderLine
 *
 * @ORM\Table(name="commande_lignes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderLineRepository")
 */
class OrderLine
{
    /**
     * @var int
     *
     * @ORM\Column(name="cmd_lig_autoid", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_ent_autoid", type="integer")
     */
    private $orderHeader;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_lig_qte", columnDefinition="INT(10) NOT NULL")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_lig_prixttc", type="decimal", precision=10, scale=2)
     */
    private $netPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_lig_prixpromo", type="decimal", precision=10, scale=2, options={"default":0})
     */
    private $promotionPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_lig_tva", type="decimal", precision=4, scale=2, options={"default":0})
     */
    private $totalVat;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_lig_remise", columnDefinition="INT(1) NOT NULL DEFAULT 0")
     */
    private $discountPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_lig_promo", columnDefinition="INT(1) NULL")
     */
    private $isPromote;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_lig_stock", columnDefinition="INT(5) NULL")
     */
    private $stock;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_lig_dispo_fourn", columnDefinition="INT(1) NOT NULL DEFAULT 0")
     */
    private $providerAvailability;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_lig_ean", type="string", length=13, nullable=true)
     */
    private $ean;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_lig_glndistrib", type="string", length=13, nullable=true)
     */
    private $distributorGln;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_lig_isNum", columnDefinition="INT(1) NOT NULL DEFAULT 0")
     */
    private $isNumeric;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_lig_titre", type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_lig_auteur", type="string", length=100, nullable=true)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_lig_editeur", type="string", length=100, nullable=true)
     */
    private $publisher;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_lig_poids", type="string", length=5, options={"default":0})
     */
    private $weight;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_lig_corres_checked", columnDefinition="INT(11) NOT NULL DEFAULT 0")
     */
    private $isChecked;

    /**
     *
     * Constructor.
     *
     */
    function __construct() 
    {
        $this->promotionPrice       = 0;
        $this->totalVat             = 0;
        $this->providerAvailability = 0;
        $this->isNumeric            = 0;
        $this->distributorGln       = 0;
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return OrderLine
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set orderHeader
     *
     * @param integer $orderHeader
     *
     * @return OrderLine
     */
    public function setOrderHeader($orderHeader)
    {
        $this->orderHeader = $orderHeader;

        return $this;
    }

    /**
     * Get orderHeader
     *
     * @return int
     */
    public function getOrderHeader()
    {
        return $this->orderHeader;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return OrderLine
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set netPrice
     *
     * @param string $netPrice
     *
     * @return OrderLine
     */
    public function setNetPrice($netPrice)
    {
        $this->netPrice = $netPrice;

        return $this;
    }

    /**
     * Get netPrice
     *
     * @return string
     */
    public function getNetPrice()
    {
        return $this->netPrice;
    }

    /**
     * Set promotionPrice
     *
     * @param string $promotionPrice
     *
     * @return OrderLine
     */
    public function setPromotionPrice($promotionPrice)
    {
        $this->promotionPrice = $promotionPrice;

        return $this;
    }

    /**
     * Get promotionPrice
     *
     * @return string
     */
    public function getPromotionPrice()
    {
        return $this->promotionPrice;
    }

    /**
     * Set totalVat
     *
     * @param string $totalVat
     *
     * @return OrderLine
     */
    public function setTotalVat($totalVat)
    {
        $this->totalVat = $totalVat;

        return $this;
    }

    /**
     * Get totalVat
     *
     * @return string
     */
    public function getTotalVat()
    {
        return $this->totalVat;
    }

    /**
     * Set discountPrice
     *
     * @param integer $discountPrice
     *
     * @return OrderLine
     */
    public function setDiscountPrice($discountPrice)
    {
        $this->discountPrice = $discountPrice;

        return $this;
    }

    /**
     * Get discountPrice
     *
     * @return int
     */
    public function getDiscountPrice()
    {
        return $this->discountPrice;
    }

    /**
     * Set isPromote
     *
     * @param integer $isPromote
     *
     * @return OrderLine
     */
    public function setIsPromote($isPromote)
    {
        $this->isPromote = $isPromote;

        return $this;
    }

    /**
     * Get isPromote
     *
     * @return int
     */
    public function getIsPromote()
    {
        return $this->isPromote;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return OrderLine
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set providerAvailability
     *
     * @param integer $providerAvailability
     *
     * @return OrderLine
     */
    public function setProviderAvailability($providerAvailability)
    {
        $this->providerAvailability = $providerAvailability;

        return $this;
    }

    /**
     * Get providerAvailability
     *
     * @return int
     */
    public function getProviderAvailability()
    {
        return $this->providerAvailability;
    }

    /**
     * Set ean
     *
     * @param string $ean
     *
     * @return OrderLine
     */
    public function setEan($ean)
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * Get ean
     *
     * @return string
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * Set distributorGln
     *
     * @param string $distributorGln
     *
     * @return OrderLine
     */
    public function setDistributorGln($distributorGln)
    {
        $this->distributorGln = $distributorGln;

        return $this;
    }

    /**
     * Get distributorGln
     *
     * @return string
     */
    public function getDistributorGln()
    {
        return $this->distributorGln;
    }

    /**
     * Set isNumeric
     *
     * @param integer $isNumeric
     *
     * @return OrderLine
     */
    public function setIsNumeric($isNumeric)
    {
        $this->isNumeric = $isNumeric;

        return $this;
    }

    /**
     * Get isNumeric
     *
     * @return int
     */
    public function getIsNumeric()
    {
        return $this->isNumeric;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return OrderLine
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return OrderLine
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set publisher
     *
     * @param string $publisher
     *
     * @return OrderLine
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get publisher
     *
     * @return string
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return OrderLine
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set isChecked
     *
     * @param integer $isChecked
     *
     * @return OrderLine
     */
    public function setIsChecked($isChecked)
    {
        $this->isChecked = $isChecked;

        return $this;
    }

    /**
     * Get isChecked
     *
     * @return int
     */
    public function getIsChecked()
    {
        return $this->isChecked;
    }
}
