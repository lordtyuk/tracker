<?php

namespace Tracker\OAuth2;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenStorage implements AccessTokenRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($token)
    {
        try {
            $tokenDoc = findByFieldOrError('Byryby\\Model\\OA2AccessToken', 'accessToken', $token);
        } catch (\Byryby\ModelExcepion $e) {
            return null;
        }

        return (new AccessTokenEntity($this->server))
            ->setId($tokenDoc->getAccessToken())
            ->setExpireTime($tokenDoc->getExpireTime());
    }

    /**
     * {@inheritdoc}
     */
    public function getScopes(AccessTokenEntity $token)
    {
        $tokenDoc = findByFieldOrError('Byryby\\Model\\OA2AccessToken', 'accessToken', $token->getId());

        $scopes = [];
        foreach ($tokenDoc->getScopes() as $scope) {
            $scopes[] = (new ScopeEntity($this->server))->hydrate([
                'id'            =>  $scope->getScopeId(),
                'description'   =>  $scope->getDescription(),
            ]);
        }

        return $scopes;
    }

    /**
     * {@inheritdoc}
     */
    public function create($token, $expireTime, $sessionId)
    {
        $sessionDoc = \Byryby\Model\OA2Session::findOrError($sessionId);

        $accessTokenDoc = app()['mandango']
            ->create('Byryby\\Model\\OA2AccessToken')
            ->setAccessToken($token)
            ->setExpireTime($expireTime)
            ->setSession($sessionDoc); // TODO: can we use setSession_reference_field ?

        $accessTokenDoc->save();
    }

    /**
     * {@inheritdoc}
     */
    public function associateScope(AccessTokenEntity $token, ScopeEntity $scope)
    {
        $tokenDoc = findByFieldOrError('Byryby\\Model\\OA2AccessToken', 'accessToken', $token->getId());
        //$scopeDoc = findByFieldOrError('Byryby\\Model\\OA2Scope', 'scopeId', $scope->getId());
        $scopeDoc = \Byryby\Model\Role::findOrError((string)$scope->getId());

        $tokenDoc->addScopes([$scopeDoc])->save();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(AccessTokenEntity $token)
    {
        app()['mandango']
            ->getRepository('Byryby\\Model\\OA2AccessToken')
            ->getCollection()
            ->deleteOne(['accessToken' => $token->getId()]);
    }
}
