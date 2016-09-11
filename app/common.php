<?php

require __DIR__ . '/../vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Tracker\Auth;


define('APPLICATION_NAME', 'Tracker');


$app = $GLOBALS['app'] = new Bullet\App([]);

$paths = array(__DIR__.'\\Tracker\\Model\\Base');
$isDevMode = true;

// the connection configuration
$dbParams = array(
		'driver'   => 'pdo_mysql',
		'user'     => 'tracker',
		'password' => 'tracker',
		'dbname'   => 'tracker',
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$app['em'] = EntityManager::create($dbParams, $config);


$app['oauth2_storage_accesstoken'] = function ($app) {
	return new Tracker\Model\OA2AccessTokenRepository(em(), em()->getClassMetadata('Tracker\Model\OA2AccessToken'));
};

$app['oauth2_storage_client'] = function ($app) {
	return new Tracker\Model\OA2ClientRepository(em(), em()->getClassMetadata('Tracker\Model\OA2Client'));
};

$app['oauth2_storage_scope'] = function ($app) {
	return new Tracker\Model\OA2ScopeRepository(em(), em()->getClassMetadata('Tracker\Model\OA2Scope'));
};

$app['oauth2_storage_user'] = function ($app) {
	return new Tracker\Model\UserRepository(em(), em()->getClassMetadata('Tracker\Model\User'));
};

$app['oauth2_storage_refreshtoken'] = function ($app) {
	return new Tracker\Model\OA2RefreshTokenRepository(em(), em()->getClassMetadata('Tracker\Model\OA2RefreshToken'));
};

$app['oauth2_storage_authcode'] = function ($app) {
	return new Tracker\Model\OA2AuthCodeRepository(em(), em()->getClassMetadata('Tracker\Model\OA2AuthCode'));
};

$app['oauth2_grant_password'] = function ($app) {
	return new \League\OAuth2\Server\Grant\PasswordGrant($app['oauth2_storage_user'], $app['oauth2_storage_refreshtoken']);
};
/*
$app['oauth2_grant_verify_email'] = function ($app) {
	return new \Tracker\OAuth2\VerifyEmailGrant();
};
*/

$app['oauth2_grant_refreshtoken'] = function ($app) {
	return new \League\OAuth2\Server\Grant\RefreshTokenGrant($app['oauth2_storage_refreshtoken']);
};

$app['oauth2_server_auth'] = $app->factory(function ($app) {
	$s = new \League\OAuth2\Server\AuthorizationServer(
			$app['oauth2_storage_client'],
			$app['oauth2_storage_accesstoken'],
			$app['oauth2_storage_scope'],
			__DIR__.'/../keys/private.key',
			__DIR__.'/../keys/public.key'
	);


	$s->enableGrantType($app['oauth2_grant_password']);
	$s->enableGrantType($app['oauth2_grant_refreshtoken']);

	if(app()->request()->header('Authorization')) {
		$request = \Tracker\HttpServerRequest::fromBulletRequest(app()->request());
		try {
			$authorizator = new \League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator($app['oauth2_storage_accesstoken']);
			$authorizator->setPrivateKey(new \League\OAuth2\Server\CryptKey(__DIR__ . '/../keys/private.key'));
			$authorizator->setPublicKey(new \League\OAuth2\Server\CryptKey(__DIR__ . '/../keys/public.key'));
			$authorizator->validateAuthorization($request);

			Auth::getInstance()->setUserId($request->getAttribute('oauth_user_id'));
			Auth::getInstance()->setAccessTokenId($request->getAttribute('oauth_access_token_id'));

		} catch(\League\OAuth2\Server\Exception\OAuthServerException $e) {
			//nothing to do, token expired or not found
		}
	}

	return $s;
});
if(app()->request()->header('Authorization')) {
	$app['oauth2_server_auth'];
}


// Shortcut to access $app instance anywhere
/**
 * @return Bullet\App
 */
function app()
{
	return $GLOBALS['app'];
}
/**
 * @return EntityManager
 */
function em()
{
	return app()['em'];
}

function getResponse($data)
{
	app()->response()->contentType('application/json');
	if(is_array($data)) {
		$return = [];
		foreach($data as $item) {
			if(!$item instanceof Tracker\Model\Inherit\Model) {
				$return[] = $item;
				continue;
			}
			/* @var Tracker\Model\Inherit\Model $item */
			$return[] = $item->toArray();
		}
	} else {
		if(!$data instanceof Tracker\Model\Inherit\Model) {
			return;
		}
		/* @var Tracker\Model\Inherit\Model $item */
		$return = $data->toArray();
	}
	return json_encode($return);
}

function getMailObject()
{
	$mail = new \PHPMailer();
	$mail->isSMTP();

	return $mail;
}

// Display exceptions with error and 500 status
$app->on('Exception', function (\Bullet\Request $request, \Bullet\Response $response, \Exception $e) use ($app) {

	$data = [
		'error' => 'server_error',
		'error_description' => 'An unexpected error occured'
	];

	// Debugging info for development ENV
	//if (BULLET_ENV !== 'production') {
	$data['exception'] = [
		'class'   => get_class($e),
		'message' => $e->getMessage(),
		'code'    => $e->getCode(),
		'file'    => $e->getFile(),
		'line'    => $e->getLine(),
		'trace'   => $e->getTrace()
	];
	//}

	$response->contentType('application/json');

	if(false) {
	} else {
		$data['error'] = 'unknown_error';
		$data['error_description'] = $e->getMessage();
	}

	//$app['log']->warning('Caught exception', $data);

	$response->content(json_encode($data));
});

// PermissionDenied
$app->on('Tracker\Exception\PermissionDenied', function (\Bullet\Request $request, \Bullet\Response $response) use ($app) {
	$response->contentType('application/json');
	$response->status(401);
	$response->content(json_encode(['error' => 'Permission denied', 'error_description' => 'The requested resource could not be located']));
});

// PermissionDenied
$app->on('Tracker\Exception\MustAuthenticate', function (\Bullet\Request $request, \Bullet\Response $response) use ($app) {
	$response->contentType('application/json');
	$response->status(401);
	$response->content(json_encode(['error' => 'Permission denied', 'error_description' => 'The requested resource could not be located']));
});


// Custom 404 Error Page
$app->on(404, function (\Bullet\Request $request, \Bullet\Response $response) use ($app) {
	$response->contentType('application/json');
	$response->content(json_encode(['error' => 'not_found', 'error_description' => 'The requested resource could not be located']));
});

// Custom 405 Error Page
$app->on(405, function (\Bullet\Request $request, \Bullet\Response $response) use ($app) {
	$response->contentType('application/json');
	$response->content(json_encode(['error' => 'not_allowed', 'error_description' => 'This HTTP method is allowed on this resource']));

});

$app->on('after', function(\Bullet\Request $request, \Bullet\Response $response) use($app) {
	$response->header("Access-Control-Allow-Origin", "*");

	if($request->isOptions()) {
		$response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH');
		$response->header('Access-Control-Allow-Headers', '*');
		$response->header("Access-Control-Allow-Headers", "Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	}

});