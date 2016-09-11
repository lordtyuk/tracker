<?php

namespace Tracker\OAuth2;

use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\ScopeInterface;

class ScopeStorage extends AbstractStorage implements ScopeInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($scope, $grantType = null, $clientId = null)
    {

        $scopeDoc = app()['mandango']
            ->getRepository('Byryby\\Model\\OA2Scope')
            ->createQuery()
            ->criteria(['scopeId' => $scope])
            ->one();

        if ($scopeDoc) {
            return (new ScopeEntity($this->server))->hydrate([
                'id'          => (string)$scopeDoc->getId(),
                'description' => $scopeDoc->getName()
            ]);
        }

        return;
    }
}
