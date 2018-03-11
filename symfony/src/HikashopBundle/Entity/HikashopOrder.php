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
     * @ORM\Column(name="order_payment_id", type="string", length=255, nullable=false, options={"default":""})
     */
    private $paymentId;

    /**
     * @var string
     *
     * @ORM\Column(name="order_payment_method", type="string", length=255, nullable=false, options={"default":""})
     */
    private $paymentMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="order_payment_price", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=false})
     */
    private $paymentPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="order_payment_tax", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=false})
     */
    private $paymentTax;

    /**
     * @var string
     *
     * @ORM\Column(name="order_payment_params", type="text", nullable=false)
     */
    private $paymentParams;

    /**
     * @var string
     *
     * @ORM\Column(name="order_shipping_id", type="string", length=255, nullable=false, options={"default":""})
     */
    private $shippingId;

    /**
     * @var string
     *
     * @ORM\Column(name="order_shipping_method", type="string", length=255, nullable=false, options={"default":""})
     */
    private $shippingMethod;

    /**
     * @var string
     *
     * @ORM\Column(name="order_shipping_price", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=false})
     */
    private $shippingPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="order_shipping_tax", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=false})
     */
    private $shippingTax;

    /**
     * @var string
     *
     * @ORM\Column(name="order_shipping_params", type="text", nullable=false)
     */
    private $shippingParams;

    /**
     * @var int
     *
     * @ORM\Column(name="order_partner_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $partnerId;

    /**
     * @var string
     *
     * @ORM\Column(name="order_partner_price", type="decimal", precision=17, scale=5, nullable=false, options={"default":0, "unsigned"=false})
     */
    private $partnerPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="order_partner_paid", columnDefinition=" INT(11) NOT NULL DEFAULT '0'")
     */
    private $partnerPaid;

    /**
     * @var int
     *
     * @ORM\Column(name="order_partner_currency_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $partnerCurrencyId;

    /**
     * @var string
     *
     * @ORM\Column(name="order_ip", type="string", length=255, nullable=false, options={"default":""})
     */
    private $orderIp;

    /**
     * @var string
     *
     * @ORM\Column(name="order_site_id", type="string", length=255, nullable=true, options={"default":""})
     */
    private $orderSiteId;

    /**
     * @var string
     *
     * @ORM\Column(name="order_lang", type="string", length=255, nullable=true, options={"default":""})
     */
    private $orderLang;

    /**
     * @var string
     *
     * @ORM\Column(name="order_token", type="string", length=255, nullable=true, options={"default":""})
     */
    private $orderToken;



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

    	$this->status = '';
    	$this->type = "sale";

    	$this->number = '';

    	$this->createdAt = 0;
    	$this->modifiedAt = 0;

    	$this->invoiceId = 0;
    	$this->invoiceNumber = '';
    	$this->invoiceCreatedAt = 0;

    	$this->currencyId = 0;
    	$this->currencyInfo = "";

    	$this->fullPrice = 0;

    	$this->taxInfo = '';

    	$this->discountCode = '';
    	$this->discountPrice = 0;
    	$this->discountTax = 0;

        $this->paymentId = '';
        $this->paymentMethod = '';
        $this->paymentPrice = 0;
        $this->paymentTax = 0;
    	$this->paymentParams = '';

        $this->shippingId = '';
        $this->shippingMethod = '';
        $this->shippingPrice = 0;
        $this->shippingTax = 0;
        $this->shippingParams = '';

        $this->partnerId = 0;
        $this->partnerPrice = 0;
        $this->partnerPaid = 0;
        $this->partnerCurrencyId = 0;

        $this->orderIp = '';
        $this->orderSiteId = '';
        $this->orderLang = '';
        $this->orderToken = '';
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
     * Set billingAddressId
     *
     * @param string $billingAddressId
     *
     * @return HikashopOrder
     */
    public function setBillingAddressId($billingAddressId)
    {
        $this->billingAddressId = $billingAddressId;

        return $this;
    }

    /**
     * Get billingAddressId
     *
     * @return string
     */
    public function getBillingAddressId()
    {
        return $this->billingAddressId;
    }

    /**
     * Set shippingAddressId
     *
     * @param string $shippingAddressId
     *
     * @return HikashopOrder
     */
    public function setShippingAddressId($shippingAddressId)
    {
        $this->shippingAddressId = $shippingAddressId;

        return $this;
    }

    /**
     * Get shippingAddressId
     *
     * @return string
     */
    public function getShippingAddressId()
    {
        return $this->shippingAddressId;
    }

    /**
     * Set userId
     *
     * @param string $userId
     *
     * @return HikashopOrder
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return HikashopOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return HikashopOrder
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return HikashopOrder
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set createdAt
     *
     * @param string $createdAt
     *
     * @return HikashopOrder
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set modifiedAt
     *
     * @param string $modifiedAt
     *
     * @return HikashopOrder
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return string
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set invoiceId
     *
     * @param string $invoiceId
     *
     * @return HikashopOrder
     */
    public function setInvoiceId($invoiceId)
    {
        $this->invoiceId = $invoiceId;

        return $this;
    }

    /**
     * Get invoiceId
     *
     * @return string
     */
    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

    /**
     * Set invoiceNumber
     *
     * @param string $invoiceNumber
     *
     * @return HikashopOrder
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * Get invoiceNumber
     *
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * Set invoiceCreatedAt
     *
     * @param string $invoiceCreatedAt
     *
     * @return HikashopOrder
     */
    public function setInvoiceCreatedAt($invoiceCreatedAt)
    {
        $this->invoiceCreatedAt = $invoiceCreatedAt;

        return $this;
    }

    /**
     * Get invoiceCreatedAt
     *
     * @return string
     */
    public function getInvoiceCreatedAt()
    {
        return $this->invoiceCreatedAt;
    }

    /**
     * Set currencyId
     *
     * @param string $currencyId
     *
     * @return HikashopOrder
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
     * Set currencyInfo
     *
     * @param string $currencyInfo
     *
     * @return HikashopOrder
     */
    public function setCurrencyInfo($currencyInfo)
    {
        $this->currencyInfo = $currencyInfo;

        return $this;
    }

    /**
     * Get currencyInfo
     *
     * @return string
     */
    public function getCurrencyInfo()
    {
        return $this->currencyInfo;
    }

    /**
     * Set fullPrice
     *
     * @param string $fullPrice
     *
     * @return HikashopOrder
     */
    public function setFullPrice($fullPrice)
    {
        $this->fullPrice = $fullPrice;

        return $this;
    }

    /**
     * Get fullPrice
     *
     * @return string
     */
    public function getFullPrice()
    {
        return $this->fullPrice;
    }

    /**
     * Set taxInfo
     *
     * @param string $taxInfo
     *
     * @return HikashopOrder
     */
    public function setTaxInfo($taxInfo)
    {
        $this->taxInfo = $taxInfo;

        return $this;
    }

    /**
     * Get taxInfo
     *
     * @return string
     */
    public function getTaxInfo()
    {
        return $this->taxInfo;
    }

    /**
     * Set discountCode
     *
     * @param string $discountCode
     *
     * @return HikashopOrder
     */
    public function setDiscountCode($discountCode)
    {
        $this->discountCode = $discountCode;

        return $this;
    }

    /**
     * Get discountCode
     *
     * @return string
     */
    public function getDiscountCode()
    {
        return $this->discountCode;
    }

    /**
     * Set discountPrice
     *
     * @param string $discountPrice
     *
     * @return HikashopOrder
     */
    public function setDiscountPrice($discountPrice)
    {
        $this->discountPrice = $discountPrice;

        return $this;
    }

    /**
     * Get discountPrice
     *
     * @return string
     */
    public function getDiscountPrice()
    {
        return $this->discountPrice;
    }

    /**
     * Set discounTax
     *
     * @param string $discounTax
     *
     * @return HikashopOrder
     */
    public function setDiscounTax($discounTax)
    {
        $this->discounTax = $discounTax;

        return $this;
    }

    /**
     * Get discounTax
     *
     * @return string
     */
    public function getDiscounTax()
    {
        return $this->discounTax;
    }

    /**
     * Set paymentId
     *
     * @param string $paymentId
     *
     * @return HikashopOrder
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * Get paymentId
     *
     * @return string
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * Set paymentMethod
     *
     * @param string $paymentMethod
     *
     * @return HikashopOrder
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set paymentPrice
     *
     * @param string $paymentPrice
     *
     * @return HikashopOrder
     */
    public function setPaymentPrice($paymentPrice)
    {
        $this->paymentPrice = $paymentPrice;

        return $this;
    }

    /**
     * Get paymentPrice
     *
     * @return string
     */
    public function getPaymentPrice()
    {
        return $this->paymentPrice;
    }

    /**
     * Set paymentTax
     *
     * @param string $paymentTax
     *
     * @return HikashopOrder
     */
    public function setPaymentTax($paymentTax)
    {
        $this->paymentTax = $paymentTax;

        return $this;
    }

    /**
     * Get paymentTax
     *
     * @return string
     */
    public function getPaymentTax()
    {
        return $this->paymentTax;
    }

    /**
     * Set paymentParams
     *
     * @param string $paymentParams
     *
     * @return HikashopOrder
     */
    public function setPaymentParams($paymentParams)
    {
        $this->paymentParams = $paymentParams;

        return $this;
    }

    /**
     * Get paymentParams
     *
     * @return string
     */
    public function getPaymentParams()
    {
        return $this->paymentParams;
    }

    /**
     * Set shippingId
     *
     * @param string $shippingId
     *
     * @return HikashopOrder
     */
    public function setShippingId($shippingId)
    {
        $this->shippingId = $shippingId;

        return $this;
    }

    /**
     * Get shippingId
     *
     * @return string
     */
    public function getShippingId()
    {
        return $this->shippingId;
    }

    /**
     * Set shippingMethod
     *
     * @param string $shippingMethod
     *
     * @return HikashopOrder
     */
    public function setShippingMethod($shippingMethod)
    {
        $this->shippingMethod = $shippingMethod;

        return $this;
    }

    /**
     * Get shippingMethod
     *
     * @return string
     */
    public function getShippingMethod()
    {
        return $this->shippingMethod;
    }

    /**
     * Set shippingPrice
     *
     * @param string $shippingPrice
     *
     * @return HikashopOrder
     */
    public function setShippingPrice($shippingPrice)
    {
        $this->shippingPrice = $shippingPrice;

        return $this;
    }

    /**
     * Get shippingPrice
     *
     * @return string
     */
    public function getShippingPrice()
    {
        return $this->shippingPrice;
    }

    /**
     * Set shippingTax
     *
     * @param string $shippingTax
     *
     * @return HikashopOrder
     */
    public function setShippingTax($shippingTax)
    {
        $this->shippingTax = $shippingTax;

        return $this;
    }

    /**
     * Get shippingTax
     *
     * @return string
     */
    public function getShippingTax()
    {
        return $this->shippingTax;
    }

    /**
     * Set shippingParams
     *
     * @param string $shippingParams
     *
     * @return HikashopOrder
     */
    public function setShippingParams($shippingParams)
    {
        $this->shippingParams = $shippingParams;

        return $this;
    }

    /**
     * Get shippingParams
     *
     * @return string
     */
    public function getShippingParams()
    {
        return $this->shippingParams;
    }

    /**
     * Set partnerId
     *
     * @param string $partnerId
     *
     * @return HikashopOrder
     */
    public function setPartnerId($partnerId)
    {
        $this->partnerId = $partnerId;

        return $this;
    }

    /**
     * Get partnerId
     *
     * @return string
     */
    public function getPartnerId()
    {
        return $this->partnerId;
    }

    /**
     * Set partnerPrice
     *
     * @param string $partnerPrice
     *
     * @return HikashopOrder
     */
    public function setPartnerPrice($partnerPrice)
    {
        $this->partnerPrice = $partnerPrice;

        return $this;
    }

    /**
     * Get partnerPrice
     *
     * @return string
     */
    public function getPartnerPrice()
    {
        return $this->partnerPrice;
    }

    /**
     * Set partnerPaid
     *
     * @param string $partnerPaid
     *
     * @return HikashopOrder
     */
    public function setPartnerPaid($partnerPaid)
    {
        $this->partnerPaid = $partnerPaid;

        return $this;
    }

    /**
     * Get partnerPaid
     *
     * @return string
     */
    public function getPartnerPaid()
    {
        return $this->partnerPaid;
    }

    /**
     * Set partnerCurrencyId
     *
     * @param string $partnerCurrencyId
     *
     * @return HikashopOrder
     */
    public function setPartnerCurrencyId($partnerCurrencyId)
    {
        $this->partnerCurrencyId = $partnerCurrencyId;

        return $this;
    }

    /**
     * Get partnerCurrencyId
     *
     * @return string
     */
    public function getPartnerCurrencyId()
    {
        return $this->partnerCurrencyId;
    }

    /**
     * Set orderIp
     *
     * @param string $orderIp
     *
     * @return HikashopOrder
     */
    public function setOrderIp($orderIp)
    {
        $this->orderIp = $orderIp;

        return $this;
    }

    /**
     * Get orderIp
     *
     * @return string
     */
    public function getOrderIp()
    {
        return $this->orderIp;
    }

    /**
     * Set orderSiteId
     *
     * @param string $orderSiteId
     *
     * @return HikashopOrder
     */
    public function setOrderSiteId($orderSiteId)
    {
        $this->orderSiteId = $orderSiteId;

        return $this;
    }

    /**
     * Get orderSiteId
     *
     * @return string
     */
    public function getOrderSiteId()
    {
        return $this->orderSiteId;
    }

    /**
     * Set orderLang
     *
     * @param string $orderLang
     *
     * @return HikashopOrder
     */
    public function setOrderLang($orderLang)
    {
        $this->orderLang = $orderLang;

        return $this;
    }

    /**
     * Get orderLang
     *
     * @return string
     */
    public function getOrderLang()
    {
        return $this->orderLang;
    }

    /**
     * Set orderToken
     *
     * @param string $orderToken
     *
     * @return HikashopOrder
     */
    public function setOrderToken($orderToken)
    {
        $this->orderToken = $orderToken;

        return $this;
    }

    /**
     * Get orderToken
     *
     * @return string
     */
    public function getOrderToken()
    {
        return $this->orderToken;
    }
}
