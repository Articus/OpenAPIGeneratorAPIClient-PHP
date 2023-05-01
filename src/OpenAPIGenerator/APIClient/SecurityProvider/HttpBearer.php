<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\SecurityProvider;

use InvalidArgumentException;
use LogicException;
use OpenAPIGenerator\APIClient\SecurityProviderInterface;
use Psr\Http\Message\RequestInterface;
use function preg_match;

class HttpBearer implements SecurityProviderInterface
{
	protected string $token = '';

	/**
	 * @return string
	 */
	public function getToken(): string
	{
		return $this->token;
	}

	/**
	 * @param string $token
	 * @return self
	 */
	public function setToken(string $token): self
	{
		if (preg_match('#^[[:alnum:]\-\._~\+/]+=*$#', $token) !== 1)
		{
			throw new InvalidArgumentException('Invalid bearer token for HTTP authentication');
		}
		$this->token = $token;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function fulfillRequirements(RequestInterface $request, array $requirements): RequestInterface
	{
		if (empty($this->token))
		{
			throw new LogicException('Bearer token should be set before using security provider');
		}
		return $request->withHeader('Authorization', 'Bearer ' . $this->token);
	}
}
