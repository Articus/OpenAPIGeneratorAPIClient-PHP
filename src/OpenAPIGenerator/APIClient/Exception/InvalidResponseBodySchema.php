<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class InvalidResponseBodySchema extends Exception
{
	protected ResponseInterface $response;

	protected array $violations;

	public function __construct(ResponseInterface $response, array $violations = [], ?Throwable $previous = null)
	{
		parent::__construct('Invalid response body schema', 0, $previous);
		$this->response = $response;
		$this->violations = $violations;
	}

	public function getResponse(): ResponseInterface
	{
		return $this->response;
	}

	public function getViolations(): array
	{
		return $this->violations;
	}
}
