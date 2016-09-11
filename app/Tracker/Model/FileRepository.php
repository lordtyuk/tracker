<?php

namespace Tracker\Model;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * FileRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FileRepository extends Base\FileRepository
{
	public function getByProduct(\Tracker\Model\Product $product)
	{


		$rsm = new ResultSetMapping();
		$rsm->addEntityResult('Tracker\Model\Base\File', 'f');
		$rsm->addFieldResult('f', 'f.id', 'id');

		return em()->createNativeQuery("SELECT f.* FROM file f INNER JOIN products_files pf ON f.id = pf.fileId WHERE pf.productId = :productId", $rsm)
			->setParameter(':productId', $product->getId())
			->getResult();
	}
}
