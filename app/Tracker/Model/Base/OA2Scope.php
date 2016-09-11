<?php

namespace Tracker\Model\Base;
use Doctrine\ORM\Mapping as ORM;
/**
 * OA2Scope
 *
 * @ORM\MappedSuperclass
 */
class OA2Scope extends \Tracker\Model\Inherit\Model
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
	 * @ORM\Column(name="description", type="string", length=140, precision=0, scale=0, nullable=false, unique=false)
	 */
	private $description;

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
     * Set description
     *
     * @param string $description
     *
     * @return OA2Scope
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
}
