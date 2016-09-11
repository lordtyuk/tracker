<?php

namespace Tracker\Model\Base;
use Doctrine\ORM\Mapping as ORM;
/**
 * OA2AuthCode
 *
 * @ORM\MappedSuperclass
 */
class OA2AuthCode extends \Tracker\Model\Inherit\Model
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $identifier;

    /**
     * @var string
     *
     * @ORM\Column(name="redirectUri", type="string", precision=0, scale=0, nullable=false, unique=false)
     */
    private $redirectUri;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="isRevoked", type="boolean")
	 */
	private $isRevoked;

    /**
     * @ORM\OneToOne(targetEntity="Tracker\Model\User")
     * @ORM\JoinColumn(name="userId", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="Tracker\Model\OA2Client")
     * @ORM\JoinColumn(name="clientId", referencedColumnName="id")
     */
    private $client;

    /**
     * @ORM\ManyToMany(targetEntity="Tracker\Model\OA2Scope")
     * @ORM\JoinTable(name="oa2authcode_oa2scope",
     *      joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="scopeId", referencedColumnName="id", unique=true)}
     *      )
     */
    private $scopes;

    /**
     * @ORM\Column(type="date")
     */
    private $expiryDateTime;

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
     * Set isRevoked
     *
     * @param boolean $isRevoked
     *
     * @return OA2AuthCode
     */
    public function setIsRevoked($isRevoked)
    {
        $this->isRevoked = $isRevoked;

        return $this;
    }

    /**
     * Get isRevoked
     *
     * @return boolean
     */
    public function getIsRevoked()
    {
        return $this->isRevoked;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     *
     * @return OA2AuthCode
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
     * Set expiryDateTime
     *
     * @param \DateTime $expiryDateTime
     *
     * @return OA2AuthCode
     */
    public function setExpiryDateTime(\DateTime $expiryDateTime)
    {
        $this->expiryDateTime = $expiryDateTime;

        return $this;
    }

    /**
     * Get expiryDateTime
     *
     * @return \DateTime
     */
    public function getExpiryDateTime()
    {
        return $this->expiryDateTime;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->scopes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set user
     *
     * @param \Tracker\Model\User $user
     *
     * @return OA2AuthCode
     */
    public function setUser(\Tracker\Model\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Tracker\Model\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set client
     *
     * @param \Tracker\Model\OA2Client $client
     *
     * @return OA2AuthCode
     */
    public function setClient(\League\OAuth2\Server\Entities\ClientEntityInterface $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Tracker\Model\OA2Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Add scope
     *
     * @param \Tracker\Model\OA2Scope $scope
     *
     * @return OA2AuthCode
     */
    public function addScope(\League\OAuth2\Server\Entities\ScopeEntityInterface $scope)
    {
        $this->scopes[] = $scope;

        return $this;
    }

    /**
     * Remove scope
     *
     * @param \Tracker\Model\OA2Scope $scope
     */
    public function removeScope(\Tracker\Model\OA2Scope $scope)
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
     * Set redirectUri
     *
     * @param string $redirectUri
     *
     * @return OA2AuthCode
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    /**
     * Get redirectUri
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }
}
