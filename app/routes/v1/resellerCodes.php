<?php

namespace Tracker;

/* @var \Bullet\App $app */

use Bullet\Request;

$app->path('resellercodes', function (Request $request) use ($app) {

	if(!$request->isOptions()) {
		//Auth::getInstance()->checkAccess('userRole');
	}

	$app->get(function($request) use($app) {
		$repo = em()->getRepository('Tracker\Model\User');
		/* @var \Tracker\Model\UserRepository $repo */
		$data = $repo->getResellerCodes();

		return getResponse($data);
	});
});
