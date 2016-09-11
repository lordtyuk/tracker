<?php

namespace Tracker\OAuth2;

use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\AuthCodeEntity;
use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\SessionInterface;

class SessionStorage extends AbstractStorage implements SessionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getByAccessToken(AccessTokenEntity $accessToken)
    {
        $tokenDoc = app()['mandango']
            ->getRepository('Byryby\\Model\\OA2AccessToken')
            ->createQuery()
            ->criteria(['accessToken' => $accessToken->getId()])
            ->one();

        if (!$tokenDoc) {
            throw new \Byryby\Exception\ModelException('The OAuth access token with ID ' . ((string) $accessToken->getId()) . ' could not be found.');
        }

        $session = $tokenDoc->getSession();

        return (new SessionEntity($this->server))
            ->setId((string) $session->getId())
            ->setOwner($session->getOwnerType(), $session->getOwnerId());
    }

    /**
     * {@inheritdoc}
     */
    public function getByAuthCode(AuthCodeEntity $authCode)
    {
        $authCodeDoc = app()['mandango']
            ->getRepository('Byryby\\Model\\OA2Session')
            ->createQuery()
            ->criteria(['accessToken' => $authCode->getId()]);

        if (!$authCodeDoc) {
            throw new \Byryby\Exception\ModelException('The OAuth auth code with ID ' . ((string) $authCode->getId()) . ' could not be found.');
        }

        $session = $authCodeDoc->getSession();

        return (new SessionEntity($this->server))
            ->setId((string) $session->getId())
            ->setOwner($session->getOwnerType(), $session->getOwnerId());
    }

    /**
     * {@inheritdoc}
     */
    public function getScopes(SessionEntity $session)
    {
        $sessionDoc = app()['mandango']
            ->getRepository('Byryby\\Model\\OA2Session')
            ->findOneById($session->getId());

        if (!$sessionDoc) {
            return;
        }

        $scopes = [];
        foreach ($sessionDoc->getScopes() as $scope) {
            $scopes[] = (new ScopeEntity($this->server))->hydrate([
                'id'          => (string)$scope->getId(),
                'description' => $scope->getName()
            ]);
        }

        return $scopes;
    }

    /**
     * {@inheritdoc}
     */
    public function create($ownerType, $ownerId, $clientId, $clientRedirectUri = null)
    {
        $clientDoc = app()['mandango']
            ->getRepository('Byryby\\Model\\OA2Client')
            ->findOneById($clientId);

        if (!$clientDoc) {
            throw new \Byryby\Exception\ModelException('The OAuth client with ID ' . ((string) $clientId) . ' could not be found.');
        }

        $sessionDoc = app()['mandango']
            ->create('Byryby\\Model\\OA2Session')
            ->setOwnerType($ownerType)
            ->setOwnerId($ownerId)
            ->setClient($clientDoc);

        $sessionDoc->save();

        return (string) $sessionDoc->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function associateScope(SessionEntity $session, ScopeEntity $scope)
    {
        $sessionDoc = app()['mandango']
            ->getRepository('Byryby\\Model\\OA2Session')
            ->findOneById($session->getId());

        if (!$sessionDoc) {
            throw new \Byryby\Exception\ModelException('The OAuth session with ID ' . ((string) $session->getId()) . ' could not be found.');
        }

        /*$scopeDoc = app()['mandango']
            ->getRepository('Byryby\\Model\\OA2Scope')
            ->createQuery()
            ->criteria(['scopeId' => $scope->getId()])
            ->one();*/

        $scopeDoc = app()['mandango']
            ->getRepository('Byryby\\Model\\Role')
            ->findOneById((string)$scope->getId());

        if (!$scopeDoc) {
            throw new \Byryby\Exception\ModelException('The OAuth scope ' . ((string) $scope->getId()) . ' could not be found.');
        }

        $sessionDoc->addScopes([$scopeDoc])->save();
    }

}
