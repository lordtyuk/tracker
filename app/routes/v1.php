<?php

namespace Tracker;

/* @var \Bullet\App $app */

$app->path('v1', function ($request) use ($app) {
	require 'v1/products.php';
	require 'v1/resellerCodes.php';
	require 'v1/users.php';
	require 'v1/oauth2.php';
	require 'v1/files.php';
});
