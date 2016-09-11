<?php

namespace Tracker\OAuth2;

use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\ClientEntity;
use League\OAuth2\Server\Entity\RefreshTokenEntity;
use League\OAuth2\Server\Entity\SessionEntity;
use League\OAuth2\Server\Event;
use League\OAuth2\Server\Exception;
use League\OAuth2\Server\Util\SecureKey;
use League\OAuth2\Server\Grant\AbstractGrant;

/**
 * VerifyEmail grant class
 */
class VerifyEmailGrant extends AbstractGrant
{
    /**
     * Grant identifier
     *
     * @var string
     */
    protected $identifier = 'verify_email';

    /**
     * Response type
     *
     * @var string
     */
    protected $responseType;

    /**
     * Access token expires in override
     *
     * @var int
     */
    protected $accessTokenTTL;

    /**
     * Complete the password grant
     *
     * @return array
     *
     * @throws
     */
    public function completeFlow()
    {
        $app = app();
        
        // Get the required params
        $clientId = $this->server->getRequest()->request->get('client_id', $this->server->getRequest()->getUser());
        if (is_null($clientId)) {
            throw new Exception\InvalidRequestException('client_id');
        }

        $clientSecret = $this->server->getRequest()->request->get('client_secret',
            $this->server->getRequest()->getPassword());
        if (is_null($clientSecret)) {
            throw new Exception\InvalidRequestException('client_secret');
        }

        // Validate client ID and client secret
        $client = $this->server->getClientStorage()->get(
            $clientId,
            $clientSecret,
            null,
            $this->getIdentifier()
        );

        if (($client instanceof ClientEntity) === false) {
            $this->server->getEventEmitter()->emit(new Event\ClientAuthenticationFailedEvent($this->server->getRequest()));
            throw new Exception\InvalidClientException();
        }

        $code = $this->server->getRequest()->request->get('code', null);
        if (is_null($code)) {
            throw new Exception\InvalidRequestException('code');
        }

        // Query the database.
        $users = $app['mandango']->getRepository('Byryby\\Model\\User')
            ->createQuery()
            ->criteria([
                'emails.verificationCode' => $code
            ])
            ->all();

        if (count($users) < 1) {
            throw new Exception\InvalidCredentialsException();
        }
        
        if (count($users) > 1) {
            $msg = 'More than one user is associated with the same e-mail verification code: ' . $code;
            $app['log']->alert($msg);
            throw new Exception\ServerErrorException($msg);
        }

        // ...so there's exactly one user with that verification code
        $user = array_pop($users);
        $userId = (string)$user->getId();

        /* @var \Byryby\Model\User $user */
        if($user->getPendingInvitation()) {
            throw new PendingInvitationException();
        }
        if($user->getDeleted()) {
            throw new DeletedException();
        }
        if($user->getSuspended()) {
            throw new SuspendedException();
        }

        // Set the e-mail to verified
        foreach ($user->getEmails() as $email) {
            if ($email->getVerificationCode() == $code) {
                $email->setVerificationCode(null);
                $email->setVerified(1);
                $user->save();
                break;
            }
        }

        // Validate any scopes that are in the request
        $scopeParam = $this->server->getRequest()->request->get('scope', '');
        $scopes = $this->validateScopes($scopeParam, $client);

        // Create a new session
        $session = new SessionEntity($this->server);
        $session->setOwner('user', $userId);
        $session->associateClient($client);

        // Generate an access token
        $accessToken = new AccessTokenEntity($this->server);
        $accessToken->setId(SecureKey::generate());
        $accessToken->setExpireTime($this->getAccessTokenTTL() + time());

        // Associate scopes with the session and access token
        foreach ($scopes as $scope) {
            $session->associateScope($scope);
        }

        foreach ($session->getScopes() as $scope) {
            $accessToken->associateScope($scope);
        }

        $this->server->getTokenType()->setSession($session);
        $this->server->getTokenType()->setParam('access_token', $accessToken->getId());
        $this->server->getTokenType()->setParam('expires_in', $this->getAccessTokenTTL());

        // Associate a refresh token if set
        if ($this->server->hasGrantType('refresh_token')) {
            $refreshToken = new RefreshTokenEntity($this->server);
            $refreshToken->setId(SecureKey::generate());
            $refreshToken->setExpireTime($this->server->getGrantType('refresh_token')->getRefreshTokenTTL() + time());
            $this->server->getTokenType()->setParam('refresh_token', $refreshToken->getId());
        }

        // Save everything
        $session->save();
        $accessToken->setSession($session);
        $accessToken->save();

        if ($this->server->hasGrantType('refresh_token')) {
            $refreshToken->setAccessToken($accessToken);
            $refreshToken->save();
        }

        return $this->server->getTokenType()->generateResponse();
    }
}
