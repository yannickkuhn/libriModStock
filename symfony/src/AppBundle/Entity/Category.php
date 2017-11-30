<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock.
 *
 * @ORM\Table(name="rubriques")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Column(name="rub_id", columnDefinition="INT(5) NOT NULL")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="rub_libri_id", type="string", length=150, nullable=false)
     */
    private $librisoftId;

    /**
     * @var string
     *
     * @ORM\Column(name="rub_libelle", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     *
     * Constructor.
     *
     */
    function __construct() 
    {
        $this->title = '';
        $this->librisoftId = 0;   
    }

    /**
     * Set id
     *
     * @param string $id
     *
     * @return Category
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
     * Set librisoftId
     *
     * @param string $librisoftId
     *
     * @return Category
     */
    public function setLibrisoftId($librisoftId)
    {
        $this->librisoftId = $librisoftId;

        return $this;
    }

    /**
     * Get librisoftId
     *
     * @return string
     */
    public function getLibrisoftId()
    {
        return $this->librisoftId;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Category
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
}
