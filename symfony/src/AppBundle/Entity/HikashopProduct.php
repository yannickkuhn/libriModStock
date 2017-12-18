<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HikashopProduct
 *
 * @ORM\Table(name="hikashop_product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class HikashopProduct
{
    /**
     * @var int
     *
     * @ORM\Column(name="product_id", columnDefinition="INT(11) NOT NULL")
     * @ORM\Id
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="product_parent_id", columnDefinition="INT(11) NOT NULL DEFAULT '0'")
     */
    private $parentId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="product_description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="product_quantity", columnDefinition="INT(11) NOT NULL DEFAULT '-1'")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="product_code", type="string", length=255, nullable=false)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="product_published", columnDefinition="TINYINT(4) NOT NULL DEFAULT '0'")
     */
    private $published;

    /**
     * @var int
     *
     * @ORM\Column(name="product_hit", columnDefinition="INT(11) NOT NULL DEFAULT '0'")
     */
    private $hit;

    /**
     * @var int
     *
     * @ORM\Column(name="product_created", columnDefinition="INT(11) NOT NULL DEFAULT '0'")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="product_sale_start", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $saleStartAt;

    /**
     * @var int
     *
     * @ORM\Column(name="product_sale_end", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $saleEndAt;

    /**
     * @var int
     *
     * @ORM\Column(name="product_delay_id", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $delayId;

    /**
     * @var int
     *
     * @ORM\Column(name="product_tax_id", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $taxId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="product_vendor_id", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $vendorId;

    /**
     * @var int
     *
     * @ORM\Column(name="product_manufacturer_id", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $manufacturerId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="product_weight", type="decimal", precision=12, scale=3, nullable=false, options={"default":0})
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="product_keywords", type="text", nullable=false)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="product_weight_unit", type="string", length=255, nullable=false, options={"default":"kg"})
     */
    private $weightUnit;

    /**
     * @var int
     *
     * @ORM\Column(name="product_modified", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $modifiedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="product_meta_description", type="string", length=255, nullable=false)
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="product_dimension_unit", type="string", length=255, nullable=false, options={"default":"m"})
     */
    private $dimensionUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="product_width", type="decimal", precision=12, scale=3, nullable=false, options={"default":0})
     */
    private $width;

    /**
     * @var string
     *
     * @ORM\Column(name="product_length", type="decimal", precision=12, scale=3, nullable=false, options={"default":0})
     */
    private $length;

    /**
     * @var string
     *
     * @ORM\Column(name="product_height", type="decimal", precision=12, scale=3, nullable=false, options={"default":0})
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="product_max_per_order", columnDefinition="INT(10) NULL DEFAULT '0'")
     */
    private $maxPerOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="product_access", type="string", length=255, nullable=false, options={"default":"all"})
     */
    private $accessingBy;

    /**
     * @var string
     *
     * @ORM\Column(name="product_group_after_purchase", type="string", length=255, nullable=false)
     */
    private $groupAfterPurchase;

    /**
     * @var string
     *
     * @ORM\Column(name="product_min_per_order", columnDefinition="INT(10) NULL DEFAULT '0'")
     */
    private $minPerOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="product_contact", columnDefinition="SMALLINT(5) NOT NULL DEFAULT '0'")
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="product_display_quantity_field", columnDefinition="SMALLINT(5) NOT NULL DEFAULT '0'")
     */
    private $displayQuantityField;

    /**
     * @var string
     *
     * @ORM\Column(name="product_last_seen_date", columnDefinition="INT(10) NULL DEFAULT '0'")
     */
    private $lastSeenDate;

    /**
     * @var string
     *
     * @ORM\Column(name="product_sales", columnDefinition="INT(10) NULL DEFAULT '0'")
     */
    private $sales;

    /**
     * @var string
     *
     * @ORM\Column(name="product_waitlist", columnDefinition="SMALLINT(5) NOT NULL DEFAULT '0'")
     */
    private $waitlist;

    /**
     * @var string
     *
     * @ORM\Column(name="product_layout", type="string", length=255, nullable=false)
     */
    private $layout;

    /**
     * @var string
     *
     * @ORM\Column(name="product_average_score", columnDefinition="FLOAT NOT NULL")
     */
    private $averageScore;

    /**
     * @var string
     *
     * @ORM\Column(name="product_total_vote", columnDefinition="INT(11) NOT NULL DEFAULT '0'")
     */
    private $totalVote;

    /**
     * @var string
     *
     * @ORM\Column(name="product_page_title", type="string", length=255, nullable=false)
     */
    private $pageTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="product_alias", type="string", length=255, nullable=false)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="product_price_percentage", type="decimal", precision=15, scale=7, nullable=false, options={"default":0})
     */
    private $pricePercentage;

    /**
     * @var string
     *
     * @ORM\Column(name="product_msrp", type="decimal", precision=15, scale=7, nullable=true, options={"default":0})
     */
    private $msrp;

    /**
     * @var string
     *
     * @ORM\Column(name="product_canonical", type="string", length=255, nullable=false)
     */
    private $canonical;

    /**
     * @var string
     *
     * @ORM\Column(name="product_warehouse_id", columnDefinition="INT(10) NOT NULL DEFAULT '0'")
     */
    private $warehouseId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_quantity_layout", type="string", length=255, nullable=false)
     */
    private $quantityLayout;

    /**
     * @var string
     *
     * @ORM\Column(name="product_sort_price", type="decimal", precision=17, scale=5, nullable=false, options={"default":0})
     */
    private $sortPrice;

    /**
     *
     * Constructor.
     *
     */
    function __construct() 
    {
        $this->parentId = 0;

        $this->published = 0;
        $this->hit = 0;
        $this->created = 0;
        $this->saleStartAt = 0;
        $this->saleEndAt = 0;
        $this->delayId = 0;
        $this->taxId = 0;
        //$this->type = ...;
        $this->vendorId = 0;
        $this->manufacturerId = 0;
        //$this->url = ...;
        $this->keywords = "";
        $this->weightUnit = "g";
        $this->modifiedAt = 0;
        $this->metaDescription = "";
        $this->dimensionUnit = "mm";
        $this->maxPerOrder = 0;
        $this->accessingBy = "all";
        $this->groupAfterPurchase = "";     // ??
        $this->minPerOrder = 0;
        $this->contact = 0;
        $this->displayQuantityField = 0;
        $this->lastSeenDate = 0;
        $this->sales = 0;                   // Stats ??
        $this->waitlist = 0;                // Stats ??
        $this->layout = "";
        $this->averageScore = 0;            // Stats ??
        $this->totalVote = 0;               // Stats ??
        $this->pageTitle = "";              // ??
        $this->alias = "";
        $this->pricePercentage = 0;
        $this->msrp = 0;                    // ??
        $this->canonical = "";              // ??
        $this->warehouseId = 0;              // ??
        $this->quantityLayout = "";         // ??
        $this->sortPrice = 0;               // ??


    }

    /**
     *
     * setFromLibrisoft.
     *
     */
    function setFromLibrisoft($name, $description, $quantity, $eanCode, $weight = 0, $width = 0, $length = 0, $height = 0) 
    {
        $this->name = $name;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->code = $eanCode;
        $this->weight = $weight;
        $this->width = $width;
        $this->length = $length;
        $this->height = $height;  
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return HikashopProduct
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set parentId
     *
     * @param string $parentId
     *
     * @return HikashopProduct
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Get parentId
     *
     * @return string
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return HikashopProduct
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return HikashopProduct
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set quantity
     *
     * @param string $quantity
     *
     * @return HikashopProduct
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return HikashopProduct
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set published
     *
     * @param string $published
     *
     * @return HikashopProduct
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return string
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set hit
     *
     * @param string $hit
     *
     * @return HikashopProduct
     */
    public function setHit($hit)
    {
        $this->hit = $hit;

        return $this;
    }

    /**
     * Get hit
     *
     * @return string
     */
    public function getHit()
    {
        return $this->hit;
    }

    /**
     * Set createdAt
     *
     * @param string $createdAt
     *
     * @return HikashopProduct
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
     * Set saleStartAt
     *
     * @param string $saleStartAt
     *
     * @return HikashopProduct
     */
    public function setSaleStartAt($saleStartAt)
    {
        $this->saleStartAt = $saleStartAt;

        return $this;
    }

    /**
     * Get saleStartAt
     *
     * @return string
     */
    public function getSaleStartAt()
    {
        return $this->saleStartAt;
    }

    /**
     * Set saleEndAt
     *
     * @param string $saleEndAt
     *
     * @return HikashopProduct
     */
    public function setSaleEndAt($saleEndAt)
    {
        $this->saleEndAt = $saleEndAt;

        return $this;
    }

    /**
     * Get saleEndAt
     *
     * @return string
     */
    public function getSaleEndAt()
    {
        return $this->saleEndAt;
    }

    /**
     * Set delayId
     *
     * @param string $delayId
     *
     * @return HikashopProduct
     */
    public function setDelayId($delayId)
    {
        $this->delayId = $delayId;

        return $this;
    }

    /**
     * Get delayId
     *
     * @return string
     */
    public function getDelayId()
    {
        return $this->delayId;
    }

    /**
     * Set taxId
     *
     * @param string $taxId
     *
     * @return HikashopProduct
     */
    public function setTaxId($taxId)
    {
        $this->taxId = $taxId;

        return $this;
    }

    /**
     * Get taxId
     *
     * @return string
     */
    public function getTaxId()
    {
        return $this->taxId;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return HikashopProduct
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
     * Set vendorId
     *
     * @param string $vendorId
     *
     * @return HikashopProduct
     */
    public function setVendorId($vendorId)
    {
        $this->vendorId = $vendorId;

        return $this;
    }

    /**
     * Get vendorId
     *
     * @return string
     */
    public function getVendorId()
    {
        return $this->vendorId;
    }

    /**
     * Set manufacturerId
     *
     * @param string $manufacturerId
     *
     * @return HikashopProduct
     */
    public function setManufacturerId($manufacturerId)
    {
        $this->manufacturerId = $manufacturerId;

        return $this;
    }

    /**
     * Get manufacturerId
     *
     * @return string
     */
    public function getManufacturerId()
    {
        return $this->manufacturerId;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return HikashopProduct
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return HikashopProduct
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
     * Set keywords
     *
     * @param string $keywords
     *
     * @return HikashopProduct
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set weightUnit
     *
     * @param string $weightUnit
     *
     * @return HikashopProduct
     */
    public function setWeightUnit($weightUnit)
    {
        $this->weightUnit = $weightUnit;

        return $this;
    }

    /**
     * Get weightUnit
     *
     * @return string
     */
    public function getWeightUnit()
    {
        return $this->weightUnit;
    }

    /**
     * Set modifiedAt
     *
     * @param string $modifiedAt
     *
     * @return HikashopProduct
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
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return HikashopProduct
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set dimensionUnit
     *
     * @param string $dimensionUnit
     *
     * @return HikashopProduct
     */
    public function setDimensionUnit($dimensionUnit)
    {
        $this->dimensionUnit = $dimensionUnit;

        return $this;
    }

    /**
     * Get dimensionUnit
     *
     * @return string
     */
    public function getDimensionUnit()
    {
        return $this->dimensionUnit;
    }

    /**
     * Set width
     *
     * @param string $width
     *
     * @return HikashopProduct
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set length
     *
     * @param string $length
     *
     * @return HikashopProduct
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return string
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set height
     *
     * @param string $height
     *
     * @return HikashopProduct
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set maxPerOrder
     *
     * @param string $maxPerOrder
     *
     * @return HikashopProduct
     */
    public function setMaxPerOrder($maxPerOrder)
    {
        $this->maxPerOrder = $maxPerOrder;

        return $this;
    }

    /**
     * Get maxPerOrder
     *
     * @return string
     */
    public function getMaxPerOrder()
    {
        return $this->maxPerOrder;
    }

    /**
     * Set accessingBy
     *
     * @param string $accessingBy
     *
     * @return HikashopProduct
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
     * Set groupAfterPurchase
     *
     * @param string $groupAfterPurchase
     *
     * @return HikashopProduct
     */
    public function setGroupAfterPurchase($groupAfterPurchase)
    {
        $this->groupAfterPurchase = $groupAfterPurchase;

        return $this;
    }

    /**
     * Get groupAfterPurchase
     *
     * @return string
     */
    public function getGroupAfterPurchase()
    {
        return $this->groupAfterPurchase;
    }

    /**
     * Set minPerOrder
     *
     * @param string $minPerOrder
     *
     * @return HikashopProduct
     */
    public function setMinPerOrder($minPerOrder)
    {
        $this->minPerOrder = $minPerOrder;

        return $this;
    }

    /**
     * Get minPerOrder
     *
     * @return string
     */
    public function getMinPerOrder()
    {
        return $this->minPerOrder;
    }

    /**
     * Set contact
     *
     * @param string $contact
     *
     * @return HikashopProduct
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set displayQuantityField
     *
     * @param string $displayQuantityField
     *
     * @return HikashopProduct
     */
    public function setDisplayQuantityField($displayQuantityField)
    {
        $this->displayQuantityField = $displayQuantityField;

        return $this;
    }

    /**
     * Get displayQuantityField
     *
     * @return string
     */
    public function getDisplayQuantityField()
    {
        return $this->displayQuantityField;
    }

    /**
     * Set lastSeenDate
     *
     * @param string $lastSeenDate
     *
     * @return HikashopProduct
     */
    public function setLastSeenDate($lastSeenDate)
    {
        $this->lastSeenDate = $lastSeenDate;

        return $this;
    }

    /**
     * Get lastSeenDate
     *
     * @return string
     */
    public function getLastSeenDate()
    {
        return $this->lastSeenDate;
    }

    /**
     * Set sales
     *
     * @param string $sales
     *
     * @return HikashopProduct
     */
    public function setSales($sales)
    {
        $this->sales = $sales;

        return $this;
    }

    /**
     * Get sales
     *
     * @return string
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * Set waitlist
     *
     * @param string $waitlist
     *
     * @return HikashopProduct
     */
    public function setWaitlist($waitlist)
    {
        $this->waitlist = $waitlist;

        return $this;
    }

    /**
     * Get waitlist
     *
     * @return string
     */
    public function getWaitlist()
    {
        return $this->waitlist;
    }

    /**
     * Set layout
     *
     * @param string $layout
     *
     * @return HikashopProduct
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get layout
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set averageScore
     *
     * @param string $averageScore
     *
     * @return HikashopProduct
     */
    public function setAverageScore($averageScore)
    {
        $this->averageScore = $averageScore;

        return $this;
    }

    /**
     * Get averageScore
     *
     * @return string
     */
    public function getAverageScore()
    {
        return $this->averageScore;
    }

    /**
     * Set totalVote
     *
     * @param string $totalVote
     *
     * @return HikashopProduct
     */
    public function setTotalVote($totalVote)
    {
        $this->totalVote = $totalVote;

        return $this;
    }

    /**
     * Get totalVote
     *
     * @return string
     */
    public function getTotalVote()
    {
        return $this->totalVote;
    }

    /**
     * Set pageTitle
     *
     * @param string $pageTitle
     *
     * @return HikashopProduct
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }

    /**
     * Get pageTitle
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return HikashopProduct
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set pricePercentage
     *
     * @param string $pricePercentage
     *
     * @return HikashopProduct
     */
    public function setPricePercentage($pricePercentage)
    {
        $this->pricePercentage = $pricePercentage;

        return $this;
    }

    /**
     * Get pricePercentage
     *
     * @return string
     */
    public function getPricePercentage()
    {
        return $this->pricePercentage;
    }

    /**
     * Set msrp
     *
     * @param string $msrp
     *
     * @return HikashopProduct
     */
    public function setMsrp($msrp)
    {
        $this->msrp = $msrp;

        return $this;
    }

    /**
     * Get msrp
     *
     * @return string
     */
    public function getMsrp()
    {
        return $this->msrp;
    }

    /**
     * Set canonical
     *
     * @param string $canonical
     *
     * @return HikashopProduct
     */
    public function setCanonical($canonical)
    {
        $this->canonical = $canonical;

        return $this;
    }

    /**
     * Get canonical
     *
     * @return string
     */
    public function getCanonical()
    {
        return $this->canonical;
    }

    /**
     * Set warehouseId
     *
     * @param string $warehouseId
     *
     * @return HikashopProduct
     */
    public function setWarehouseId($warehouseId)
    {
        $this->warehouseId = $warehouseId;

        return $this;
    }

    /**
     * Get warehouseId
     *
     * @return string
     */
    public function getWarehouseId()
    {
        return $this->warehouseId;
    }

    /**
     * Set quantityLayout
     *
     * @param string $quantityLayout
     *
     * @return HikashopProduct
     */
    public function setQuantityLayout($quantityLayout)
    {
        $this->quantityLayout = $quantityLayout;

        return $this;
    }

    /**
     * Get quantityLayout
     *
     * @return string
     */
    public function getQuantityLayout()
    {
        return $this->quantityLayout;
    }

    /**
     * Set sortPrice
     *
     * @param string $sortPrice
     *
     * @return HikashopProduct
     */
    public function setSortPrice($sortPrice)
    {
        $this->sortPrice = $sortPrice;

        return $this;
    }

    /**
     * Get sortPrice
     *
     * @return string
     */
    public function getSortPrice()
    {
        return $this->sortPrice;
    }
}
