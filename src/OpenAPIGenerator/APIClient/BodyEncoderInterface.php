<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient;

use Psr\Http\Message\StreamInterface;

interface BodyEncoderInterface
{
	/**
	 * Encodes specified arbitrary data into HTTP message body
	 * @param mixed $data
	 * @return StreamInterface
	 */
	public function encode($data): StreamInterface;
}
