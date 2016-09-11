<?php

namespace Tracker\Service\EmailTemplates;

abstract class EmailTemplateAbstract
{
	protected $templatePath;

	public static function factory()
	{
		return new static;
	}

	public function bind($name, $value)
	{

	}

	public function render()
	{
		ob_start();
		include $this->templatePath;
		return ob_get_clean();
	}
}