<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="produits")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="prod_id", columnDefinition="INT(11) NOT NULL")
     * @ORM\Id
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="rub_1_id", columnDefinition="INT(5) NOT NULL DEFAULT '0'")
     */
    private $category1;

    /**
     * @var int
     *
     * @ORM\Column(name="rub_2_id", columnDefinition="INT(5) NOT NULL DEFAULT '0'")
     */
    private $category2;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_etat_id", columnDefinition="INT(2) NOT NULL DEFAULT '0'")
     */
    private $stateId;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_occasion", columnDefinition="INT(1) NOT NULL DEFAULT '0'")
     */
    private $isOccasion;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_numerique", columnDefinition="INT(1) NOT NULL DEFAULT '0'")
     */
    private $isNumeric;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_lie", columnDefinition="INT(1) NOT NULL DEFAULT '0'")
     */
    private $isLie;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_ean", type="string", length=13, nullable=false)
     */
    private $ean;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_physical_ean", type="string", length=13, nullable=true)
     */
    private $physicalEan;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_prixht", type="decimal", precision=10, scale=2, options={"default":0})
     */
    private $grossTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_prixttc", type="decimal", precision=10, scale=2, options={"default":0})
     */
    private $netTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_prixpromo", type="decimal", precision=10, scale=2, options={"default":0})
     */
    private $discountPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_tva1", type="decimal", precision=4, scale=2, options={"default":0})
     */
    private $vat1;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_tva2", type="decimal", precision=4, scale=2, options={"default":0})
     */
    private $vat2;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_titre", type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_soustitre", type="string", length=100, nullable=true)
     */
    private $subTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_auteurs", type="string", length=200, nullable=true)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_editeur", type="string", length=100, nullable=true)
     */
    private $publisher;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_collection", type="string", length=100, nullable=true)
     */
    private $collection;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_genre", type="string", length=500, nullable=true)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prod_parution", type="date")
     */
    private $parutionDate;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_resume", type="text", nullable=true)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_desc_promotional", type="text", nullable=true)
     */
    private $promotionnalDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_desc_cover", type="text", nullable=true)
     */
    private $coverDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_namedistrib", type="string", length=200, nullable=true)
     */
    private $nameDistributor;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_glndistrib", type="string", length=13, nullable=true)
     */
    private $glnDistributor;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_presentcc", type="text", nullable=true)
     */
    private $heartDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_zone_1", type="text", nullable=true)
     */
    private $zone1Description;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_support", type="string", length=100, nullable=false)
     */
    private $support;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_nbpage", columnDefinition="INT(4) NOT NULL DEFAULT '0'")
     */
    private $totalPages;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_poids", type="decimal", precision=6, scale=2, options={"default":0})
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_epaisseur", type="decimal", precision=8, scale=2, options={"default":0})
     */
    private $wideness;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_largeur", type="decimal", precision=8, scale=2, options={"default":0})
     */
    private $thickness;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_hauteur", type="decimal", precision=8, scale=2, options={"default":0})
     */
    private $height;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_public", type="string", length=100, nullable=false)
     */
    private $public;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_lang", type="string", length=50, nullable=false)
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_protection", type="string", length=50, nullable=true)
     */
    private $protection;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_constraint_type", type="string", length=3, nullable=true)
     */
    private $constraintType;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_constraint_status", type="string", length=3, nullable=true)
     */
    private $constraintStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_constraint_quantity", type="string", length=3, nullable=true)
     */
    private $constraintQuantity;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_constraint_unit", type="string", length=3, nullable=true)
     */
    private $constraintUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_keys", type="text", nullable=true)
     */
    private $keys;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_dist_bl", columnDefinition="INT(1) NOT NULL DEFAULT '0'")
     */
    private $distBl;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_promo", columnDefinition="INT(1) NOT NULL DEFAULT '0'")
     */
    private $isPromoted;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_dispo", columnDefinition="INT(2) NOT NULL DEFAULT '0'")
     */
    private $isAvailable;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_validate", columnDefinition="INT(1) NOT NULL DEFAULT '0'")
     */
    private $isValid;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_ban", columnDefinition="INT(1) NOT NULL DEFAULT '0'")
     */
    private $isBanned;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_deleted", columnDefinition="INT(1) NOT NULL DEFAULT '0'")
     */
    private $isDeleted;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prod_date_ajout", type="date")
     */
    private $createdAtDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prod_heure_ajout", type="time")
     */
    private $createdAtTime;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_user_ajout", type="string", length=50, nullable=true)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prod_date_mod", type="date")
     */
    private $updatedAtDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prod_heure_mod", type="time")
     */
    private $updatedAtTime;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_user_mod", type="string", length=50, nullable=true)
     */
    private $updatedBy;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_distsend", columnDefinition="INT(1) NOT NULL DEFAULT '0'")
     */
    private $distSend;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_meta_titre", type="text", nullable=true)
     */
    private $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_meta_description", type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_type_prix", columnDefinition="INT(1) NOT NULL DEFAULT '0'")
     */
    private $priceType;

    /**
     *
     * Constructor.
     *
     */
    function __construct() 
    {
        
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return Product
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
     * Set category1
     *
     * @param string $category1
     *
     * @return Product
     */
    public function setCategory1($category1)
    {
        $this->category1 = $category1;

        return $this;
    }

    /**
     * Get category1
     *
     * @return string
     */
    public function getCategory1()
    {
        return $this->category1;
    }

    /**
     * Set category2
     *
     * @param string $category2
     *
     * @return Product
     */
    public function setCategory2($category2)
    {
        $this->category2 = $category2;

        return $this;
    }

    /**
     * Get category2
     *
     * @return string
     */
    public function getCategory2()
    {
        return $this->category2;
    }

    /**
     * Set stateId
     *
     * @param string $stateId
     *
     * @return Product
     */
    public function setStateId($stateId)
    {
        $this->stateId = $stateId;

        return $this;
    }

    /**
     * Get stateId
     *
     * @return string
     */
    public function getStateId()
    {
        return $this->stateId;
    }

    /**
     * Set isOccasion
     *
     * @param string $isOccasion
     *
     * @return Product
     */
    public function setIsOccasion($isOccasion)
    {
        $this->isOccasion = $isOccasion;

        return $this;
    }

    /**
     * Get isOccasion
     *
     * @return string
     */
    public function getIsOccasion()
    {
        return $this->isOccasion;
    }

    /**
     * Set isNumeric
     *
     * @param string $isNumeric
     *
     * @return Product
     */
    public function setIsNumeric($isNumeric)
    {
        $this->isNumeric = $isNumeric;

        return $this;
    }

    /**
     * Get isNumeric
     *
     * @return string
     */
    public function getIsNumeric()
    {
        return $this->isNumeric;
    }

    /**
     * Set isLie
     *
     * @param string $isLie
     *
     * @return Product
     */
    public function setIsLie($isLie)
    {
        $this->isLie = $isLie;

        return $this;
    }

    /**
     * Get isLie
     *
     * @return string
     */
    public function getIsLie()
    {
        return $this->isLie;
    }

    /**
     * Set ean
     *
     * @param string $ean
     *
     * @return Product
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
     * Set physicalEan
     *
     * @param string $physicalEan
     *
     * @return Product
     */
    public function setPhysicalEan($physicalEan)
    {
        $this->physicalEan = $physicalEan;

        return $this;
    }

    /**
     * Get physicalEan
     *
     * @return string
     */
    public function getPhysicalEan()
    {
        return $this->physicalEan;
    }

    /**
     * Set grossTotal
     *
     * @param string $grossTotal
     *
     * @return Product
     */
    public function setGrossTotal($grossTotal)
    {
        $this->grossTotal = $grossTotal;

        return $this;
    }

    /**
     * Get grossTotal
     *
     * @return string
     */
    public function getGrossTotal()
    {
        return $this->grossTotal;
    }

    /**
     * Set netTotal
     *
     * @param string $netTotal
     *
     * @return Product
     */
    public function setNetTotal($netTotal)
    {
        $this->netTotal = $netTotal;

        return $this;
    }

    /**
     * Get netTotal
     *
     * @return string
     */
    public function getNetTotal()
    {
        return $this->netTotal;
    }

    /**
     * Set discountPrice
     *
     * @param string $discountPrice
     *
     * @return Product
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
     * Set vat1
     *
     * @param string $vat1
     *
     * @return Product
     */
    public function setVat1($vat1)
    {
        $this->vat1 = $vat1;

        return $this;
    }

    /**
     * Get vat1
     *
     * @return string
     */
    public function getVat1()
    {
        return $this->vat1;
    }

    /**
     * Set vat2
     *
     * @param string $vat2
     *
     * @return Product
     */
    public function setVat2($vat2)
    {
        $this->vat2 = $vat2;

        return $this;
    }

    /**
     * Get vat2
     *
     * @return string
     */
    public function getVat2()
    {
        return $this->vat2;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Product
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
     * Set subTitle
     *
     * @param string $subTitle
     *
     * @return Product
     */
    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    /**
     * Get subTitle
     *
     * @return string
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Product
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
     * @return Product
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
     * Set collection
     *
     * @param string $collection
     *
     * @return Product
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collection
     *
     * @return string
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Product
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
     * Set parutionDate
     *
     * @param \DateTime $parutionDate
     *
     * @return Product
     */
    public function setParutionDate($parutionDate)
    {
        $this->parutionDate = $parutionDate;

        return $this;
    }

    /**
     * Get parutionDate
     *
     * @return \DateTime
     */
    public function getParutionDate()
    {
        return $this->parutionDate;
    }

    /**
     * Set summary
     *
     * @param string $summary
     *
     * @return Product
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set promotionnalDesc
     *
     * @param string $promotionnalDesc
     *
     * @return Product
     */
    public function setPromotionnalDesc($promotionnalDesc)
    {
        $this->promotionnalDesc = $promotionnalDesc;

        return $this;
    }

    /**
     * Get promotionnalDesc
     *
     * @return string
     */
    public function getPromotionnalDesc()
    {
        return $this->promotionnalDesc;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
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
     * Set coverDescription
     *
     * @param string $coverDescription
     *
     * @return Product
     */
    public function setCoverDescription($coverDescription)
    {
        $this->coverDescription = $coverDescription;

        return $this;
    }

    /**
     * Get coverDescription
     *
     * @return string
     */
    public function getCoverDescription()
    {
        return $this->coverDescription;
    }

    /**
     * Set nameDistributor
     *
     * @param string $nameDistributor
     *
     * @return Product
     */
    public function setNameDistributor($nameDistributor)
    {
        $this->nameDistributor = $nameDistributor;

        return $this;
    }

    /**
     * Get nameDistributor
     *
     * @return string
     */
    public function getNameDistributor()
    {
        return $this->nameDistributor;
    }

    /**
     * Set glnDistributor
     *
     * @param string $glnDistributor
     *
     * @return Product
     */
    public function setGlnDistributor($glnDistributor)
    {
        $this->glnDistributor = $glnDistributor;

        return $this;
    }

    /**
     * Get glnDistributor
     *
     * @return string
     */
    public function getGlnDistributor()
    {
        return $this->glnDistributor;
    }

    /**
     * Set heartDescription
     *
     * @param string $heartDescription
     *
     * @return Product
     */
    public function setHeartDescription($heartDescription)
    {
        $this->heartDescription = $heartDescription;

        return $this;
    }

    /**
     * Get heartDescription
     *
     * @return string
     */
    public function getHeartDescription()
    {
        return $this->heartDescription;
    }

    /**
     * Set zone1Description
     *
     * @param string $zone1Description
     *
     * @return Product
     */
    public function setZone1Description($zone1Description)
    {
        $this->zone1Description = $zone1Description;

        return $this;
    }

    /**
     * Get zone1Description
     *
     * @return string
     */
    public function getZone1Description()
    {
        return $this->zone1Description;
    }

    /**
     * Set support
     *
     * @param string $support
     *
     * @return Product
     */
    public function setSupport($support)
    {
        $this->support = $support;

        return $this;
    }

    /**
     * Get support
     *
     * @return string
     */
    public function getSupport()
    {
        return $this->support;
    }

    /**
     * Set totalPages
     *
     * @param string $totalPages
     *
     * @return Product
     */
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;

        return $this;
    }

    /**
     * Get totalPages
     *
     * @return string
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return Product
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
     * Set wideness
     *
     * @param string $wideness
     *
     * @return Product
     */
    public function setWideness($wideness)
    {
        $this->wideness = $wideness;

        return $this;
    }

    /**
     * Get wideness
     *
     * @return string
     */
    public function getWideness()
    {
        return $this->wideness;
    }

    /**
     * Set thickness
     *
     * @param string $thickness
     *
     * @return Product
     */
    public function setThickness($thickness)
    {
        $this->thickness = $thickness;

        return $this;
    }

    /**
     * Get thickness
     *
     * @return string
     */
    public function getThickness()
    {
        return $this->thickness;
    }

    /**
     * Set height
     *
     * @param string $height
     *
     * @return Product
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
     * Set public
     *
     * @param string $public
     *
     * @return Product
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return string
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set lang
     *
     * @param string $lang
     *
     * @return Product
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set protection
     *
     * @param string $protection
     *
     * @return Product
     */
    public function setProtection($protection)
    {
        $this->protection = $protection;

        return $this;
    }

    /**
     * Get protection
     *
     * @return string
     */
    public function getProtection()
    {
        return $this->protection;
    }

    /**
     * Set constraintType
     *
     * @param string $constraintType
     *
     * @return Product
     */
    public function setConstraintType($constraintType)
    {
        $this->constraintType = $constraintType;

        return $this;
    }

    /**
     * Get constraintType
     *
     * @return string
     */
    public function getConstraintType()
    {
        return $this->constraintType;
    }

    /**
     * Set constraintStatus
     *
     * @param string $constraintStatus
     *
     * @return Product
     */
    public function setConstraintStatus($constraintStatus)
    {
        $this->constraintStatus = $constraintStatus;

        return $this;
    }

    /**
     * Get constraintStatus
     *
     * @return string
     */
    public function getConstraintStatus()
    {
        return $this->constraintStatus;
    }

    /**
     * Set constraintQuantity
     *
     * @param string $constraintQuantity
     *
     * @return Product
     */
    public function setConstraintQuantity($constraintQuantity)
    {
        $this->constraintQuantity = $constraintQuantity;

        return $this;
    }

    /**
     * Get constraintQuantity
     *
     * @return string
     */
    public function getConstraintQuantity()
    {
        return $this->constraintQuantity;
    }

    /**
     * Set constraintUnit
     *
     * @param string $constraintUnit
     *
     * @return Product
     */
    public function setConstraintUnit($constraintUnit)
    {
        $this->constraintUnit = $constraintUnit;

        return $this;
    }

    /**
     * Get constraintUnit
     *
     * @return string
     */
    public function getConstraintUnit()
    {
        return $this->constraintUnit;
    }

    /**
     * Set keys
     *
     * @param string $keys
     *
     * @return Product
     */
    public function setKeys($keys)
    {
        $this->keys = $keys;

        return $this;
    }

    /**
     * Get keys
     *
     * @return string
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * Set distBl
     *
     * @param string $distBl
     *
     * @return Product
     */
    public function setDistBl($distBl)
    {
        $this->distBl = $distBl;

        return $this;
    }

    /**
     * Get distBl
     *
     * @return string
     */
    public function getDistBl()
    {
        return $this->distBl;
    }

    /**
     * Set isPromoted
     *
     * @param string $isPromoted
     *
     * @return Product
     */
    public function setIsPromoted($isPromoted)
    {
        $this->isPromoted = $isPromoted;

        return $this;
    }

    /**
     * Get isPromoted
     *
     * @return string
     */
    public function getIsPromoted()
    {
        return $this->isPromoted;
    }

    /**
     * Set isAvailable
     *
     * @param string $isAvailable
     *
     * @return Product
     */
    public function setIsAvailable($isAvailable)
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    /**
     * Get isAvailable
     *
     * @return string
     */
    public function getIsAvailable()
    {
        return $this->isAvailable;
    }

    /**
     * Set isValid
     *
     * @param string $isValid
     *
     * @return Product
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * Get isValid
     *
     * @return string
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * Set isBanned
     *
     * @param string $isBanned
     *
     * @return Product
     */
    public function setIsBanned($isBanned)
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    /**
     * Get isBanned
     *
     * @return string
     */
    public function getIsBanned()
    {
        return $this->isBanned;
    }

    /**
     * Set isDeleted
     *
     * @param string $isDeleted
     *
     * @return Product
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return string
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set createdAtDate
     *
     * @param \DateTime $createdAtDate
     *
     * @return Product
     */
    public function setCreatedAtDate($createdAtDate)
    {
        $this->createdAtDate = $createdAtDate;

        return $this;
    }

    /**
     * Get createdAtDate
     *
     * @return \DateTime
     */
    public function getCreatedAtDate()
    {
        return $this->createdAtDate;
    }

    /**
     * Set createdAtTime
     *
     * @param \DateTime $createdAtTime
     *
     * @return Product
     */
    public function setCreatedAtTime($createdAtTime)
    {
        $this->createdAtTime = $createdAtTime;

        return $this;
    }

    /**
     * Get createdAtTime
     *
     * @return \DateTime
     */
    public function getCreatedAtTime()
    {
        return $this->createdAtTime;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     *
     * @return Product
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set updatedAtDate
     *
     * @param \DateTime $updatedAtDate
     *
     * @return Product
     */
    public function setUpdatedAtDate($updatedAtDate)
    {
        $this->updatedAtDate = $updatedAtDate;

        return $this;
    }

    /**
     * Get updatedAtDate
     *
     * @return \DateTime
     */
    public function getUpdatedAtDate()
    {
        return $this->updatedAtDate;
    }

    /**
     * Set updatedAtTime
     *
     * @param \DateTime $updatedAtTime
     *
     * @return Product
     */
    public function setUpdatedAtTime($updatedAtTime)
    {
        $this->updatedAtTime = $updatedAtTime;

        return $this;
    }

    /**
     * Get updatedAtTime
     *
     * @return \DateTime
     */
    public function getUpdatedAtTime()
    {
        return $this->updatedAtTime;
    }

    /**
     * Set updatedBy
     *
     * @param string $updatedBy
     *
     * @return Product
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set distSend
     *
     * @param string $distSend
     *
     * @return Product
     */
    public function setDistSend($distSend)
    {
        $this->distSend = $distSend;

        return $this;
    }

    /**
     * Get distSend
     *
     * @return string
     */
    public function getDistSend()
    {
        return $this->distSend;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return Product
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return Product
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
     * Set priceType
     *
     * @param string $priceType
     *
     * @return Product
     */
    public function setPriceType($priceType)
    {
        $this->priceType = $priceType;

        return $this;
    }

    /**
     * Get priceType
     *
     * @return string
     */
    public function getPriceType()
    {
        return $this->priceType;
    }
}
