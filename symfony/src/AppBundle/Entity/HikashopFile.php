<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HikashopFile
 *
 * @ORM\Table(name="hikashop_file")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HikashopPriceRepository")
 */
class HikashopFile
{
    /**
     * @var int
     *
     * @ORM\Column(name="file_id", columnDefinition="INT(10) UNSIGNED NOT NULL AUTO_INCREMENT")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="file_description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="file_path", type="string", length=255, nullable=false)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="file_type", type="string", length=255, nullable=false, options={"default":"category"})
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="file_ref_id", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $refId;

    /**
     * @var int
     *
     * @ORM\Column(name="file_free_download", columnDefinition="TINYINT(3) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $freeDownload;

    /**
     * @var int
     *
     * @ORM\Column(name="file_ordering", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $ordering;

    /**
     * @var int
     *
     * @ORM\Column(name="file_limit", columnDefinition="INT(11) NOT NULL DEFAULT '0'")
     */
    private $limit;

    /**
     *
     * Constructor.
     *
     */
    function __construct() 
    {
        $this->description = '';
        $this->type = 'product';
        $this->freeDownload = 0;
        $this->ordering = 0;
        $this->limit = 0;
    }

    /**
     *
     * setFromLibrisoft.
     *
     */
    function setFromLibrisoft($fileNameWithoutExt, $fileName, $productId) 
    {
        $this->name = $fileNameWithoutExt;
        $this->path = $fileName;
        $this->refId = $productId;
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
     * Set name
     *
     * @param string $name
     *
     * @return HikashopFile
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
     * @return HikashopFile
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
     * Set path
     *
     * @param string $path
     *
     * @return HikashopFile
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return HikashopFile
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
     * Set refId
     *
     * @param string $refId
     *
     * @return HikashopFile
     */
    public function setRefId($refId)
    {
        $this->refId = $refId;

        return $this;
    }

    /**
     * Get refId
     *
     * @return string
     */
    public function getRefId()
    {
        return $this->refId;
    }

    /**
     * Set freeDownload
     *
     * @param string $freeDownload
     *
     * @return HikashopFile
     */
    public function setFreeDownload($freeDownload)
    {
        $this->freeDownload = $freeDownload;

        return $this;
    }

    /**
     * Get freeDownload
     *
     * @return string
     */
    public function getFreeDownload()
    {
        return $this->freeDownload;
    }

    /**
     * Set ordering
     *
     * @param string $ordering
     *
     * @return HikashopFile
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering
     *
     * @return string
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set limit
     *
     * @param string $limit
     *
     * @return HikashopFile
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get limit
     *
     * @return string
     */
    public function getLimit()
    {
        return $this->limit;
    }
}
