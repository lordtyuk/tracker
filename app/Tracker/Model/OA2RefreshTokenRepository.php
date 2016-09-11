<?php

namespace Tracker\Model;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

/**
 * OA2RefreshTokenRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OA2RefreshTokenRepository extends Base\OA2RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
	public function getNewRefreshToken()
	{
		return new OA2RefreshToken();
	}

	public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
	{
		/* @var OA2RefreshToken $refreshTokenEntity */
		$refreshTokenEntity->save();
	}

	public function revokeRefreshToken($tokenId)
	{
		$refreshToken = $this->findOneBy(['identifier' => $tokenId]);
		$refreshToken->setIsRevoked(true);
	}

	public function isRefreshTokenRevoked($tokenId)
	{
		$refreshToken = $this->findOneBy(['identifier' => $tokenId]);

		if($refreshToken)
			return $refreshToken->getIsRevoked();

		return true;
	}
}