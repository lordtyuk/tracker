<?php

namespace Tracker;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class FakeBody implements StreamInterface
{
	protected $body;

	public function __toString()
	{
		// TODO: Implement __toString() method.
	}

	public function close()
	{
		// TODO: Implement close() method.
	}

	public function detach()
	{
		// TODO: Implement detach() method.
	}

	public function getSize()
	{
		// TODO: Implement getSize() method.
	}

	public function tell()
	{
		// TODO: Implement tell() method.
	}

	public function eof()
	{
		// TODO: Implement eof() method.
	}

	public function isSeekable()
	{
		// TODO: Implement isSeekable() method.
	}

	public function seek($offset, $whence = SEEK_SET)
	{
		// TODO: Implement seek() method.
	}

	public function rewind()
	{
		// TODO: Implement rewind() method.
	}

	public function isWritable()
	{
		// TODO: Implement isWritable() method.
	}

	public function write($string)
	{
		$this->body = json_decode($string, true);
	}

	public function isReadable()
	{
		// TODO: Implement isReadable() method.
	}

	public function read($length)
	{
		// TODO: Implement read() method.
	}

	public function getContents()
	{
		return $this->body;
	}

	public function getMetadata($key = null)
	{
		// TODO: Implement getMetadata() method.
	}

}

class HttpServerResponse implements ResponseInterface
{

	protected $body;

	public function __construct()
	{
		$this->body = new FakeBody();
	}

	public function getProtocolVersion()
	{
		return $this;
	}

	public function withProtocolVersion($version)
	{
		return $this;
	}

	public function getHeaders()
	{
		return $this;
	}

	public function hasHeader($name)
	{
		return $this;
	}

	public function getHeader($name)
	{
		return $this;
	}

	public function getHeaderLine($name)
	{
		return $this;
	}

	public function withHeader($name, $value)
	{
		return $this;
	}

	public function withAddedHeader($name, $value)
	{
		return $this;
	}

	public function withoutHeader($name)
	{
		return $this;
	}

	public function getBody()
	{
		return $this->body;
	}

	public function withBody(StreamInterface $body)
	{
		return $this;
	}

	public function getStatusCode()
	{
		return $this;
	}

	public function withStatus($code, $reasonPhrase = '')
	{
		return $this;
	}

	public function getReasonPhrase()
	{
		return $this;
	}
}