<?php

namespace Tracker;

/* @var \Bullet\App $app */

use Bullet\Request;
use Tracker\Model\User;

$app->path('token', function (Request $request) use ($app) {
	$app->post(function(Request $request) {
		$server = app()['oauth2_server_auth'];
		/* @var \League\OAuth2\Server\AuthorizationServer $server */

		$request = HttpServerRequest::fromBulletRequest($request);
		$response = new HttpServerResponse();
		$server->respondToAccessTokenRequest($request, $response);

		return $response->getBody()->getContents();
	});
});
