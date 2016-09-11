<?php

namespace Tracker\Model;
use Doctrine\ORM\Mapping as ORM;
use Tracker\Auth;

/**
 * Product
 *
 * @ORM\Table(name="Product")
 * @ORM\Entity(repositoryClass="Tracker\Model\ProductRepository")
 */
class Product extends Base\Product
{
	public function __construct()
	{
		parent::__construct();
		$this->setCreatedAt(new \DateTime("now"));
		$this->setCreatorUser(Auth::getInstance()->getUser());
	}

	public function fromArray($data)
	{
		if(isset($data['resellerCode']))
			$data['identifier'] = $data['resellerCode'].$data['identifier'];

		parent::fromArray($data);
	}

	public function toArray()
	{
		$data = parent::toArray();
		$files = $this->getFiles()->toArray();
		$data['files'] = [];
		foreach($files as $file) {
			/* @var \Tracker\Model\File $file */
			$data['files'][] = $file->toArray();
		}

		return $data;
	}
}
