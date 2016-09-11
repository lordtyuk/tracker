<?php

namespace Tracker\OAuth2;

use League\OAuth2\Server\Entity\RefreshTokenEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\RefreshTokenInterface;

class RefreshTokenStorage extends AbstractStorage implements RefreshTokenInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($token)
    {
        $tokenDoc = app()['mandango']
            ->getRepository('Byryby\\Model\\OA2RefreshToken')
            ->createQuery()
            ->criteria(['refreshToken' => $token])
            ->one();

        if ($tokenDoc) {
            return (new RefreshTokenEntity($this->server))
                ->setId($tokenDoc->getRefreshToken())
                ->setExpireTime($tokenDoc->getExpireTime())
                ->setAccessTokenId($tokenDoc->getAccessToken()->getAccessToken());
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function create($token, $expireTime, $accessToken)
    {
        $accessTokenDoc = app()['mandango']
            ->getRepository('Byryby\\Model\\OA2AccessToken')
            ->createQuery()
            ->criteria(['accessToken' => $accessToken])
            ->one();

        if (!$accessTokenDoc) {
            throw new \Byryby\ModelException('The OAuth2 access token with ID ' . ((string) $accessToken) . ' could not be found.');
        }

        $refreshTokenDoc = app()['mandango']
            ->create('Byryby\\Model\\OA2RefreshToken')
            ->setRefreshToken($token)
            ->setExpireTime($expireTime)
            ->setAccessToken($accessTokenDoc);

        $refreshTokenDoc->save();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(RefreshTokenEntity $token)
    {
        app()['mandango']
            ->getRepository('Byryby\\Model\\OA2RefreshToken')
            ->getCollection()
            ->deleteOne(['refreshToken' => $token->getId()]);
    }
}
