<?php

namespace Tracker;

use Tracker\Exception\PermissionDenied;

class Auth
{
	/**
	 * @var Auth The reference to *Singleton* instance of this class
	 */
	private static $instance;

	private $user;
	private $userId;
	private $accessTokenId;
	private $accessToken;
	private $scopes = [];

	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @return Auth The *Singleton* instance.
	 */
	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Protected constructor to prevent creating a new instance of the
	 * *Singleton* via the `new` operator from outside of this class.
	 */
	protected function __construct()
	{
	}

	/**
	 * Private clone method to prevent cloning of the instance of the
	 * *Singleton* instance.
	 *
	 * @return void
	 */
	private function __clone()
	{
	}

	/**
	 * Private unserialize method to prevent unserializing of the *Singleton*
	 * instance.
	 *
	 * @return void
	 */
	private function __wakeup()
	{
	}

	public function setUserId($userId)
	{
		$this->userId = $userId;
	}

	public function setAccessTokenId($accessTokenId)
	{
		$this->accessTokenId = $accessTokenId;
	}

	/*
	 * @return Tracker\Model\OA2AccessToken
	 */
	public function getAccessToken()
	{
		if(!$this->accessToken) {
			$this->accessToken = em()->getRepository('Tracker\Model\OA2AccessToken')->findOneBy(['identifier' => $this->accessTokenId]);
		}

		return $this->accessToken;
	}

	/*
	 * @return Tracker\Model\User
	 */
	public function getUser()
	{
		if(!$this->user) {
			$this->user = em()->getRepository('Tracker\Model\User')->findOneBy(['id' => $this->userId]);
			if($this->user) {
				$_scopes = $this->user->getScopes();
				$this->scopes = [];
				foreach ($_scopes as $scope) {
					/* @var \Tracker\Model\OA2Scope $scope */
					$this->scopes[] = $scope->getDescription();
				}
			}
		}

		return $this->user;
	}

	public function checkAccess($scope)
	{
		$this->getUser();

		if(!in_array($scope, $this->scopes))
			throw new PermissionDenied();
	}

}
