<?php

namespace Tracker\Model\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 *
 * @ORM\MappedSuperclass
 */
class File extends \Tracker\Model\Inherit\Model
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=140, precision=0, scale=0, nullable=false, unique=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=260, precision=0, scale=0, nullable=false, unique=false)
     */
    private $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Tracker\Model\Base\User
     *
     * @ORM\ManyToOne(targetEntity="Tracker\Model\Base\User", inversedBy="createdProducts")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creatorId", referencedColumnName="id", nullable=true)
     * })
     */
    private $creatorUser;

    /**
     * Set title
     *
     * @param string $title
     *
     * @return File
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return File
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set creatorUser
     *
     * @param \Tracker\Model\Base\User $creatorUser
     *
     * @return File
     */
    public function setCreatorUser(\Tracker\Model\Base\User $creatorUser = null)
    {
        $this->creatorUser = $creatorUser;

        return $this;
    }

    /**
     * Get creatorUser
     *
     * @return \Tracker\Model\Base\User
     */
    public function getCreatorUser()
    {
        return $this->creatorUser;
    }

    /**
     * Set product
     *
     * @param \Tracker\Model\Base\Product $product
     *
     * @return File
     */
    public function setProduct(\Tracker\Model\Base\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return File
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
}
