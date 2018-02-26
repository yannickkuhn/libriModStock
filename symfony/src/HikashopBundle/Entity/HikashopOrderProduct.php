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

}
