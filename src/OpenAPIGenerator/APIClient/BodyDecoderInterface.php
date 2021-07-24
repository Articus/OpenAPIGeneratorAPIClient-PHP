<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient;

use Psr\Http\Message\StreamInterface;

interface BodyDecoderInterface
{
	/**
	 * Decodes specified HTTP message body into arbitrary data
	 * @param StreamInterface $body
	 * @return mixed
	 */
	public function decode(StreamInterface $body);
}
