<?php

namespace Tracker;

/* @var \Bullet\App $app */

use Bullet\Request;
use Tracker\Model\File;
use Tracker\Model\Product;

$app->path('files', function (Request $request) use ($app) {

	$app->param('int', function(Request $request, $productId) use($app) {

		$file = File::findById($productId);

		$app->delete(function(Request $request) use($app, $file) {

			if(!$request->isOptions()) {
				Auth::getInstance()->checkAccess('adminRole');
			}

			$file->delete();

			return $app->response(204, null);
		});

		$app->get(function(Request $request) use($app, $file) {

			return getResponse($file);
		});

	});

	$app->get(function(Request $request) use($app) {

		if(!$request->isOptions()) {
			Auth::getInstance()->checkAccess('adminRole');
		}

		$data = em()->getRepository('Tracker\Model\File')->findAll();

		return getResponse($data);
	});

	$app->post(function(Request $request) use($app) {

		if(!$request->isOptions()) {
			Auth::getInstance()->checkAccess('adminRole');
		}

		if(!isset($_FILES['files']))
			return '';

		$ids = ($request->get('ids'));

		if(!count($ids)) {
			return '';
		}

		$products = [];
		foreach($ids as $id) {
			$products[] = Product::findById($id);
		}

		foreach ($_FILES["files"]["error"] as $key => $error) {
			if ($error == UPLOAD_ERR_OK) {
				$tmp_name = $_FILES["files"]["tmp_name"][$key];
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				$name = basename($_FILES["files"]["name"][$key]);
				move_uploaded_file($tmp_name, __DIR__.'/../../../web/storage/'.$name);

				$file = new \Tracker\Model\File();
				$file->setTitle($name);
				$file->setPath('/storage/'.$name);
				$file->setCreatorUser(Auth::getInstance()->getUser());
				$file->save();

				foreach($products as $product) {
					/* @var \Tracker\Model\Product $product */
					$product->getFiles()->add($file);
					$product->save();
				}

			}
		}


		return getResponse($file);
	});
});
