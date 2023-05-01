<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\BodyCoder;

use InvalidArgumentException;
use OpenAPIGenerator\APIClient\BodyCoderInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use function json_decode;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;
use function sprintf;
use const JSON_ERROR_NONE;
use const JSON_OBJECT_AS_ARRAY;

class Json implements BodyCoderInterface
{
	protected StreamFactoryInterface $streamFactory;

	protected int $encodeFlags;

	protected int $decodeFlags;

	protected bool $decodeAsAssociativeArray;

	protected int $depth;
	/**
	 * @param StreamFactoryInterface $streamFactory
	 * @param int $encodeFlags
	 * @param int $decodeFlags
	 * @param int $depth
	 */
	public function __construct(StreamFactoryInterface $streamFactory, int $encodeFlags, int $decodeFlags, int $depth)
	{
		$this->streamFactory = $streamFactory;
		$this->encodeFlags = $encodeFlags;
		$this->decodeFlags = $decodeFlags;
		$this->decodeAsAssociativeArray = (bool)($this->decodeFlags & JSON_OBJECT_AS_ARRAY);
		$this->depth = $depth;
	}

	/**
	 * @inheritDoc
	 */
	public function encode($data): StreamInterface
	{
		$json = json_encode($data, $this->encodeFlags, $this->depth);
		if ($json === false)
		{
			throw new InvalidArgumentException(sprintf('JSON encoding failure. %s', json_last_error_msg()));
		}
		return $this->streamFactory->createStream($json);
	}

	/**
	 * @inheritDoc
	 */
	public function decode(StreamInterface $body)
	{
		$result = json_decode($body->getContents(), $this->decodeAsAssociativeArray, $this->depth, $this->decodeFlags);
		if (($result === null) && (json_last_error() !== JSON_ERROR_NONE))
		{
			throw new InvalidArgumentException(sprintf('JSON decoding failure. %s', json_last_error_msg()));
		}
		return $result;
	}
}
