<?php

namespace Tracker\Model\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\MappedSuperclass
 */
class Product extends \Tracker\Model\Inherit\Model
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
     * @ORM\Column(name="identifier", type="string", length=140, precision=0, scale=0, nullable=false, unique=false)
     */
    private $identifier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="producedAt", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $producedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="storedAt", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $storedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="useBefore", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $useBefore;

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
     * @var \Tracker\Model\Base\File
     *
     * @ORM\ManyToMany(targetEntity="Tracker\Model\Base\File", inversedBy="attachedFiles")
     * @ORM\JoinTable(name="products_files",
     *   joinColumns={
     *     @ORM\JoinColumn(name="productId", referencedColumnName="id", nullable=true)
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="fileId", referencedColumnName="id", nullable=true)
     *   }
     * )
     */
    private $files;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ownerUsers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Product
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
     * @return Product
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
     * Add ownerUser
     *
     * @param \Tracker\Model\Base\User $ownerUser
     *
     * @return Product
     */
    public function addOwnerUser(\Tracker\Model\Base\User $ownerUser)
    {
        $this->ownerUsers[] = $ownerUser;

        return $this;
    }

    /**
     * Remove ownerUser
     *
     * @param \Tracker\Model\Base\User $ownerUser
     */
    public function removeOwnerUser(\Tracker\Model\Base\User $ownerUser)
    {
        $this->ownerUsers->removeElement($ownerUser);
    }

    /**
     * Get ownerUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOwnerUsers()
    {
        return $this->ownerUsers;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     *
     * @return Product
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set producedAt
     *
     * @param \DateTime $producedAt
     *
     * @return Product
     */
    public function setProducedAt($producedAt)
    {
        $this->producedAt = $producedAt;

        return $this;
    }

    /**
     * Get producedAt
     *
     * @return \DateTime
     */
    public function getProducedAt()
    {
        return $this->producedAt;
    }

    /**
     * Set storedAt
     *
     * @param \DateTime $storedAt
     *
     * @return Product
     */
    public function setStoredAt($storedAt)
    {
        $this->storedAt = $storedAt;

        return $this;
    }

    /**
     * Get storedAt
     *
     * @return \DateTime
     */
    public function getStoredAt()
    {
        return $this->storedAt;
    }

    /**
     * Set useBefore
     *
     * @param \DateTime $useBefore
     *
     * @return Product
     */
    public function setUseBefore($useBefore)
    {
        $this->useBefore = $useBefore;

        return $this;
    }

    /**
     * Get useBefore
     *
     * @return \DateTime
     */
    public function getUseBefore()
    {
        return $this->useBefore;
    }


    /**
     * Add file
     *
     * @param \Tracker\Model\Base\File $file
     *
     * @return Product
     */
    public function addFile(\Tracker\Model\Base\File $file)
    {
        $this->files[] = $file;

        return $this;
    }

    /**
     * Remove file
     *
     * @param \Tracker\Model\Base\File $file
     */
    public function removeFile(\Tracker\Model\Base\File $file)
    {
        $this->files->removeElement($file);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }
}
