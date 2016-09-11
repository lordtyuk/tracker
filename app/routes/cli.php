<?php

namespace Tracker;

/* @var \Bullet\App $app */

use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Tracker\Model\File;
use Tracker\Model\Product;
use Tracker\Model\User;
use Tracker\Model\UserProduct;

$app->path('build', function ($request) use ($app) {
	$tool = new \Doctrine\ORM\Tools\SchemaTool(em());

	$classes = [];
	$toUpdateclasses = [];

	foreach (new \DirectoryIterator(__DIR__.'/../Tracker/Model') as $fileInfo) {
		if($fileInfo->isDot() || $fileInfo->isDir() || substr($fileInfo->getBasename('.php'), -1,1) == '~' || substr($fileInfo->getBasename('.php'), -10,10) == 'Repository')
			continue;

		$classes[] = em()->getClassMetadata("Tracker\\Model\\Base\\".$fileInfo->getBasename('.php'));
		$toUpdateclasses[] = em()->getClassMetadata("Tracker\\Model\\".$fileInfo->getBasename('.php'));
	}

	$tool->updateSchema($toUpdateclasses);

	// Create EntityGenerator
	$entityGenerator = new \Doctrine\ORM\Tools\EntityGenerator();
	$repoGenerator = new \Doctrine\ORM\Tools\EntityRepositoryGenerator();


	$entityGenerator->setGenerateAnnotations(true);
	$entityGenerator->setGenerateStubMethods(true);
	$entityGenerator->setRegenerateEntityIfExists(false);
	$entityGenerator->setUpdateEntityIfExists(true);
	//$entityGenerator->setNumSpaces($input->getOption('num-spaces'));
	$entityGenerator->setBackupExisting(true);

	$entityGenerator->setClassToExtend('Tracker\Model\Inherit\Model');

	// Generating Entities
	$entityGenerator->generate($classes, __DIR__.'/..');

	foreach ($classes as $metadata) {
		echo sprintf("Processing entity %s\n", $metadata->name);
		$repoGenerator->writeEntityRepositoryClass($metadata->name.'Repository', __DIR__.'/..');
	}

});

$app->path('loadData', function($request) {
	require_once __DIR__.'/../data/fixtures.php';
});
