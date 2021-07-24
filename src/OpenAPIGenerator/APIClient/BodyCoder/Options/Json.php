<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\BodyCoder\Options;

use Psr\Http\Message\StreamFactoryInterface;

class Json
{
	/**
	 * Service name for stream factory implementation inside container
	 * @var string
	 */
	public $streamFactoryServiceName = StreamFactoryInterface::class;

	/**
	 * Flags for json_encode
	 * @var int
	 */
	public $encodeFlags = 0;

	/**
	 * Flags for json_decode
	 * @var int
	 */
	public $decodeFlags = \JSON_OBJECT_AS_ARRAY;

	/**
	 * Depth for json_encode and json_decode
	 * @var int
	 */
	public $depth = 512;

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
