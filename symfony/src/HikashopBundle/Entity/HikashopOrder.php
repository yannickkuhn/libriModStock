<?php

namespace HikashopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HikashopOrderProduct
 *
 * @ORM\Table(name="c0syc_hikashop_order")
 * @ORM\Entity(repositoryClass="HikashopBundle\Repository\HikashopOrderRepository")
 */
class HikashopOrder
{
    /**
     * @var int
     *
     * @ORM\Column(name="order_id", columnDefinition="INT(10) UNSIGNED NOT NULL AUTO_INCREMENT")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="order_billing_address_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $billingAddressId;

    /**
     * @var int
     *
     * @ORM\Column(name="order_shipping_address_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $shippingAddressId;

    /**
     * @var int
     *
     * @ORM\Column(name="order_user_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="order_status", type="string", length=255, nullable=false, options={"default":""})
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="order_type", type="string", length=255, nullable=false, options={"default":"sale"})
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="order_number", type="string", length=255, nullable=false, options={"default":""})
     */
    private $number;

    /**
     * @var int
     *
     * @ORM\Column(name="order_created", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="order_modified", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $modifiedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="order_invoice_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $invoiceId;

    /**
     * @var string
     *
     * @ORM\Column(name="order_invoice_number", type="string", length=255, nullable=false, options={"default":""})
     */
    private $invoiceNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="order_invoice_created", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $invoiceCreatedAt;

    /**
     * @var int
     *
     * @ORM\Column(name="order_currency_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $currencyId;

    /**
     * @var string
     *
     * @ORM\Column(name="order_currency_info", type="text", nullable=false)
     */
    private $currencyInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="order_full_price", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=false})
     */
    private $fullPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="order_tax_info", type="text", nullable=false)
     */
    private $taxInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="order_discount_code", type="string", length=255, nullable=false, options={"default":""})
     */
    private $discountCode;

    /**
     * @var string
     *
     * @ORM\Column(name="order_discount_price", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=false})
     */
    private $discountPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="order_discount_tax", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=false})
     */
    private $discounTax;

    /**
     * @var string
     *
     * @ORM\Column(name="order_payment_params", type="text", nullable=false)
     */
    private $paymentParams;


    /**
     *
     * Constructor.
     *
     */
    function __construct() 
    {
    	$this->billingAddressId = 0;
    	$this->shippingAddressId = 0;
    	$this->userId = 0;

    	$this->status = "";
    	$this->type = "sale";

    	$this->number = "";

    	$this->createdAt = 0;
    	$this->modifiedAt = 0;

    	$this->invoiceId = 0;
    	$this->invoiceNumber = "";
    	$this->invoiceCreatedAt = 0;

    	$this->currencyId = 0;
    	$this->currencyInfo = "";

    	$this->fullPrice = 0;

    	$this->taxInfo = "";
    	$this->discountCode = "";

    	$this->discountPrice = 0;
    	$this->discountTax = 0;

    	$this->paymentParams = "";
    }