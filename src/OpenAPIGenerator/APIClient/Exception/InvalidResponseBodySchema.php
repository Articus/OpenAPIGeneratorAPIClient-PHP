<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\Exception;

use Psr\Http\Message\ResponseInterface;

class InvalidResponseBodySchema extends \Exception
{
	/**
	 * @var ResponseInterface
	 */
	protected $response;

	/**
	 * @var array
	 */
	protected $violations;

	/**
	 * @param ResponseInterface $response
	 * @param array $violations
	 */
	public function __construct(ResponseInterface $response, array $violations = [], \Throwable $previous = null)
	{
		parent::__construct('Invalid response body schema', 0, $previous);
		$this->response = $response;
		$this->violations = $violations;
	}

	/**
	 * @return ResponseInterface
	 */
	public function getResponse(): ResponseInterface
	{
		return $this->response;
	}

	/**
	 * @return array
	 */
	public function getViolations(): array
	{
		return $this->violations;
	}
}
