<?php
declare(strict_types=1);

namespace spec\Example;

use Articus\DataTransfer\Strategy\StrategyInterface as DTStrategy;
use Articus\DataTransfer\Validator\ValidatorInterface as DTValidator;
use OpenAPIGenerator\APIClient as OAGAC;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class DummyApiClient extends OAGAC\AbstractApiClient
{
	public function createRequest(string $method, string $pathTemplate, array $pathParameters, array $queryParameters): RequestInterface
	{
		return parent::createRequest($method, $pathTemplate, $pathParameters, $queryParameters);
	}

	public function getPathParameters($parameters): array
	{
		return parent::getPathParameters($parameters);
	}

	public function getQueryParameters($parameters): array
	{
		return parent::getQueryParameters($parameters);
	}

	public function addCustomHeaders(RequestInterface $request, $parameters): RequestInterface
	{
		return parent::addCustomHeaders($request, $parameters);
	}

	public function addCookies(RequestInterface $request, $parameters): RequestInterface
	{
		return parent::addCookies($request, $parameters);
	}

	public function addBody(RequestInterface $request, string $mediaType, $content, ?DTStrategy $contentStrategy = null): RequestInterface
	{
		return parent::addBody($request, $mediaType, $content, $contentStrategy);
	}

	public function addAcceptHeader(RequestInterface $request, string $mediaTypeRange): RequestInterface
	{
		return parent::addAcceptHeader($request, $mediaTypeRange);
	}

	public function addSecurity(RequestInterface $request, iterable $security): RequestInterface
	{
		return parent::addSecurity($request, $security);
	}

	public function parseBody(ResponseInterface $response, &$content, ?DTStrategy $contentStrategy = null, ?DTValidator $contentValidator = null): void
	{
		parent::parseBody($response, $content, $contentStrategy, $contentValidator);
	}

	public function getSuccessfulContent($content, iterable $headers, int $statusCode, string $reasonPhrase)
	{
		return parent::getSuccessfulContent($content, $headers, $statusCode, $reasonPhrase);
	}
}
