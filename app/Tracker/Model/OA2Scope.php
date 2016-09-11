<?php

namespace Tracker\Model;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * OA2Scope
 *
 * @ORM\Table(name="OA2Scope")
 * @ORM\Entity(repositoryClass="Tracker\Model\OA2ScopeRepository")
 */
class OA2Scope extends Base\OA2Scope implements ScopeEntityInterface
{
	function jsonSerialize()
	{
		return json_encode($this->toArray());
	}
}
