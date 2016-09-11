<?php

namespace Tracker\Model\Base;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

/**
 * OA2RefreshToken
 *
 * @ORM\MappedSuperclass
 */
class OA2RefreshToken extends \Tracker\Model\Inherit\Model
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
     * @ORM\OneToOne(targetEntity="Tracker\Model\OA2AccessToken")
     * @ORM\JoinColumn(name="accessTokenId", referencedColumnName="id")
     */
    private $accessToken;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="isRevoked", type="boolean")
	 */
	private $isRevoked;

    /**
     * @ORM\Column(type="date")
     */
    private $expiryDateTime;

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
     * Set isRevoked
     *
     * @param boolean $isRevoked
     *
     * @return OA2RefreshToken
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
     * @param string $identifier
     *
     * @return OA2RefreshToken
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
     * @return OA2RefreshToken
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
     * Set accessToken
     *
     * @param \Tracker\Model\OA2AccessToken $accessToken
     *
     * @return OA2RefreshToken
     */
    public function setAccessToken(AccessTokenEntityInterface $accessToken = null)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken
     *
     * @return \Tracker\Model\OA2AccessToken
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
