<?php

namespace Tracker;

/* @var \Bullet\App $app */

use Bullet\Request;
use Tracker\Model\Product;

/* @var \Tracker\Model\User $user */

$app->path('products', function(Request $request) use($app, $user) {

	$app->get(function(Request $request) use($app, $user) {
		$products = $user->getOwnedProducts()->toArray();
		return getResponse($products);
	});

	$app->post(function(Request $request) use($app, $user) {
		$data = json_decode($request->raw(), true);

		$repo = em()->getRepository('Tracker\Model\Product');
		/* @var \Tracker\Model\ProductRepository $repo */
		$product = $repo->findByIdentifier($data['identifier']);

		$user->addOwnedProduct($product);
		$user->save();

		return getResponse($product);
	});
});
