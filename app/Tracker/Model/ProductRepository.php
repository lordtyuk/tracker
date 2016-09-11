<?php

namespace Tracker\Model;
use Tracker\Model\Exception\NotFoundException;
use Tracker\Model\User;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends Base\ProductRepository
{
	/***
	 * @return \Tracker\Model\Product
	 * @throws NotFoundException
	 ***/
	public function findByIdentifier($identifier)
	{
		$product = $this->findOneBy(['identifier' => $identifier]);

		if(!$product) {
			throw new NotFoundException();
		}

		return $product;
	}
}
