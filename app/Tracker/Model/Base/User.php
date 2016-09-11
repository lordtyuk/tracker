<?php

namespace Tracker\Model\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\MappedSuperclass
 */
class User extends \Tracker\Model\Inherit\Model
{

    /**
     * @var string
     *
     * @ORM\Column(name="hashedPassword", type="string", length=140, precision=0, scale=0, nullable=false, unique=false)
     */
    private $hashedPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=140, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=140, precision=0, scale=0, nullable=false, unique=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="reseller_code", type="string", length=3, precision=0, scale=0, nullable=true, unique=false)
     */
    private $resellerCode;

    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=140, precision=0, scale=0, nullable=true, unique=false)
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=140, precision=0, scale=0, nullable=true, unique=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=140, precision=0, scale=0, nullable=true, unique=false)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=140, precision=0, scale=0, nullable=true, unique=false)
     */
    private $phone;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tracker\Model\Base\OA2Scope")
     * @ORM\JoinTable(name="users_oa2scope",
     *   joinColumns={
     *     @ORM\JoinColumn(name="userId", referencedColumnName="id", nullable=true)
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="scopeId", referencedColumnName="id", nullable=true)
     *   }
     * )
     */
    private $scopes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Tracker\Model\Base\Product")
     * @ORM\JoinTable(name="users_products",
     *   joinColumns={
     *     @ORM\JoinColumn(name="userId", referencedColumnName="id", nullable=true)
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="productId", referencedColumnName="id", nullable=true)
     *   }
     * )
     */
    protected $ownedProducts;

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * Set hashedPassword
     *
     * @param string $hashedPassword
     *
     * @return User
     */
    public function setHashedPassword($hashedPassword)
    {
        $this->hashedPassword = $hashedPassword;

        return $this;
    }

    /**
     * Get hashedPassword
     *
     * @return string
     */
    public function getHashedPassword()
    {
        return $this->hashedPassword;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->scopes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ownedProducts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add scope
     *
     * @param \Tracker\Model\Base\OA2Scope $scope
     *
     * @return User
     */
    public function addScope(\Tracker\Model\Base\OA2Scope $scope)
    {
        $this->scopes[] = $scope;

        return $this;
    }

    /**
     * Remove scope
     *
     * @param \Tracker\Model\Base\OA2Scope $scope
     */
    public function removeScope(\Tracker\Model\Base\OA2Scope $scope)
    {
        $this->scopes->removeElement($scope);
    }

    /**
     * Get scopes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * Add scope
     *
     * @param \Tracker\Model\Base\Product $product
     *
     * @return User
     */
    public function addOwnedProduct(\Tracker\Model\Base\Product $product)
    {
        $this->ownedProducts[] = $product;

        return $this;
    }

    /**
     * Remove scope
     *
     * @param \Tracker\Model\Base\Product $product
     */
    public function removeOwnedProduct(\Tracker\Model\Base\Product $product)
    {
        $this->ownedProducts->removeElement($product);
    }

    /**
     * Get ownedProducts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOwnedProducts()
    {
        return $this->ownedProducts;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return User
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
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
     * Set street
     *
     * @param string $street
     *
     * @return User
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
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set resellerCode
     *
     * @param string $resellerCode
     *
     * @return User
     */
    public function setResellerCode($resellerCode)
    {
        $this->resellerCode = $resellerCode;

        return $this;
    }

    /**
     * Get resellerCode
     *
     * @return string
     */
    public function getResellerCode()
    {
        return $this->resellerCode;
    }
}
