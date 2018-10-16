<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="produit_associations")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AssocProductRepository")
 */
class AssocProduct
{
    /**
     * @var int
     *
     * @ORM\Column(name="prod_assoc_id", columnDefinition="INT(11) NOT NULL")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_ean", type="string", length=13, nullable=false)
     */
    private $ean;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_etat_id", columnDefinition="INT(2) NOT NULL DEFAULT '0'")
     */
    private $stateId;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_assoc_etat", type="string", length=100, nullable=false)
     */
    private $assocState;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_assoc_ean", type="string", length=13, nullable=false)
     */
    private $assocEan;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_assoc_prixht", type="decimal", precision=10, scale=2, options={"default":0})
     */
    private $assocGrossTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_assoc_prixttc", type="decimal", precision=10, scale=2, options={"default":0})
     */
    private $assocNetTotal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prod_assoc_date_ajout", type="date")
     */
    private $assocCreatedAtDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prod_assoc_heure_ajout", type="time")
     */
    private $assocCreatedAtTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prod_assoc_date_mod", type="date")
     */
    private $assocUpdatedAtDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prod_assoc_heure_mod", type="time")
     */
    private $assocUpdatedAtTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="prod_assoc_parution", type="date")
     */
    private $assocParutionDate;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_assoc_dispo", columnDefinition="INT(2) NOT NULL DEFAULT '0'")
     */
    private $isAvailable;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_assoc_titre", type="string", length=100, nullable=true)
     */
    private $assocTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_assoc_auteurs", type="string", length=200, nullable=true)
     */
    private $assocAuthor;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_assoc_editeur", type="string", length=100, nullable=true)
     */
    private $assocPublisher;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_assoc_collection", type="string", length=100, nullable=true)
     */
    private $assocCollection;

    /**
     * @var int
     *
     * @ORM\Column(name="prod_assoc_rub_per_id", columnDefinition="INT(2) NOT NULL DEFAULT '0'")
     */
    private $assocRubId;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_assoc_support", type="string", length=100, nullable=true)
     */
    private $assocSupport;

    /**
     * @var string
     *
     * @ORM\Column(name="prod_assoc_poids", type="decimal", precision=6, scale=2, options={"default":0})
     */
    private $assocWeight;

    /**
     *
     * Constructor.
     *
     */
    function __construct() 
    {
        $this->assocTitle = '';
        $this->assocPublisher = '';
        $this->assocAuthor = '';
        $this->assocCollection = '';
        $this->assocSupport = '';
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return AssocProduct
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
     * Set ean
     *
     * @param string $ean
     *
     * @return AssocProduct
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
     * Set stateId
     *
     * @param string $stateId
     *
     * @return AssocProduct
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
     * Set assocState
     *
     * @param string $assocState
     *
     * @return AssocProduct
     */
    public function setAssocState($assocState)
    {
        $this->assocState = $assocState;

        return $this;
    }

    /**
     * Get assocState
     *
     * @return string
     */
    public function getAssocState()
    {
        return $this->assocState;
    }

    /**
     * Set assocEan
     *
     * @param string $assocEan
     *
     * @return AssocProduct
     */
    public function setAssocEan($assocEan)
    {
        $this->assocEan = $assocEan;

        return $this;
    }

    /**
     * Get assocEan
     *
     * @return string
     */
    public function getAssocEan()
    {
        return $this->assocEan;
    }

    /**
     * Set grossTotal
     *
     * @param string $assocGrossTotal
     *
     * @return AssocProduct
     */
    public function setAssocGrossTotal($assocGrossTotal)
    {
        $this->assocGrossTotal = $assocGrossTotal;

        return $this;
    }

    /**
     * Get assocGrossTotal
     *
     * @return string
     */
    public function getAssocGrossTotal()
    {
        return $this->assocGrossTotal;
    }

    /**
     * Set assocNetTotal
     *
     * @param string $assocNetTotal
     *
     * @return AssocProduct
     */
    public function setAssocNetTotal($assocNetTotal)
    {
        $this->assocNetTotal = $assocNetTotal;

        return $this;
    }

    /**
     * Get assocNetTotal
     *
     * @return string
     */
    public function getAssocNetTotal()
    {
        return $this->assocNetTotal;
    }

    /**
     * Set assocCreatedAtDate
     *
     * @param \DateTime $assocCreatedAtDate
     *
     * @return AssocProduct
     */
    public function setAssocCreatedAtDate($assocCreatedAtDate)
    {
        $this->assocCreatedAtDate = $assocCreatedAtDate;

        return $this;
    }

    /**
     * Get assocCreatedAtDate
     *
     * @return \DateTime
     */
    public function getAssocCreatedAtDate()
    {
        return $this->assocCreatedAtDate;
    }

    /**
     * Set assocCreatedAtTime
     *
     * @param \DateTime $assocCreatedAtTime
     *
     * @return AssocProduct
     */
    public function setAssocCreatedAtTime($assocCreatedAtTime)
    {
        $this->assocCreatedAtTime = $assocCreatedAtTime;

        return $this;
    }

    /**
     * Get assocCreatedAtTime
     *
     * @return \DateTime
     */
    public function getAssocCreatedAtTime()
    {
        return $this->assocCreatedAtTime;
    }

    /**
     * Set assocUpdatedAtDate
     *
     * @param \DateTime $assocUpdatedAtDate
     *
     * @return AssocProduct
     */
    public function setAssocUpdatedAtDate($assocUpdatedAtDate)
    {
        $this->assocUpdatedAtDate = $assocUpdatedAtDate;

        return $this;
    }

    /**
     * Get assocUpdatedAtDate
     *
     * @return \DateTime
     */
    public function getAssocUpdatedAtDate()
    {
        return $this->assocUpdatedAtDate;
    }

    /**
     * Set assocUpdatedAtTime
     *
     * @param \DateTime $assocUpdatedAtTime
     *
     * @return AssocProduct
     */
    public function setAssocUpdatedAtTime($assocUpdatedAtTime)
    {
        $this->assocUpdatedAtTime = $assocUpdatedAtTime;

        return $this;
    }

    /**
     * Get assocUpdatedAtTime
     *
     * @return \DateTime
     */
    public function getAssocUpdatedAtTime()
    {
        return $this->assocUpdatedAtTime;
    }

    /**
     * Set assocParutionDate
     *
     * @param \DateTime $assocParutionDate
     *
     * @return AssocProduct
     */
    public function setAssocParutionDate($assocParutionDate)
    {
        $this->assocParutionDate = $assocParutionDate;

        return $this;
    }

    /**
     * Get assocParutionDate
     *
     * @return \DateTime
     */
    public function getAssocParutionDate()
    {
        return $this->assocParutionDate;
    }

    /**
     * Set isAvailable
     *
     * @param string $isAvailable
     *
     * @return AssocProduct
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
     * Set assocTitle
     *
     * @param string $assocTitle
     *
     * @return AssocProduct
     */
    public function setAssocTitle($assocTitle)
    {
        $this->assocTitle = $assocTitle;

        return $this;
    }

    /**
     * Get assocTitle
     *
     * @return string
     */
    public function getAssocTitle()
    {
        return $this->assocTitle;
    }

    /**
     * Set assocAuthor
     *
     * @param string $assocAuthor
     *
     * @return AssocProduct
     */
    public function setAssocAuthor($assocAuthor)
    {
        $this->assocAuthor = $assocAuthor;

        return $this;
    }

    /**
     * Get assocAuthor
     *
     * @return string
     */
    public function getAssocAuthor()
    {
        return $this->assocAuthor;
    }

    /**
     * Set assocPublisher
     *
     * @param string $assocPublisher
     *
     * @return AssocProduct
     */
    public function setAssocPublisher($assocPublisher)
    {
        $this->assocPublisher = $assocPublisher;

        return $this;
    }

    /**
     * Get assocPublisher
     *
     * @return string
     */
    public function getAssocPublisher()
    {
        return $this->assocPublisher;
    }

    /**
     * Set assocCollection
     *
     * @param string $assocCollection
     *
     * @return AssocProduct
     */
    public function setAssocCollection($assocCollection)
    {
        $this->assocCollection = $assocCollection;

        return $this;
    }

    /**
     * Get assocCollection
     *
     * @return string
     */
    public function getAssocCollection()
    {
        return $this->assocCollection;
    }

    /**
     * Set assocRubId
     *
     * @param string $assocRubId
     *
     * @return AssocProduct
     */
    public function setAssocRubId($assocRubId)
    {
        $this->assocRubId = $assocRubId;

        return $this;
    }

    /**
     * Get assocRubId
     *
     * @return string
     */
    public function getAssocRubId()
    {
        return $this->assocRubId;
    }

    /**
     * Set assocSupport
     *
     * @param string $assocSupport
     *
     * @return AssocProduct
     */
    public function setAssocSupport($assocSupport)
    {
        $this->assocSupport = $assocSupport;

        return $this;
    }

    /**
     * Get assocSupport
     *
     * @return string
     */
    public function getAssocSupport()
    {
        return $this->assocSupport;
    }

    /**
     * Set assocWeight
     *
     * @param string $assocWeight
     *
     * @return AssocProduct
     */
    public function setAssocWeight($assocWeight)
    {
        $this->assocWeight = $assocWeight;

        return $this;
    }

    /**
     * Get assocWeight
     *
     * @return string
     */
    public function getAssocWeight()
    {
        return $this->assocWeight;
    }
}
