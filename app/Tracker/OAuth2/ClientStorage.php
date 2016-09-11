<?php

namespace Tracker\OAuth2;

use League\OAuth2\Server\Entity\ClientEntity;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\ClientInterface;

class ClientStorage extends AbstractStorage implements ClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function get($clientId, $clientSecret = null, $redirectUri = null, $grantType = null)
    {
        $clientQuery = app()['mandango']
            ->getRepository('Byryby\\Model\\OA2Client')
            ->createQuery()
            ->criteria(['_id' => new \MongoDB\BSON\ObjectID($clientId)]);

        if ($clientSecret !== null) {
            $clientQuery->mergeCriteria(['secret' => $clientSecret]);
        }

        if ($redirectUri) {
            $clientQuery->mergeCriteria(['redirectUris.uri' => $redirectUri]);
        }

        $clientDoc = $clientQuery->one();

        if (!$clientDoc) {
            return null;
        }

        return (new ClientEntity($this->server))
            ->hydrate([
                'id'    =>  $clientDoc->getId(),
                'name'  =>  $clientDoc->getName(),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBySession(SessionEntity $session)
    {
        /*
        $sessionDoc = app()['mandango']->getRepository('Byryby\\Model\\OA2Session')
            ->createQuery()
            ->criteria(['sessionId' => $session->getId()])
            ->one();
        */
        $sessionDoc = app()['mandango']->getRepository('Byryby\\Model\\OA2Session')->findOneById($session->getId());

        if ($sessionDoc) {
            $clientDoc = $sessionDoc->getClient();
            return (new ClientEntity($this->server))
                ->hydrate([
                    'id'    =>  (string) $clientDoc->getId(),
                    'name'  =>  $clientDoc->getName(),
                ]);
        }

        return;
    }
}
