<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient;

use Psr\Http\Message\RequestInterface;

interface SecurityProviderInterface
{
	/**
	 * Modifies HTTP request to meet security requirements declared for operation
	 * @param RequestInterface $request
	 * @param string[] $requirements
	 * @return RequestInterface
	 */
	public function fulfillRequirements(RequestInterface $request, array $requirements): RequestInterface;
}
