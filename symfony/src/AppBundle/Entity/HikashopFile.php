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

}
