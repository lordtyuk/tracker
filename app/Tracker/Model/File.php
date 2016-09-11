<?php

namespace Tracker\Model;
use Doctrine\ORM\Mapping as ORM;
/**
 * File
 *
 * @ORM\Table(name="File")
 * @ORM\Entity(repositoryClass="Tracker\Model\FileRepository")
 */
class File extends Base\File
{
	public function __construct()
	{
		parent::__construct();
		$this->setCreatedAt(new \DateTime("now"));
	}

}
