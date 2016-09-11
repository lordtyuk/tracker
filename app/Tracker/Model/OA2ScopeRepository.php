<?php

namespace Tracker\Model;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

/**
 * OA2ScopeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OA2ScopeRepository extends Base\OA2ScopeRepository implements ScopeRepositoryInterface
{
	public function getScopeEntityByIdentifier($identifier)
	{
		return $this->find($identifier);
	}

	public function getScopeEntityByName($name)
	{
		return $this->findOneBy(['description' => $name]);
	}

	public function finalizeScopes(
		array $scopes,
		$grantType,
		ClientEntityInterface $clientEntity,
		$userIdentifier = null
	)
	{
		return $scopes;
	}
}
