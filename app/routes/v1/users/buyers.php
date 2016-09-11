<?php

namespace Tracker;

/* @var \Bullet\App $app */

use Bullet\Request;

/* @var \Tracker\Model\User $user */

$app->path('buyers', function(Request $request) use($app, $user) {

	$app->get(function(Request $request) use($app, $user) {
		$repo = em()->getRepository('Tracker\Model\User');
		/* @var \Tracker\Model\UserRepository $repo */
		$buyers = $repo->getBuyersByUser($user);

		return getResponse($buyers);
	});
});
