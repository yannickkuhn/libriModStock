<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderHeader
 *
 * @ORM\Table(name="commande_entetes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderHeaderRepository")
 */
class OrderHeader
{
    /**
     * @var int
     *
     * @ORM\Column(name="cmd_ent_autoid", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_id", type="string", length=20)
     */
    private $idWeb;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_id_libri", type="string", length=10, nullable=true)
     */
    private $idLibrisoft;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_sid", type="string", length=100, nullable=true)
     */
    private $sessionId;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_ent_valid", columnDefinition="INT(1) NOT NULL DEFAULT 0")
     */
    private $valid;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_ent_download", columnDefinition="INT(1) NOT NULL DEFAULT 0")
     */
    private $download;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cmd_ent_date", type="date")
     */
    private $createdAtDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cmd_ent_heure", type="time")
     */
    private $createdAtTime;

    /**
     * @var int
     *
     * @ORM\Column(name="pai_id", columnDefinition="INT(1) NULL")
     */
    private $paymentId;

    /**
     * @var int
     *
     * @ORM\Column(name="expd_id", columnDefinition="INT(2) NULL")
     */
    private $shippingId;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_ent_pai_statut", columnDefinition="INT(1) NOT NULL DEFAULT 0")
     */
    private $paymentStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_montant_hfp", type="decimal", precision=10, scale=2, options={"default":0})
     */
    private $netTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_montant_fp", type="decimal", precision=10, scale=2, options={"default":0})
     */
    private $shippingValue;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_poids", type="decimal", precision=10, scale=2, options={"default":0})
     */
    private $weight;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_ent_allDigital", columnDefinition="INT(1) NOT NULL DEFAULT 0")
     */
    private $numeric;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_nocolis", type="string", length=50, nullable=true)
     */
    private $trackerNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_ent_pcadeau", columnDefinition="INT(1) NOT NULL DEFAULT 0")
     */
    private $gift;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_commentaire", columnDefinition="MEDIUMTEXT NULL")
     */
    private $comment;

    /**
     * @var int
     *
     * @ORM\Column(name="statut_id", columnDefinition="INT(1) NULL")
     */
    private $statusId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cmd_ent_date_statut", type="date", nullable=true)
     */
    private $statusDate;

    /**
     * @var string
     *
     * @ORM\Column(name="cli_id", type="string", length=20, nullable=true)
     */
    private $billedCustomer;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_ent_billingCiv", columnDefinition="INT(1) NULL")
     */
    private $billingTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_billingPrenom", type="string", length=50, nullable=true)
     */
    private $billingFirstName;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_billingNom", type="string", length=50, nullable=true)
     */
    private $billingLastName;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_billingSociete", type="string", length=50, nullable=true)
     */
    private $billingSocietyName;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_billingAdresse", type="string", length=100, nullable=true)
     */
    private $billingStreetAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_billingVille", type="string", length=100, nullable=true)
     */
    private $billingTown;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_billingCodePostal", type="string", length=10, nullable=true)
     */
    private $billingPostCode;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_ent_billingPaysId", columnDefinition="INT(11) NULL")
     */
    private $billingCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_billingTel", type="string", length=50, nullable=true)
     */
    private $billingPhone;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_ent_deliveryCiv", columnDefinition="INT(1) NULL")
     */
    private $shippingTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_deliveryPrenom", type="string", length=50, nullable=true)
     */
    private $shippingFirstName;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_deliveryNom", type="string", length=50, nullable=true)
     */
    private $shippingLastName;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_deliverySociete", type="string", length=50, nullable=true)
     */
    private $shippingSocietyName;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_deliveryAdresse", type="string", length=100, nullable=true)
     */
    private $shippingStreetAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_deliveryVille", type="string", length=100, nullable=true)
     */
    private $shippingTown;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_deliveryCodePostal", type="string", length=10, nullable=true)
     */
    private $shippingPostCode;

