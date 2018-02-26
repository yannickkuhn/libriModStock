<?php

namespace HikashopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HikashopPrice
 *
 * @ORM\Table(name="c0syc_hikashop_category")
 * @ORM\Entity(repositoryClass="HikashopBundle\Repository\HikashopCategoryRepository")
 */
class HikashopCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="category_id", columnDefinition="INT(10) UNSIGNED NOT NULL AUTO_INCREMENT")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="category_parent_id", columnDefinition=" INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $parentId;

    /**
     * @var string
     *
     * @ORM\Column(name="category_type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="category_name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="category_description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="category_published", columnDefinition="TINYINT(4) NOT NULL DEFAULT '0'")
     */
    private $published;

    /**
     * @var int
     *
     * @ORM\Column(name="category_ordering", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $ordering;

    /**
     * @var int
     *
     * @ORM\Column(name="category_left", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $left;

    /**
     * @var int
     *
     * @ORM\Column(name="category_right", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $right;

    /**
     * @var int
     *
     * @ORM\Column(name="category_depth", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $depth;

    /**
     * @var string
     *
     * @ORM\Column(name="category_namekey", type="string", length=255, nullable=false)
     */
    private $namekey;

    /**
     * @var int
     *
     * @ORM\Column(name="category_created", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="category_modified", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $modifiedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="category_access", type="string", length=255, nullable=false, options={"default":"all"})
     */
    private $accessingBy;

    /**
     * @var int
     *
     * @ORM\Column(name="category_menu", columnDefinition="INT(10) UNSIGNED NOT NULL DEFAULT '0'")
     */
    private $menu;

    /**
     * @var string
     *
     * @ORM\Column(name="category_keywords", type="text", nullable=false)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="category_meta_description", type="string", length=255, nullable=false, options={"default":""})
     */
    private $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="category_layout", type="string", length=255, nullable=false, options={"default":""})
     */
    private $layout;

    /**
     * @var string
     *
     * @ORM\Column(name="category_page_title", type="string", length=255, nullable=false, options={"default":""})
     */
    private $pageTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="category_alias", type="string", length=255, nullable=false, options={"default":""})
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="category_site_id", type="string", length=255, nullable=true, options={"default":""})
     */
    private $siteId;

    /**
     * @var string
     *
     * @ORM\Column(name="category_canonical", type="string", length=255, nullable=false, options={"default":""})
     */
    private $canonical;

    /**
     * @var string
     *
     * @ORM\Column(name="category_quantity_layout", type="string", length=255, nullable=false, options={"default":""})
     */
    private $quantityLayout;

    /**
     *
     * Constructor.
     *
     */
    function __construct() 
    {
        $this->description = '';
        $this->published = 1;
        $this->ordering = 1;    // dynamique ?
        $this->left = 0;
        $this->right = 0;
        $this->depth = 2;
        
        $this->accessingBy = 'all';
        $this->menu = 0;
        $this->keywords = '';
        $this->metaDescription = '';
        $this->layout = '';
        $this->pageTitle = '';
        $this->alias = '';      // alias (slug)
        $this->siteId = '';
        $this->canonical = '';
        $this->quantityLayout = '';

        $date = new \DateTime();
        $this->createdAt = $date->getTimestamp();
        $this->modifiedAt = $date->getTimestamp();
        $this->namekey = 'product_'.$this->createdAt;    // identifier as : product_{createdAt}
    }

    /**
     *
     * setFromLibrisoft.
     *
     */
    function setFromLibrisoft($name, $slug = '', $type = 'product', $parentId = 2) 
    {
        $this->parentId = $parentId;
        $this->type = $type;
        $this->name = $name;
        $this->alias = $slug;
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
     * @return HikashopCategory
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
     * Set type
     *
     * @param string $type
     *
     * @return HikashopCategory
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
     * Set name
     *
     * @param string $name
     *
     * @return HikashopCategory
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
     * @return HikashopCategory
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
     * Set published
     *
     * @param string $published
     *
     * @return HikashopCategory
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
     * Set ordering
     *
     * @param string $ordering
     *
     * @return HikashopCategory
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
     * Set left
     *
     * @param string $left
     *
     * @return HikashopCategory
     */
    public function setLeft($left)
    {
        $this->left = $left;

        return $this;
    }

    /**
     * Get left
     *
     * @return string
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Set right
     *
     * @param string $right
     *
     * @return HikashopCategory
     */
    public function setRight($right)
    {
        $this->right = $right;

        return $this;
    }

    /**
     * Get right
     *
     * @return string
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * Set depth
     *
     * @param string $depth
     *
     * @return HikashopCategory
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Get depth
     *
     * @return string
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Set namekey
     *
     * @param string $namekey
     *
     * @return HikashopCategory
     */
    public function setNamekey($namekey)
    {
        $this->namekey = $namekey;

        return $this;
    }

    /**
     * Get namekey
     *
     * @return string
     */
    public function getNamekey()
    {
        return $this->namekey;
    }

    /**
     * Set createdAt
     *
     * @param string $createdAt
     *
     * @return HikashopCategory
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
     * @return HikashopCategory
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
     * Set accessingBy
     *
     * @param string $accessingBy
     *
     * @return HikashopCategory
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
     * Set menu
     *
     * @param string $menu
     *
     * @return HikashopCategory
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return string
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     *
     * @return HikashopCategory
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
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return HikashopCategory
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
     * Set layout
     *
     * @param string $layout
     *
     * @return HikashopCategory
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
     * Set pageTitle
     *
     * @param string $pageTitle
     *
     * @return HikashopCategory
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
     * @return HikashopCategory
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
     * Set siteId
     *
     * @param string $siteId
     *
     * @return HikashopCategory
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * Get siteId
     *
     * @return string
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * Set canonical
     *
     * @param string $canonical
     *
     * @return HikashopCategory
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
     * Set quantityLayout
     *
     * @param string $quantityLayout
     *
     * @return HikashopCategory
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
}
