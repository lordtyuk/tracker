<?php

namespace Tracker\Model\Base;
use Doctrine\ORM\Mapping as ORM;
/**
 * OA2AccessToken
 *
 * @ORM\MappedSuperclass
 */
class OA2AccessToken extends \Tracker\Model\Inherit\Model
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
     * @var boolean
     *
     * @ORM\Column(name="isRevoked", type="boolean", options={"default":0})
     */
    private $isRevoked;


	/**
	 * @ORM\ManyToOne(targetEntity="Tracker\Model\User")
	 * @ORM\JoinColumn(name="userId", referencedColumnName="id")
	 */
	private $user;

	/**
	 * @ORM\ManyToOne(targetEntity="Tracker\Model\OA2Client")
	 * @ORM\JoinColumn(name="clientId", referencedColumnName="id")
	 */
	private $client;

	/**
	 * @ORM\Column(type="date")
	 */
	private $expiryDateTime;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->isRevoked = false;
    }

    /**
     * Get identifier
     *
     * @return integer
     */
    public function getIdentifier()
    {
        return $this->identifier;
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
     * Set identifier
     *
     * @param integer $identifier
     *
     * @return OA2AccessToken
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Set expiryDateTime
     *
     * @param \DateTime $expiryDateTime
     *
     * @return OA2AccessToken
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
     * Set client
     *
     * @param \Tracker\Model\OA2Client $client
     *
     * @return OA2AccessToken
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
     * Set user
     *
     * @param \Tracker\Model\User $user
     *
     * @return OA2AccessToken
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
     * Set isRevoked
     *
     * @param boolean $isRevoked
     *
     * @return OA2AccessToken
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
}
