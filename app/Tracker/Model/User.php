<?php

namespace Tracker\Model;
use Doctrine\ORM\Mapping as ORM;
use League\OAuth2\Server\Entities\UserEntityInterface;

/**
 * User
 *
 * @ORM\Table(name="User")
 * @ORM\Entity(repositoryClass="Tracker\Model\UserRepository")
 */
class User extends Base\User implements UserEntityInterface
{
	public function __construct()
	{
		parent::__construct();
		$this->setCreatedAt(new \DateTime("now"));
	}

	public function getIdentifier()
	{
		return $this->getId();
	}

	public function fromArray($data)
	{
		parent::fromArray($data);

		$scopeRepo = em()->getRepository('Tracker\Model\OA2Scope');

		if(isset($data['resellerCode'])) {
			/* @var \Tracker\Model\OA2ScopeRepository $scopeRepo */
			$scope = $scopeRepo->getScopeEntityByName('traderRole');
			if(!$this->getScopes()->contains($scope))
				$this->getScopes()->add($scope);
		}

		$scope = $scopeRepo->getScopeEntityByName('userRole');
		if(!$this->getScopes()->contains($scope))
			$this->getScopes()->add($scope);

		if(isset($data['password'])) {
			$this->setHashedPassword(password_hash($data['password'], PASSWORD_DEFAULT));
		}
		return $this;
	}

	public function toArray()
	{
		$data = parent::toArray();
		if(isset($data['hashedPassword']))
			unset($data['hashedPassword']);

		$data['scopes'] = $this->getScopes()->toArray();
		foreach($data['scopes'] as &$scope) {
			/* @var \Tracker\Model\OA2Scope $scope */
			$scope = $scope->toArray();
		}

		return $data;
	}
}
