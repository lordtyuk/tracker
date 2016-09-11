<?php

namespace Tracker;

/* @var \Bullet\App $app */

use Bullet\Request;
use Tracker\Exception\MustAuthenticate;
use Tracker\Model\User;

$app->path('me', function(Request $request) use($app) {

	if(!$request->isOptions()) {
		if(!Auth::getInstance()->getUser())
			throw new MustAuthenticate();
	}

	$user = Auth::getInstance()->getUser();

	require 'products.php';
	require 'password.php';
	require 'buyers.php';

	$app->get(function(Request $request) use($app, $user) {
		return getResponse($user);
	});

	$app->put(function(Request $request) use($app, $user) {
		$data = json_decode($request->raw(), true);

		$user->fromArray($data);
		$user->save();

		return getResponse($user);
	});

});

