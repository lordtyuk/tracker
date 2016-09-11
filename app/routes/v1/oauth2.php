<?php

namespace Tracker;

/* @var \Bullet\App $app */

use Bullet\Request;
use Tracker\Model\User;

$app->path('oauth2', function (Request $request) use ($app) {
	require 'oauth2/token.php';
	require 'oauth2/revoke.php';
});
