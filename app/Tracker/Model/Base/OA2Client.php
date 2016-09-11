<?php

namespace Tracker\Model\Base;
use Doctrine\ORM\Mapping as ORM;
/**
 * OA2Client
 *
 * @ORM\MappedSuperclass
 */
class OA2Client extends \Tracker\Model\Inherit\Model
{

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $identifier;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", length=140, precision=0, scale=0, nullable=false, unique=false)
	 */
	private $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="redirectUri", type="string", length=140, precision=0, scale=0, nullable=false, unique=false)
	 */
	private $redirectUri;

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
     * Set name
     *
     * @param string $name
     *
     * @return OA2Client
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
     * Set redirectUri
     *
     * @param string $redirectUri
     *
     * @return OA2Client
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
