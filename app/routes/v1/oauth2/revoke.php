<?php

namespace Tracker;

/* @var \Bullet\App $app */

use Bullet\Request;
use Tracker\Model\User;

$app->path('revoke', function (Request $request) use ($app) {
	$app->get(function(Request $request) {

		$accessToken = Auth::getInstance()->getAccessToken();
		$accessToken->setisRevoked(true);
		$accessToken->save();

		return 200;
	});
});
