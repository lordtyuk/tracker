<?php

namespace Tracker;

use Bullet\Request;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class HttpServerRequest implements \Psr\Http\Message\ServerRequestInterface
{
	/* @var Request $request */
	private $request;
	private $attributes = [];

	public static function fromBulletRequest(Request $request)
	{
		$_request = new HttpServerRequest();
		$_request->request = $request;
		return $_request;
	}

	public function getProtocolVersion()
	{
		// TODO: Implement getProtocolVersion() method.
	}

	public function withProtocolVersion($version)
	{
		// TODO: Implement withProtocolVersion() method.
	}

	public function getHeaders()
	{
		// TODO: Implement getHeaders() method.
	}

	public function hasHeader($name)
	{
		return $this->request->header($name)?true:false;
	}

	public function getHeader($name)
	{
		return array($this->request->header($name));
	}

	public function getHeaderLine($name)
	{
		// TODO: Implement getHeaderLine() method.
	}

	public function withHeader($name, $value)
	{
		var_dump($name, $value);
		return $this;
	}

	public function withAddedHeader($name, $value)
	{
		// TODO: Implement withAddedHeader() method.
	}

	public function withoutHeader($name)
	{
		// TODO: Implement withoutHeader() method.
	}

	public function getBody()
	{
		// TODO: Implement getBody() method.
	}

	public function withBody(StreamInterface $body)
	{
		// TODO: Implement withBody() method.
	}

	public function getRequestTarget()
	{
		// TODO: Implement getRequestTarget() method.
	}

	public function withRequestTarget($requestTarget)
	{
		// TODO: Implement withRequestTarget() method.
	}

	public function getMethod()
	{
		// TODO: Implement getMethod() method.
	}

	public function withMethod($method)
	{
		// TODO: Implement withMethod() method.
	}

	public function getUri()
	{
		// TODO: Implement getUri() method.
	}

	public function withUri(UriInterface $uri, $preserveHost = false)
	{
		// TODO: Implement withUri() method.
	}

	public function getServerParams()
	{
		return $_REQUEST;
	}

	public function getCookieParams()
	{
		// TODO: Implement getCookieParams() method.
	}

	public function withCookieParams(array $cookies)
	{
		// TODO: Implement withCookieParams() method.
	}

	public function getQueryParams()
	{
		return $_REQUEST;
	}

	public function withQueryParams(array $query)
	{
		// TODO: Implement withQueryParams() method.
	}

	public function getUploadedFiles()
	{
		// TODO: Implement getUploadedFiles() method.
	}

	public function withUploadedFiles(array $uploadedFiles)
	{
		// TODO: Implement withUploadedFiles() method.
	}

	public function getParsedBody()
	{
		return json_decode($this->request->raw());
	}

	public function withParsedBody($data)
	{
		// TODO: Implement withParsedBody() method.
	}

	public function getAttributes()
	{
		return $this->attributes;
	}

	public function getAttribute($name, $default = null)
	{
		if(isset($this->attributes[$name]))
			return $this->attributes[$name];

		return null;
	}

	public function withAttribute($name, $value)
	{
		$this->attributes[$name] = $value;

		return $this;
	}

	public function withoutAttribute($name)
	{
		// TODO: Implement withoutAttribute() method.
	}
}