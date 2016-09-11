<?php

require __DIR__ . '/../app/common.php';

// Require paths/routes for web requests
$routesDir = __DIR__ . '/../app/routes/';

require $routesDir . 'v1.php';

$request = new Bullet\Request();

// Response
$app->run($request)->send();

