<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\BodyCoder\Options;

use Psr\Http\Message\StreamFactoryInterface;
use const JSON_OBJECT_AS_ARRAY;

class Json
{
	/**
	 * Service name for stream factory implementation inside container
	 * @var string
	 */
	public string $streamFactoryServiceName = StreamFactoryInterface::class;

	/**
	 * Flags for json_encode
	 * @var int
	 */
	public int $encodeFlags = 0;

	/**
	 * Flags for json_decode
	 * @var int
	 */
	public int $decodeFlags = JSON_OBJECT_AS_ARRAY;

	/**
	 * Depth for json_encode and json_decode
	 * @var int
	 */
	public int $depth = 512;

	public function __construct(iterable $options)
	{
		foreach ($options as $key => $value)
		{
			switch ($key)
			{
				case 'streamFactoryServiceName':
				case 'stream_factory_service_name':
					$this->streamFactoryServiceName = $value;
					break;
				case 'encodeFlags':
				case 'encode_flags':
					$this->encodeFlags = $value;
					break;
				case 'decodeFlags':
				case 'decode_flags':
					$this->decodeFlags = $value;
					break;
				case 'depth':
					$this->depth = $value;
					break;
			}
		}
	}
}
