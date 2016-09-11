<?php

namespace Tracker\OAuth2;

use League\OAuth2\Server\Entity\AuthCodeEntity;
use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\AuthCodeInterface;

class AuthCodeStorage extends AbstractStorage implements AuthCodeInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($code)
    {
        $authCodeDoc = app()['mandango']
            ->getRepository('Byryby\\Model\\OA2AuthCode')
            ->createQuery()
            ->criteria([
                'authCode' => $code,
                'expireTime' => [
                    '$gte' => time()
                ]
            ])
            ->one();

        if ($authCodeDoc) {
            return (new AuthCodeEntity($this->server))
                ->setId($authCodeDoc->getAuthCode())
                ->setRedirectUri($authCodeDoc->getRedirectUri()->getUri())
                ->setExpireTime($authCodeDoc->getExpireTime());
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getScopes(AuthCodeEntity $token)
    {
        $authCodeDoc = findByFieldOrError('Byryby\\Model\\OA2AuthCode', $token->getId());

        $scopes = [];
        foreach ($authCodeDoc->getScopes() as $scope) {
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
    public function create($token, $expireTime, $sessionId, $redirectUri)
    {
        $sessionDoc = \Byryby\Model\OA2Session::findOrError($sessionId);

        $authCodeDoc = app()['mandango']
            ->create('Byryby\\Model\\OA2AuthCode')
            ->setAuthCode($token)
            ->setExpireTime($expireTime)
            ->setSession($sessionDoc)
            ->setRedirectUri(app()['mandango']
                ->create('Byryby\\Model\\OA2RedirectUri')
                ->setUri($redirectUri)
            );

        $authCodeDoc->save();
    }

    /**
     * {@inheritdoc}
     */
    public function associateScope(AuthCodeEntity $token, ScopeEntity $scope)
    {
        $authCodeDoc = app()['mandango']
            ->getRepository('Byryby\\Model\\OA2AuthCode')
            ->findById($token->getId());

        if (!$authCodeDoc) {
            throw new \Byryby\ModelException('The OAuth auth code ' . ((string) $token->getId()) . ' could not be found.');
        }

        $scopeDoc = app()['mandango']
            ->getRepository('Byryby\\Model\\OA2Scope')
            ->findById($scope->getId());

        if (!$tokenDoc) {
            throw new \Byryby\ModelException('The OAuth scope ' . ((string) $scope->getId()) . ' could not be found.');
        }

        $authCodeDoc
            ->addScopes([$scope])
            ->save();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(AuthCodeEntity $token)
    {
        app()['mandango']
            ->getRepository('Byryby\\Model\\OA2AuthCode')
            ->getCollection()
            ->deleteOne(['authCode' => $token->getId()]);
    }
}
