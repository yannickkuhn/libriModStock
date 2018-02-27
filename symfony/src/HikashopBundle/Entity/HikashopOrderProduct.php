<?php

namespace HikashopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HikashopOrderProduct
 *
 * @ORM\Table(name="c0syc_hikashop_order_product")
 * @ORM\Entity(repositoryClass="HikashopBundle\Repository\HikashopOrderProductRepository")
 */
class HikashopOrderProduct
{
    /**
     * @var int
     *
     * @ORM\Column(name="order_product_id", columnDefinition="INT(10) UNSIGNED NOT NULL AUTO_INCREMENT")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="order_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $orderId;

    /**
     * @var int
     *
     * @ORM\Column(name="product_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $productId;

    /**
     * @var int
     *
     * @ORM\Column(name="order_product_quantity", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '1'")
     */
    private $productQuantity;

    /**
     * @var string
     *
     * @ORM\Column(name="order_product_name", type="string", length=255, nullable=true)
     */
    private $productName;

    /**
     * @var string
     *
     * @ORM\Column(name="order_product_code", type="string", length=255, nullable=true)
     */
    private $productCode;

    /**
     * @var string
     *
     * @ORM\Column(name="order_product_price", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=false})
     */
    private $productPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="order_product_tax", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=false})
     */
    private $productTax;

    /**
     * @var string
     *
     * @ORM\Column(name="order_product_tax_info", type="text", nullable=false)
     */
    private $productTaxInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="order_product_options", type="text", nullable=false)
     */
    private $productOptions;

    /**
     * @var int
     *
     * @ORM\Column(name="order_product_option_parent_id", columnDefinition=" INT(10) UNSIGNED NULL DEFAULT '0'")
     */
    private $productOptionParentId;

    /**
     * @var int
     *
     * @ORM\Column(name="order_product_wishlist_id", columnDefinition=" INT(11) NOT UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $productWishListId;

    /**
     * @var string
     *
     * @ORM\Column(name="order_product_shipping_id", type="string", length=255, nullable=true)
     */
    private $productShippingId;

    /**
     * @var string
     *
     * @ORM\Column(name="order_product_shipping_method", type="string", length=255, nullable=true)
     */
    private $productShippingMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="order_product_shipping_price", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=false})
     */
    private $productShippingPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="order_product_shipping_tax", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=false})
     */
    private $productShippingTax;

    /**
     * @var string
     *
     * @ORM\Column(name="order_product_shipping_params", type="text", nullable=false)
     */
    private $productShippingParams;

    /**
     * @var string
     *
     * @ORM\Column(name="order_product_status", type="string", length=255, nullable=true)
     */
    private $productStatus;

    /**
     * @var int
     *
     * @ORM\Column(name="order_product_wishlist_product_id", columnDefinition=" INT(11) NOT UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $productWishListProductId;

    /**
     *
     * Constructor.
     *
     */
    function __construct() 
    {
        $this->orderId = 0;
        $this->productId = 0;

        $this->productQuantity = 1;

        $this->productName = null;
        $this->productCode = null;
        $this->productPrice = 0;

        $this->productTax = 0;
        $this->productTaxInfo = "";

        $this->productOptions = "";
        $this->productOptionParentId = 0;
        $this->productWishListId = 0;

        $this->productShippingId = null;
        $this->productShippingMethod = null;

        $this->productShippingPrice = 0;
        $this->productShippingTax = 0;
        $this->productShippingParams = "";

        $this->productStatus = null;
        $this->productWishListProductId = 0;
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
     * Set orderId
     *
     * @param string $orderId
     *
     * @return HikashopOrderProduct
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set productId
     *
     * @param string $productId
     *
     * @return HikashopOrderProduct
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
     * Set productQuantity
     *
     * @param string $productQuantity
     *
     * @return HikashopOrderProduct
     */
    public function setProductQuantity($productQuantity)
    {
        $this->productQuantity = $productQuantity;

        return $this;
    }

    /**
     * Get productQuantity
     *
     * @return string
     */
    public function getProductQuantity()
    {
        return $this->productQuantity;
    }

    /**
     * Set productName
     *
     * @param string $productName
     *
     * @return HikashopOrderProduct
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set productCode
     *
     * @param string $productCode
     *
     * @return HikashopOrderProduct
     */
    public function setProductCode($productCode)
    {
        $this->productCode = $productCode;

        return $this;
    }

    /**
     * Get productCode
     *
     * @return string
     */
    public function getProductCode()
    {
        return $this->productCode;
    }

    /**
     * Set productPrice
     *
     * @param string $productPrice
     *
     * @return HikashopOrderProduct
     */
    public function setProductPrice($productPrice)
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    /**
     * Get productPrice
     *
     * @return string
     */
    public function getProductPrice()
    {
        return $this->productPrice;
    }

    /**
     * Set productTax
     *
     * @param string $productTax
     *
     * @return HikashopOrderProduct
     */
    public function setProductTax($productTax)
    {
        $this->productTax = $productTax;

        return $this;
    }

    /**
     * Get productTax
     *
     * @return string
     */
    public function getProductTax()
    {
        return $this->productTax;
    }

    /**
     * Set productTaxInfo
     *
     * @param string $productTaxInfo
     *
     * @return HikashopOrderProduct
     */
    public function setProductTaxInfo($productTaxInfo)
    {
        $this->productTaxInfo = $productTaxInfo;

        return $this;
    }

    /**
     * Get productTaxInfo
     *
     * @return string
     */
    public function getProductTaxInfo()
    {
        return $this->productTaxInfo;
    }

    /**
     * Set productOptions
     *
     * @param string $productOptions
     *
     * @return HikashopOrderProduct
     */
    public function setProductOptions($productOptions)
    {
        $this->productOptions = $productOptions;

        return $this;
    }

    /**
     * Get productOptions
     *
     * @return string
     */
    public function getProductOptions()
    {
        return $this->productOptions;
    }

    /**
     * Set productOptionParentId
     *
     * @param string $productOptionParentId
     *
     * @return HikashopOrderProduct
     */
    public function setProductOptionParentId($productOptionParentId)
    {
        $this->productOptionParentId = $productOptionParentId;

        return $this;
    }

    /**
     * Get productOptionParentId
     *
     * @return string
     */
    public function getProductOptionParentId()
    {
        return $this->productOptionParentId;
    }

    /**
     * Set productWishListId
     *
     * @param string $productWishListId
     *
     * @return HikashopOrderProduct
     */
    public function setProductWishListId($productWishListId)
    {
        $this->productWishListId = $productWishListId;

        return $this;
    }

    /**
     * Get productWishListId
     *
     * @return string
     */
    public function getProductWishListId()
    {
        return $this->productWishListId;
    }

    /**
     * Set productShippingId
     *
     * @param string $productShippingId
     *
     * @return HikashopOrderProduct
     */
    public function setProductShippingId($productShippingId)
    {
        $this->productShippingId = $productShippingId;

        return $this;
    }

    /**
     * Get productShippingId
     *
     * @return string
     */
    public function getProductShippingId()
    {
        return $this->productShippingId;
    }

    /**
     * Set productShippingMethod
     *
     * @param string $productShippingMethod
     *
     * @return HikashopOrderProduct
     */
    public function setProductShippingMethod($productShippingMethod)
    {
        $this->productShippingMethod = $productShippingMethod;

        return $this;
    }

    /**
     * Get productShippingMethod
     *
     * @return string
     */
    public function getProductShippingMethod()
    {
        return $this->productShippingMethod;
    }

    /**
     * Set productShippingPrice
     *
     * @param string $productShippingPrice
     *
     * @return HikashopOrderProduct
     */
    public function setProductShippingPrice($productShippingPrice)
    {
        $this->productShippingPrice = $productShippingPrice;

        return $this;
    }

    /**
     * Get productShippingPrice
     *
     * @return string
     */
    public function getProductShippingPrice()
    {
        return $this->productShippingPrice;
    }

    /**
     * Set productShippingTax
     *
     * @param string $productShippingTax
     *
     * @return HikashopOrderProduct
     */
    public function setProductShippingTax($productShippingTax)
    {
        $this->productShippingTax = $productShippingTax;

        return $this;
    }

    /**
     * Get productShippingTax
     *
     * @return string
     */
    public function getProductShippingTax()
    {
        return $this->productShippingTax;
    }

    /**
     * Set productShippingParams
     *
     * @param string $productShippingParams
     *
     * @return HikashopOrderProduct
     */
    public function setProductShippingParams($productShippingParams)
    {
        $this->productShippingParams = $productShippingParams;

        return $this;
    }

    /**
     * Get productShippingParams
     *
     * @return string
     */
    public function getProductShippingParams()
    {
        return $this->productShippingParams;
    }

    /**
     * Set productStatus
     *
     * @param string $productStatus
     *
     * @return HikashopOrderProduct
     */
    public function setProductStatus($productStatus)
    {
        $this->productStatus = $productStatus;

        return $this;
    }

    /**
     * Get productStatus
     *
     * @return string
     */
    public function getProductStatus()
    {
        return $this->productStatus;
    }

    /**
     * Set productWishListProductId
     *
     * @param string $productWishListProductId
     *
     * @return HikashopOrderProduct
     */
    public function setProductWishListProductId($productWishListProductId)
    {
        $this->productWishListProductId = $productWishListProductId;

        return $this;
    }

    /**
     * Get productWishListProductId
     *
     * @return string
     */
    public function getProductWishListProductId()
    {
        return $this->productWishListProductId;
    }
}
