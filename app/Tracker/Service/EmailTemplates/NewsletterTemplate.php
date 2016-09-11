<?php

namespace Tracker\Service\EmailTemplates;

class NewsletterTemplate extends EmailTemplateAbstract
{

	public function __construct()
	{
		$this->templatePath = __DIR__.'/Templates/newsletter.phtml';
	}
}