<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="clients")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 */
class Client
{
    /**
     * @var int
     *
     * @ORM\Column(name="cli_autoid", columnDefinition="INT(10) NOT NULL")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="cli_id", type="string", length=20, nullable=true)
     */
    private $cliId;

    /**
     * @var string
     *
     * @ORM\Column(name="cli_gencod", type="string", length=13, nullable=true)
     */
    private $cliGencod;

    /**
     * @var int
     *
     * @ORM\Column(name="cli_civ", columnDefinition="INT(1) NOT NULL DEFAULT 0")
     */
    private $civility;

    /**
     * @var string
     *
     * @ORM\Column(name="cli_prenom", type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="cli_nom", type="string", length=50, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="cli_mail", type="string", length=50)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="cli_password", type="string", length=100, nullable=true)
     */
    private $cliPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="cli_rtvcode", type="string", length=15, nullable=true)
     */
    private $cliRtvcode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cli_date_inscr", type="date", nullable=true)
     */
    private $subscribedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cli_last_visit", type="date", nullable=true)
     */
    private $cliLastVisit;

    /**
     * @var int
     *
     * @ORM\Column(name="cli_collectivite", columnDefinition="INT(1) NOT NULL DEFAULT 0")
     */
    private $cliCollectivite;

    /**
     * @var int
     *
     * @ORM\Column(name="cli_tmp_collectivite", columnDefinition="INT(1) NOT NULL DEFAULT 0")
     */
    private $cliTmpCollectivite;

    /**
     * @var int
     *
     * @ORM\Column(name="bforce_cpt", columnDefinition="INT(3) NOT NULL DEFAULT 0")
     */
    private $bforceCpt;

    /**
     * @var int
     *
     * @ORM\Column(name="bforce_time", columnDefinition="INT(20) NOT NULL DEFAULT 0")
     */
    private $bforceTime;

    /**
     * @var int
     *
     * @ORM\Column(name="bforce_locktime", columnDefinition="INT(10) NOT NULL DEFAULT 0")
     */
    private $bforceLocktime;

    /**
     * @var string
     *
     * @ORM\Column(name="Budget_Tot", type="decimal", precision=10, scale=2, options={"default":0})
     */
    private $budgetTot;

    /**
     * @var string
     *
     * @ORM\Column(name="Bugget_Use", type="decimal", precision=10, scale=2, options={"default":0})
     */
    private $budgetUse;


    function __construct() 
    {
        $this->cliGencod            = 0;
        $this->cliPassword          = 0;
        $this->cliCollectivite      = 0;
        $this->cliTmpCollectivite   = 0;
        $this->bforceCpt            = 0;
        $this->bforceLocktime       = 0;
        $this->bforceTime           = 0;
        $this->budgetTot            = 0;
        $this->budgetUse            = 0;

        // -----------------------------
        // Civility :
        // - 0 : man (Mr.)
        // - 1 : woman (Mme.)
        // - 2 : lady (Mlle.)
        // -----------------------------

        $this->civivility           = 0;
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return Client
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
     * Set cliId
     *
     * @param string $cliId
     *
     * @return Client
     */
    public function setCliId($cliId)
    {
        $this->cliId = $cliId;

        return $this;
    }

    /**
     * Get cliId
     *
     * @return string
     */
    public function getCliId()
    {
        return $this->cliId;
    }

    /**
     * Set cliGencod
     *
     * @param string $cliGencod
     *
     * @return Client
     */
    public function setCliGencod($cliGencod)
    {
        $this->cliGencod = $cliGencod;

        return $this;
    }

    /**
     * Get cliGencod
     *
     * @return string
     */
    public function getCliGencod()
    {
        return $this->cliGencod;
    }

    /**
     * Set civility
     *
     * @param integer $civility
     *
     * @return Client
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;

        return $this;
    }

    /**
     * Get civility
     *
     * @return int
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Client
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Client
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Client
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set cliPassword
     *
     * @param string $cliPassword
     *
     * @return Client
     */
    public function setCliPassword($cliPassword)
    {
        $this->cliPassword = $cliPassword;

        return $this;
    }

    /**
     * Get cliPassword
     *
     * @return string
     */
    public function getCliPassword()
    {
        return $this->cliPassword;
    }

    /**
     * Set cliRtvcode
     *
     * @param string $cliRtvcode
     *
     * @return Client
     */
    public function setCliRtvcode($cliRtvcode)
    {
        $this->cliRtvcode = $cliRtvcode;

        return $this;
    }

    /**
     * Get cliRtvcode
     *
     * @return string
     */
    public function getCliRtvcode()
    {
        return $this->cliRtvcode;
    }

    /**
     * Set subscribedDate
     *
     * @param \DateTime $subscribedDate
     *
     * @return Client
     */
    public function setSubscribedDate($subscribedDate)
    {
        $this->subscribedDate = $subscribedDate;

        return $this;
    }

    /**
     * Get subscribedDate
     *
     * @return \DateTime
     */
    public function getSubscribedDate()
    {
        return $this->subscribedDate;
    }

    /**
     * Set cliLastVisit
     *
     * @param \DateTime $cliLastVisit
     *
     * @return Client
     */
    public function setCliLastVisit($cliLastVisit)
    {
        $this->cliLastVisit = $cliLastVisit;

        return $this;
    }

    /**
     * Get cliLastVisit
     *
     * @return \DateTime
     */
    public function getCliLastVisit()
    {
        return $this->cliLastVisit;
    }

    /**
     * Set cliCollectivite
     *
     * @param integer $cliCollectivite
     *
     * @return Client
     */
    public function setCliCollectivite($cliCollectivite)
    {
        $this->cliCollectivite = $cliCollectivite;

        return $this;
    }

    /**
     * Get cliCollectivite
     *
     * @return int
     */
    public function getCliCollectivite()
    {
        return $this->cliCollectivite;
    }

    /**
     * Set cliTmpCollectivite
     *
     * @param integer $cliTmpCollectivite
     *
     * @return Client
     */
    public function setCliTmpCollectivite($cliTmpCollectivite)
    {
        $this->cliTmpCollectivite = $cliTmpCollectivite;

        return $this;
    }

    /**
     * Get cliTmpCollectivite
     *
     * @return int
     */
    public function getCliTmpCollectivite()
    {
        return $this->cliTmpCollectivite;
    }

    /**
     * Set bforceCpt
     *
     * @param integer $bforceCpt
     *
     * @return Client
     */
    public function setBforceCpt($bforceCpt)
    {
        $this->bforceCpt = $bforceCpt;

        return $this;
    }

    /**
     * Get bforceCpt
     *
     * @return int
     */
    public function getBforceCpt()
    {
        return $this->bforceCpt;
    }

    /**
     * Set bforceTime
     *
     * @param integer $bforceTime
     *
     * @return Client
     */
    public function setBforceTime($bforceTime)
    {
        $this->bforceTime = $bforceTime;

        return $this;
    }

    /**
     * Get bforceTime
     *
     * @return int
     */
    public function getBforceTime()
    {
        return $this->bforceTime;
    }

    /**
     * Set bforceLocktime
     *
     * @param integer $bforceLocktime
     *
     * @return Client
     */
    public function setBforceLocktime($bforceLocktime)
    {
        $this->bforceLocktime = $bforceLocktime;

        return $this;
    }

    /**
     * Get bforceLocktime
     *
     * @return int
     */
    public function getBforceLocktime()
    {
        return $this->bforceLocktime;
    }

    /**
     * Set budgetTot
     *
     * @param string $budgetTot
     *
     * @return Client
     */
    public function setBudgetTot($budgetTot)
    {
        $this->budgetTot = $budgetTot;

        return $this;
    }

    /**
     * Get budgetTot
     *
     * @return string
     */
    public function getBudgetTot()
    {
        return $this->budgetTot;
    }

    /**
     * Set budgetUse
     *
     * @param string $budgetUse
     *
     * @return Client
     */
    public function setBudgetUse($budgetUse)
    {
        $this->budgetUse = $budgetUse;

        return $this;
    }

    /**
     * Get budgetUse
     *
     * @return string
     */
    public function getBudgetUse()
    {
        return $this->budgetUse;
    }
}

