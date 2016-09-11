<?php

namespace Tracker;

/* @var \Bullet\App $app */

use Bullet\Request;
use Tracker\Model\User;

$app->path('users', function (Request $request) use ($app) {

	require 'users/me.php';

	$app->param('int', function(Request $request, $userId) use($app) {

		$user = User::findById($userId);

		require 'users/products.php';

		$app->delete(function(Request $request) use($app, $user) {
			$user->delete();
			return $app->response(204, null);
		});

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

	$app->get(function($request) use($app) {
		$data = em()->getRepository('Tracker\Model\User')->findAll();
		return getResponse($data);
	});

	$app->post(function(Request $request) use($app) {
		$data = json_decode($request->raw(), true);

		$user = new \Tracker\Model\User();
		$user->fromArray($data);
		$user->save();

		return getResponse($user);
	});
});
