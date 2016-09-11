<?php

namespace Tracker;

/* @var \Bullet\App $app */

use Bullet\Request;
use Tracker\Model\Product;

$app->path('products', function (Request $request) use ($app) {

	if(!$request->isOptions()) {
		//Auth::getInstance()->checkAccess('userRole');
	}

	$app->param('int', function(Request $request, $productId) use($app) {

		$product = Product::findById($productId);
		/* @var \Tracker\Model\Product $product */

		include('products/files.php');

		$app->delete(function(Request $request) use($app, $product) {
			$product->delete();

			return $app->response(204, null);
		});

		$app->get(function(Request $request) use($app, $product) {
			return getResponse($product);
		});

		$app->put(function(Request $request) use($app, $product) {
			$data = json_decode($request->raw(), true);

			$product->fromArray($data);
			$product->save();

			return getResponse($product);
		});

	});

	$app->get(function($request) use($app) {
		$data = em()->getRepository('Tracker\Model\Product')->findAll();

		return getResponse($data);
	});

	$app->post(function(Request $request) use($app) {
		$data = json_decode($request->raw(), true);

		$product = new \Tracker\Model\Product();
		if(isset($data['producedAt'])) {
			$data['producedAt'] = new \DateTime($data['producedAt']);
		}
		if(isset($data['useBefore'])) {
			$data['useBefore'] = new \DateTime($data['useBefore']);
		}
		if(isset($data['storedAt'])) {
			$data['storedAt'] = new \DateTime($data['storedAt']);
		}

		$product->fromArray($data);
		$product->save();

		return getResponse($product);
	});
});
