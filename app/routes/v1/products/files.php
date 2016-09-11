<?php

namespace Tracker;

/* @var \Bullet\App $app */
/* @var \Tracker\Model\Product $product */

use Bullet\Request;
use Tracker\Model\Base\File;
use Tracker\Model\Product;

$app->path('files', function (Request $request) use ($app, $product) {

	$app->param('int', function(Request $request, $fileId) use($app, $product) {

		$file = File::findById($fileId);
		/* @var \Tracker\Model\File $file */

		$app->delete(function(Request $request) use($app, $product, $file) {
			if(!$request->isOptions()) {
				Auth::getInstance()->checkAccess('adminRole');
			}

			$product->removeFile($file);
			$product->save();

			return $app->response(204, null);
		});

	});
});
