<?php

//CLIENTS
$repository = em()->getRepository('Tracker\Model\OA2Client');

$defeaultClient = new \Tracker\Model\OA2Client();
$defeaultClient->setName('defaultClient');
$defeaultClient->setRedirectUri('/');
$defeaultClient->save();

//SCOPES
$repository = em()->getRepository('Tracker\Model\OA2Scope');

$userRole = new \Tracker\Model\OA2Scope();
$userRole->setDescription('userRole');
$userRole->save();


$adminRole = new \Tracker\Model\OA2Scope();
$adminRole->setDescription('adminRole');
$adminRole->save();

$traderRole = new \Tracker\Model\OA2Scope();
$traderRole->setDescription('traderRole');
$traderRole->save();

//USERS
$repository = em()->getRepository('Tracker\Model\User');

$admin = new \Tracker\Model\User();
$admin->fromArray(
	[
		'email' => 'admin@admin.admin',
		'password' => 'admin',
		'name' => 'Admin'
	]
);

$admin->getScopes()->add($adminRole);
$admin->getScopes()->add($userRole);
$admin->getScopes()->add($traderRole);

$admin->save();

