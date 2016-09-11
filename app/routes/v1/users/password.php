<?php

namespace Tracker;

/* @var \Bullet\App $app */

use Bullet\Request;
use Tracker\Model\Product;

/* @var \Tracker\Model\User $user */

$app->path('password', function(Request $request) use($app, $user) {

	$app->delete(function(Request $request) use($app, $user) {

		$password = substr(md5(time()), 0, 8);
		$user->fromArray(['password' => $password]);
		$user->save();

		$mail = getMailObject();


		return 204;
	});
});
