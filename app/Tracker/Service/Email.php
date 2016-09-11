<?php

namespace Tracker\Service;

class Email
{

	static $instance = null;

	public static function getInstance()
	{
		if(!self::$instance) {

			self::$instance = new Email();
		}

		return self::$instance;
	}

	public function send(\Tracker\Service\EmailTemplates\EmailTemplateAbstract $template)
	{
		$service = new \Google_Service_Gmail(app()['google']);

		$mail = new \PHPMailer();

		$mail->IsHTML(true);
		$mail->CharSet = "text/html; charset=UTF-8;";
		$mail->IsSMTP();

		$mail->From = 'lordtyuk@gmail.com';
		$mail->FromName = 'lordtyuk@gmail.com'; // First name, last name
		$mail->AddAddress('lordtyuk@gmail.com', "First name last name");

		$mail->Subject =  'test';
		$mail->Body =  $template->render();

		$mail->preSend();
		$raw = $mail->getSentMIMEMessage();

		$user = 'me';
		$message_object = new \Google_Service_Gmail_Message();
		$encoded_message = rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
		$message_object->setRaw($encoded_message);
		$results = $service->users_messages->send($user, $message_object);
	}
}