<?php

namespace HikashopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HikashopPrice
 *
 * @ORM\Table(name="c0syc_hikashop_address")
 * @ORM\Entity(repositoryClass="HikashopBundle\Repository\HikashopAddressRepository")
 */
class HikashopAddress
{
    /**
     * @var int
     *
     * @ORM\Column(name="address_id", columnDefinition="INT(10) UNSIGNED NOT NULL AUTO_INCREMENT")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="address_user_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="address_title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="address_firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="address_middle_name", type="string", length=255, nullable=true)
     */
    private $middleName;

    /**
     * @var string
     *
     * @ORM\Column(name="address_lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="address_company", type="string", length=255, nullable=true)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="address_street", type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="address_street2", type="string", length=255, nullable=false, options={"default":""})
     */
    private $street2;

    /**
     * @var string
     *
     * @ORM\Column(name="address_post_code", type="string", length=255, nullable=true)
     */
    private $postCode;

    /**
     * @var string
     *
     * @ORM\Column(name="address_city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address_telephone", type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="address_telephone2", type="string", length=255, nullable=true)
     */
    private $telephone2;

    /**
     * @var string
     *
     * @ORM\Column(name="address_fax", type="string", length=255, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="address_state", type="string", length=255, nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="address_country", type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @var int
     *
     * @ORM\Column(name="address_published", columnDefinition="TINYINT(4) NOT NULL DEFAULT '1'")
     */
    private $published;

    /**
     * @var string
     *
     * @ORM\Column(name="address_vat", type="string", length=255, nullable=true)
     */
    private $vat;

    /**
     * @var int
     *
     * @ORM\Column(name="address_default", columnDefinition="TINYINT(4) NOT NULL DEFAULT '0'")
     */
    private $default;

    /**
     * @var string
     *
     * @ORM\Column(name="address_type", type="string", length=255, nullable=false, options={"default":""})
     */
    private $type;

    /**
     *
     * Constructor.
     *
     */
    function __construct() 
    {
    	$this->userId = null;
    	$this->title = null;
    	$this->firstname = null;
    	$this->middleName = null;
    	$this->lastname = null;
    	$this->company = null;
    	$this->street = null;
    	$this->street2 = '';
    	$this->postCode = null;
    	$this->city = null;
    	$this->telephone = null;
    	$this->telephone2 = null;
    	$this->fax = null;
    	$this->state = null;
    	$this->country = null;

        $this->published = 1;
        $this->vat = null;
        $this->default = 0;
        $this->type = '';
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
     * Set userId
     *
     * @param string $userId
     *
     * @return HikashopAddress
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
     * Set title
     *
     * @param string $title
     *
     * @return HikashopAddress
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return HikashopAddress
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     *
     * @return HikashopAddress
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return HikashopAddress
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return HikashopAddress
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return HikashopAddress
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set street2
     *
     * @param string $street2
     *
     * @return HikashopAddress
     */
    public function setStreet2($street2)
    {
        $this->street2 = $street2;

        return $this;
    }

    /**
     * Get street2
     *
     * @return string
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * Set postCode
     *
     * @param string $postCode
     *
     * @return HikashopAddress
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * Get postCode
     *
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return HikashopAddress
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return HikashopAddress
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set telephone2
     *
     * @param string $telephone2
     *
     * @return HikashopAddress
     */
    public function setTelephone2($telephone2)
    {
        $this->telephone2 = $telephone2;

        return $this;
    }

    /**
     * Get telephone2
     *
     * @return string
     */
    public function getTelephone2()
    {
        return $this->telephone2;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return HikashopAddress
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return HikashopAddress
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return HikashopAddress
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set published
     *
     * @param string $published
     *
     * @return HikashopAddress
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
     * Set vat
     *
     * @param string $vat
     *
     * @return HikashopAddress
     */
    public function setVat($vat)
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * Get vat
     *
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Set default
     *
     * @param string $default
     *
     * @return HikashopAddress
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Get default
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return HikashopAddress
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
}
