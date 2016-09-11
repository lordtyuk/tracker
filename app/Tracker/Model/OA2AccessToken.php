<?php

namespace Tracker\Model;
use Doctrine\ORM\Mapping as ORM;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

/**
 * OA2AccessToken
 *
 * @ORM\Table(name="OA2AccessToken")
 * @ORM\Entity(repositoryClass="Tracker\Model\OA2AccessTokenRepository")
 */
class OA2AccessToken extends Base\OA2AccessToken implements AccessTokenEntityInterface
{
	public function convertToJWT(CryptKey $privateKey)
	{
		return (new Builder())
				->setAudience($this->getClient()->getIdentifier())
				->setId($this->getIdentifier(), true)
				->setIssuedAt(time())
				->setNotBefore(time())
				->setExpiration($this->getExpiryDateTime()->getTimestamp())
				->setSubject($this->getUserIdentifier())
				->set('scopes', $this->getScopes())
				->sign(new Sha256(), new Key($privateKey->getKeyPath(), $privateKey->getPassPhrase()))
				->getToken();
	}

	public function setUserIdentifier($identifier)
	{
		$user = em()->getRepository('Tracker\Model\User')->findOneBy(['id' => $identifier]);
		$this->setUser($user);
	}

	public function getUserIdentifier()
	{
		return $this->getUser()->getId();
	}


	/**
	 * Add scope
	 *
	 * @param \Tracker\Model\OA2Scope $scope
	 *
	 * @return OA2AccessToken
	 */
	public function addScope(\League\OAuth2\Server\Entities\ScopeEntityInterface  $scope)
	{
		$this->getUser()->getScopes()->add($scope);

		return $this;
	}

	/**
	 * Remove scope
	 *
	 * @param \Tracker\Model\OA2Scope $scope
	 */
	public function removeScope(\Tracker\Model\OA2Scope $scope)
	{
		$this->getUser()->getScopes()->removeElement($scope);
	}

	/**
	 * Get scopes
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getScopes()
	{
		return $this->getUser()->getScopes();
	}


}