    /**
     * @var int
     *
     * @ORM\Column(name="cmd_ent_deliveryPaysId", columnDefinition="INT(11) NULL")
     */
    private $shippingCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="cmd_ent_deliveryTel", type="string", length=50, nullable=true)
     */
    private $shippingPhone;

    /**
     *
     * Constructor.
     *
     */
    function __construct() 
    {
        $this->comment          = "";
        $this->sessionId        = 0;
        $this->numeric          = 0;
        $this->gift             = 0;
        $this->emailSent        = 0;
        $this->valid            = 1;
        $this->download         = 0;
        $this->shippingValue    = 0;

        // -----------------------------
        // Civility :
        // - 0 : man (Mr.)
        // - 1 : woman (Mme.)
        // - 2 : lady (Mlle.)
        // -----------------------------

        $this->billingTitle     = 0; 
        $this->shippingTitle    = 0;
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return OrderHeader
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
     * Set idWeb
     *
     * @param string $idWeb
     *
     * @return OrderHeader
     */
    public function setIdWeb($idWeb)
    {
        $this->idWeb = $idWeb;

        return $this;
    }

    /**
     * Get idWeb
     *
     * @return string
     */
    public function getIdWeb()
    {
        return $this->idWeb;
    }

    /**
     * Set idLibrisoft
     *
     * @param string $idLibrisoft
     *
     * @return OrderHeader
     */
    public function setIdLibrisoft($idLibrisoft)
    {
        $this->idLibrisoft = $idLibrisoft;

        return $this;
    }

    /**
     * Get idLibrisoft
     *
     * @return string
     */
    public function getIdLibrisoft()
    {
        return $this->idLibrisoft;
    }

    /**
     * Set sessionId
     *
     * @param string $sessionId
     *
     * @return OrderHeader
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get sessionId
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set valid
     *
     * @param integer $valid
     *
     * @return OrderHeader
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid
     *
     * @return int
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Set download
     *
     * @param integer $download
     *
     * @return OrderHeader
     */
    public function setDownload($download)
    {
        $this->download = $download;

        return $this;
    }

    /**
     * Get download
     *
     * @return int
     */
    public function getDownload()
    {
        return $this->download;
    }

    /**
     * Set createdAtDate
     *
     * @param \DateTime $createdAtDate
     *
     * @return OrderHeader
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
     * @return OrderHeader
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
     * Set paymentId
     *
     * @param integer $paymentId
     *
     * @return OrderHeader
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * Get paymentId
     *
     * @return int
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * Set shippingId
     *
     * @param integer $shippingId
     *
     * @return OrderHeader
     */
    public function setShippingId($shippingId)
    {
        $this->shippingId = $shippingId;

        return $this;
    }

    /**
     * Get shippingId
     *
     * @return int
     */
    public function getShippingId()
    {
        return $this->shippingId;
    }

    /**
     * Set paymentStatus
     *
     * @param integer $paymentStatus
     *
     * @return OrderHeader
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * Get paymentStatus
     *
     * @return int
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * Set netTotal
     *
     * @param string $netTotal
     *
     * @return OrderHeader
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
     * Set shippingValue
     *
     * @param string $shippingValue
     *
     * @return OrderHeader
     */
    public function setShippingValue($shippingValue)
    {
        $this->shippingValue = $shippingValue;

        return $this;
    }

    /**
     * Get shippingValue
     *
     * @return string
     */
    public function getShippingValue()
    {
        return $this->shippingValue;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return OrderHeader
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
     * Set numeric
     *
     * @param integer $numeric
     *
     * @return OrderHeader
     */
    public function setNumeric($numeric)
    {
        $this->numeric = $numeric;

        return $this;
    }

    /**
     * Get numeric
     *
     * @return int
     */
    public function getNumeric()
    {
        return $this->numeric;
    }

    /**
     * Set trackerNumber
     *
     * @param string $trackerNumber
     *
     * @return OrderHeader
     */
    public function setTrackerNumber($trackerNumber)
    {
        $this->trackerNumber = $trackerNumber;

        return $this;
    }

    /**
     * Get trackerNumber
     *
     * @return string
     */
    public function getTrackerNumber()
    {
        return $this->trackerNumber;
    }

    /**
     * Set gift
     *
     * @param integer $gift
     *
     * @return OrderHeader
     */
    public function setGift($gift)
    {
        $this->gift = $gift;

        return $this;
    }

    /**
     * Get gift
     *
     * @return int
     */
    public function getGift()
    {
        return $this->gift;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return OrderHeader
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set statusId
     *
     * @param integer $statusId
     *
     * @return OrderHeader
     */
    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * Get statusId
     *
     * @return int
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * Set statusDate
     *
     * @param \DateTime $statusDate
     *
     * @return OrderHeader
     */
    public function setStatusDate($statusDate)
    {
        $this->statusDate = $statusDate;

        return $this;
    }

    /**
     * Get statusDate
     *
     * @return \DateTime
     */
    public function getStatusDate()
    {
        return $this->statusDate;
    }

    /**
     * Set billedCustomer
     *
     * @param string $billedCustomer
     *
     * @return OrderHeader
     */
    public function setBilledCustomer($billedCustomer)
    {
        $this->billedCustomer = $billedCustomer;

        return $this;
    }

    /**
     * Get billedCustomer
     *
     * @return string
     */
    public function getBilledCustomer()
    {
        return $this->billedCustomer;
    }

    /**
     * Set billingTitle
     *
     * @param integer $billingTitle
     *
     * @return OrderHeader
     */
    public function setBillingTitle($billingTitle)
    {
        $this->billingTitle = $billingTitle;

        return $this;
    }

    /**
     * Get billingTitle
     *
     * @return int
     */
    public function getBillingTitle()
    {
        return $this->billingTitle;
    }

    /**
     * Set billingFirstName
     *
     * @param string $billingFirstName
     *
     * @return OrderHeader
     */
    public function setBillingFirstName($billingFirstName)
    {
        $this->billingFirstName = $billingFirstName;

        return $this;
    }

    /**
     * Get billingFirstName
     *
     * @return string
     */
    public function getBillingFirstName()
    {
        return $this->billingFirstName;
    }

    /**
     * Set billingLastName
     *
     * @param string $billingLastName
     *
     * @return OrderHeader
     */
    public function setBillingLastName($billingLastName)
    {
        $this->billingLastName = $billingLastName;

        return $this;
    }

    /**
     * Get billingLastName
     *
     * @return string
     */
    public function getBillingLastName()
    {
        return $this->billingLastName;
    }

    /**
     * Set billingSocietyName
     *
     * @param string $billingSocietyName
     *
     * @return OrderHeader
     */
    public function setBillingSocietyName($billingSocietyName)
    {
        $this->billingSocietyName = $billingSocietyName;

        return $this;
    }

    /**
     * Get billingSocietyName
     *
     * @return string
     */
    public function getBillingSocietyName()
    {
        return $this->billingSocietyName;
    }

    /**
     * Set billingStreetAddress
     *
     * @param string $billingStreetAddress
     *
     * @return OrderHeader
     */
    public function setBillingStreetAddress($billingStreetAddress)
    {
        $this->billingStreetAddress = $billingStreetAddress;

        return $this;
    }

    /**
     * Get billingStreetAddress
     *
     * @return string
     */
    public function getBillingStreetAddress()
    {
        return $this->billingStreetAddress;
    }

    /**
     * Set billingTown
     *
     * @param string $billingTown
     *
     * @return OrderHeader
     */
    public function setBillingTown($billingTown)
    {
        $this->billingTown = $billingTown;

        return $this;
    }

    /**
     * Get billingTown
     *
     * @return string
     */
    public function getBillingTown()
    {
        return $this->billingTown;
    }

    /**
     * Set billingPostCode
     *
     * @param string $billingPostCode
     *
     * @return OrderHeader
     */
    public function setBillingPostCode($billingPostCode)
    {
        $this->billingPostCode = $billingPostCode;

        return $this;
    }

    /**
     * Get billingPostCode
     *
     * @return string
     */
    public function getBillingPostCode()
    {
        return $this->billingPostCode;
    }

    /**
     * Set billingCountry
     *
     * @param integer $billingCountry
     *
     * @return OrderHeader
     */
    public function setBillingCountry($billingCountry)
    {
        $this->billingCountry = $billingCountry;

        return $this;
    }

    /**
     * Get billingCountry
     *
     * @return int
     */
    public function getBillingCountry()
    {
        return $this->billingCountry;
    }

    /**
     * Set billingPhone
     *
     * @param string $billingPhone
     *
     * @return OrderHeader
     */
    public function setBillingPhone($billingPhone)
    {
        $this->billingPhone = $billingPhone;

        return $this;
    }

    /**
     * Get billingPhone
     *
     * @return string
     */
    public function getBillingPhone()
    {
        return $this->billingPhone;
    }

    /**
     * Set shippingTitle
     *
     * @param integer $shippingTitle
     *
     * @return OrderHeader
     */
    public function setShippingTitle($shippingTitle)
    {
        $this->shippingTitle = $shippingTitle;

        return $this;
    }

    /**
     * Get shippingTitle
     *
     * @return int
     */
    public function getShippingTitle()
    {
        return $this->shippingTitle;
    }

    /**
     * Set shippingFirstName
     *
     * @param string $shippingFirstName
     *
     * @return OrderHeader
     */
    public function setShippingFirstName($shippingFirstName)
    {
        $this->shippingFirstName = $shippingFirstName;

        return $this;
    }

    /**
     * Get shippingFirstName
     *
     * @return string
     */
    public function getShippingFirstName()
    {
        return $this->shippingFirstName;
    }

    /**
     * Set shippingLastName
     *
     * @param string $shippingLastName
     *
     * @return OrderHeader
     */
    public function setShippingLastName($shippingLastName)
    {
        $this->shippingLastName = $shippingLastName;

        return $this;
    }

    /**
     * Get shippingLastName
     *
     * @return string
     */
    public function getShippingLastName()
    {
        return $this->shippingLastName;
    }

    /**
     * Set shippingSocietyName
     *
     * @param string $shippingSocietyName
     *
     * @return OrderHeader
     */
    public function setShippingSocietyName($shippingSocietyName)
    {
        $this->shippingSocietyName = $shippingSocietyName;

        return $this;
    }

    /**
     * Get shippingSocietyName
     *
     * @return string
     */
    public function getShippingSocietyName()
    {
        return $this->shippingSocietyName;
    }

    /**
     * Set shippingStreetAddress
     *
     * @param string $shippingStreetAddress
     *
     * @return OrderHeader
     */
    public function setShippingStreetAddress($shippingStreetAddress)
    {
        $this->shippingStreetAddress = $shippingStreetAddress;

        return $this;
    }

    /**
     * Get shippingStreetAddress
     *
     * @return string
     */
    public function getShippingStreetAddress()
    {
        return $this->shippingStreetAddress;
    }

    /**
     * Set shippingTown
     *
     * @param string $shippingTown
     *
     * @return OrderHeader
     */
    public function setShippingTown($shippingTown)
    {
        $this->shippingTown = $shippingTown;

        return $this;
    }

    /**
     * Get shippingTown
     *
     * @return string
     */
    public function getShippingTown()
    {
        return $this->shippingTown;
    }

    /**
     * Set shippingPostCode
     *
     * @param string $shippingPostCode
     *
     * @return OrderHeader
     */
    public function setShippingPostCode($shippingPostCode)
    {
        $this->shippingPostCode = $shippingPostCode;

        return $this;
    }

    /**
     * Get shippingPostCode
     *
     * @return string
     */
    public function getShippingPostCode()
    {
        return $this->shippingPostCode;
    }

    /**
     * Set shippingCountry
     *
     * @param integer $shippingCountry
     *
     * @return OrderHeader
     */
    public function setShippingCountry($shippingCountry)
    {
        $this->shippingCountry = $shippingCountry;

        return $this;
    }

    /**
     * Get shippingCountry
     *
     * @return int
     */
    public function getShippingCountry()
    {
        return $this->shippingCountry;
    }

    /**
     * Set shippingPhone
     *
     * @param string $shippingPhone
     *
     * @return OrderHeader
     */
    public function setShippingPhone($shippingPhone)
    {
        $this->shippingPhone = $shippingPhone;

        return $this;
    }

    /**
     * Get shippingPhone
     *
     * @return string
     */
    public function getShippingPhone()
    {
        return $this->shippingPhone;
    }
}
