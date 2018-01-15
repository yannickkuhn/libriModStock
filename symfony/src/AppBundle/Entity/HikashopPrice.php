<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HikashopPrice
 *
 * @ORM\Table(name="hikashop_price")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HikashopPriceRepository")
 */
class HikashopPrice
{
    /**
     * @var int
     *
     * @ORM\Column(name="price_id", columnDefinition="INT(10) UNSIGNED NOT NULL AUTO_INCREMENT")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="price_currency_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $currencyId;

    /**
     * @var int
     *
     * @ORM\Column(name="price_product_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $productId;

    /**
     * @var string
     *
     * @ORM\Column(name="price_value", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=false})
     */
    private $value;

    /**
     * @var int
     *
     * @ORM\Column(name="price_min_quantity", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $minQuantity;

    /**
     * @var string
     *
     * @ORM\Column(name="price_access", type="string", length=255, nullable=false, options={"default":"all"})
     */
    private $accessingBy;

    /**
     * @var string
     *
     * @ORM\Column(name="price_site_id", type="string", length=255, nullable=true)
     */
    private $siteId;

    /**
     * @var string
     *
     * @ORM\Column(name="price_users", type="string", length=255, nullable=false)
     */
    private $users;

    /**
     *
     * Constructor.
     *
     */
    function __construct() 
    {
    	$this->currencyId = 1;
    	$this->minQuantity = 0;
    	$this->accessingBy = "all";
    	$this->siteId = "";
    	$this->users = "";
    }

    /**
     *
     * setFromLibrisoft.
     *
     */
    function setFromLibrisoft($productId, $value, $currencyId = 1) 
    {
    	$this->productId = $productId;
    	$this->value = $value;
    	$this->currencyId = $currencyId;	// 1 => € (EUR)
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set currencyId
     *
     * @param string $currencyId
     *
     * @return HikashopPrice
     */
    public function setCurrencyId($currencyId)
    {
        $this->currencyId = $currencyId;

        return $this;
    }

    /**
     * Get currencyId
     *
     * @return string
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    /**
     * Set productId
     *
     * @param string $productId
     *
     * @return HikashopPrice
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return HikashopPrice
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set minQuantity
     *
     * @param string $minQuantity
     *
     * @return HikashopPrice
     */
    public function setMinQuantity($minQuantity)
    {
        $this->minQuantity = $minQuantity;

        return $this;
    }

    /**
     * Get minQuantity
     *
     * @return string
     */
    public function getMinQuantity()
    {
        return $this->minQuantity;
    }

    /**
     * Set accessingBy
     *
     * @param string $accessingBy
     *
     * @return HikashopPrice
     */
    public function setAccessingBy($accessingBy)
    {
        $this->accessingBy = $accessingBy;

        return $this;
    }

    /**
     * Get accessingBy
     *
     * @return string
     */
    public function getAccessingBy()
    {
        return $this->accessingBy;
    }

    /**
     * Set siteId
     *
     * @param string $siteId
     *
     * @return HikashopPrice
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * Get siteId
     *
     * @return string
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * Set users
     *
     * @param string $users
     *
     * @return HikashopPrice
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * Get users
     *
     * @return string
     */
    public function getUsers()
    {
        return $this->users;
    }
}
