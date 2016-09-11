<?php

namespace Tracker\Model\Inherit;

class Model
{
	public static function findById($id)
	{
		return em()->getRepository(static::class)->find($id);
	}

	public function fromArray($data)
	{
		$meta = em()->getClassMetadata(static::class);

		foreach($meta->getFieldNames() as $item) {

			if($item == 'id' || $item == 'createdAt')
				continue;

			if(isset($data[$item]))
				$this->{'set'.$item}($data[$item]);
		}

		return $this;
	}

	public function toArray()
	{
		$meta = em()->getClassMetadata(static::class);
		$obj = [];
		foreach($meta->getFieldNames() as $fieldName) {
			$obj[$fieldName] = $this->{'get'.$fieldName}();
			if(is_object($obj[$fieldName])) {
				if(get_class($obj[$fieldName]) == 'DateTime') {
					/* @var \Datetime $obj [$fieldName] */
					$obj[$fieldName] = $obj[$fieldName]->format('Y-m-d H:i:s');
				}
			}
		}
		return $obj;
	}

	public function save()
	{
		em()->persist($this);
		em()->flush();

		return $this;
	}

	public function delete()
	{
		em()->remove($this);
		em()->flush();
	}
}