<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\Exception;

use Exception;
use Throwable;

class UnsuccessfulResponse extends Exception
{
	/**
	 * @var mixed
	 */
	protected $responseContent;

	/**
	 * @var iterable<string, string[]>
	 */
	protected iterable $responseHeaders;

	/**
	 * @param mixed $responseContent
	 * @param iterable<string, string[]> $responseHeaders
	 * @param int $responseStatusCode
	 * @param string $responseReasonPhrase
	 * @param Throwable|null $previous
	 */
	public function __construct(
		$responseContent,
		iterable $responseHeaders,
		int $responseStatusCode,
		string $responseReasonPhrase,
		?Throwable $previous = null
	)
	{
		parent::__construct($responseReasonPhrase, $responseStatusCode, $previous);
		$this->responseContent = $responseContent;
		$this->responseHeaders = $responseHeaders;
	}

	/**
	 * @return mixed
	 */
	public function getResponseContent()
	{
		return $this->responseContent;
	}

	/**
	 * @return iterable<string, string[]>
	 */
	public function getResponseHeaders(): iterable
	{
		return $this->responseHeaders;
	}
}
