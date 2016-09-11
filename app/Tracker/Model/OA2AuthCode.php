<?php

namespace Tracker\Model;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * OA2AuthCode
 *
 * @ORM\Table(name="OA2AuthCode")
 * @ORM\Entity(repositoryClass="Tracker\Model\OA2AuthCodeRepository")
 */
class OA2AuthCode extends Base\OA2AuthCode implements AuthCodeEntityInterface
{

	public function setUserIdentifier($identifier)
	{
		$user = em()->find('Tracker\Model\User', $identifier);
		$this->setUser($user);
	}

	public function getUserIdentifier()
	{
		$this->getUser()->getId();
	}
}
