<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient;

use Articus\DataTransfer\Exception as DTException;
use Articus\DataTransfer\Service as DTService;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractApiClient
{
	public const SUBSET_PATH = 'path';
	public const SUBSET_QUERY = 'query';
	public const SUBSET_HEADER = 'header';
	public const SUBSET_COOKIE = 'cookie';

	/**
	 * @var string
	 */
	protected $serverUrl;

	/**
	 * @var DTService
	 */
	protected $dt;

	/**
	 * @var RequestFactoryInterface
	 */
	protected $requestFactory;

	/**
	 * @var ClientInterface
	 */
	protected $httpClient;

	/**
	 * @var ContainerInterface
	 */
	protected $securityProviderFactory;

	/**
	 * @var ContainerInterface
	 */
	protected $bodyEncoderFactory;

	/**
	 * @var ContainerInterface
	 */
	protected $bodyDecoderFactory;

	/**
	 * @param string $serverUrl
	 * @param DTService $dt
	 * @param RequestFactoryInterface $requestFactory
	 * @param ClientInterface $httpClient
	 * @param ContainerInterface $securityProviderFactory
	 * @param ContainerInterface $bodyEncoderFactory
	 * @param ContainerInterface $bodyDecoderFactory
	 */
	public function __construct(
		string $serverUrl,
		DTService $dt,
		RequestFactoryInterface $requestFactory,
		ClientInterface $httpClient,
		ContainerInterface $securityProviderFactory,
		ContainerInterface $bodyEncoderFactory,
		ContainerInterface $bodyDecoderFactory
	)
	{
		$this->serverUrl = $serverUrl;
		$this->dt = $dt;
		$this->requestFactory = $requestFactory;
		$this->httpClient = $httpClient;
		$this->securityProviderFactory = $securityProviderFactory;
		$this->bodyEncoderFactory = $bodyEncoderFactory;
		$this->bodyDecoderFactory = $bodyDecoderFactory;
	}

	/**
	 * @param string $method
	 * @param string $pathTemplate
	 * @param array $pathParameters
	 * @param array $queryParameters
	 * @return RequestInterface
	 */
	protected function createRequest(string $method, string $pathTemplate, array $pathParameters, array $queryParameters): RequestInterface
	{
		$pathTemplateReplacements = [];
		foreach ($pathParameters as $pathParameterName => $pathParameterValue)
		{
			$pathTemplateReplacements['{' . $pathParameterName . '}'] = \rawurlencode($pathParameterValue);
		}
		$path = empty($pathTemplateReplacements) ? $pathTemplate : \strtr($pathTemplate, $pathTemplateReplacements);
		$queryString = empty($queryParameters) ? '' : '?' . \http_build_query($queryParameters, '', '&', \PHP_QUERY_RFC3986);
		return $this->requestFactory->createRequest($method, $this->serverUrl . $path . $queryString);
	}

	/**
	 * @param object $parameters
	 * @return array
	 * @throws DTException\InvalidData
	 */
	protected function getPathParameters($parameters): array
	{
		return $this->dt->extractFromTypedData($parameters, self::SUBSET_PATH) ?? [];
	}

	/**
	 * @param object $parameters
	 * @return array
	 * @throws DTException\InvalidData
	 */
	protected function getQueryParameters($parameters): array
	{
		return $this->dt->extractFromTypedData($parameters, self::SUBSET_QUERY) ?? [];
	}

	/**
	 * @param RequestInterface $request
	 * @param object $parameters
	 * @return RequestInterface
	 * @throws DTException\InvalidData
	 */
	protected function addCustomHeaders(RequestInterface $request, $parameters): RequestInterface
	{
		$headers = $this->dt->extractFromTypedData($parameters, self::SUBSET_HEADER) ?? [];
		foreach ($headers as $headerName => $headerValue)
		{
			$request = $request->withHeader($headerName, $headerValue);
		}
		return $request;
	}

	/**
	 * @param RequestInterface $request
	 * @param object $parameters
	 * @return RequestInterface
	 * @throws DTException\InvalidData
	 */
	protected function addCookies(RequestInterface $request, $parameters): RequestInterface
	{
		$cookies = $this->dt->extractFromTypedData($parameters, self::SUBSET_COOKIE) ?? [];
		if (!empty($cookies))
		{
			$request = $request->withHeader('Cookie', \http_build_query($cookies, '', '; ', \PHP_QUERY_RFC3986));
		}
		return $request;
	}

	/**
	 * @param RequestInterface $request
	 * @param string $mediaType
	 * @param mixed $content
	 * @return RequestInterface
	 * @throws DTException\InvalidData
	 */
	protected function addBody(RequestInterface $request, string $mediaType, $content): RequestInterface
	{
		$contentData = \is_object($content) ? $this->dt->extractFromTypedData($content) : $content;
		$bodyEncoder = $this->getBodyEncoder($mediaType);
		return $request
			->withHeader('Content-Type', $mediaType)
			->withBody($bodyEncoder->encode($contentData))
		;
	}

	/**
	 * @param string $mediaType
	 * @return BodyEncoderInterface
	 */
	protected function getBodyEncoder(string $mediaType): BodyEncoderInterface
	{
		return $this->bodyEncoderFactory->get($mediaType);
	}

	/**
	 * @param RequestInterface $request
	 * @param string $mediaTypeRange
	 * @return RequestInterface
	 */
	protected function addAcceptHeader(RequestInterface $request, string $mediaTypeRange): RequestInterface
	{
		return $request->withHeader('Accept', $mediaTypeRange);
	}

	/**
	 * @param RequestInterface $request
	 * @param iterable $security
	 * @return RequestInterface
	 */
	protected function addSecurity(RequestInterface $request, iterable $security): RequestInterface
	{
		foreach ($security as $securitySchemaName => $securityRequirements)
		{
			$securityProvider = $this->getSecurityProvider($securitySchemaName);
			$request = $securityProvider->fulfillRequirements($request, $securityRequirements);
		}
		return  $request;
	}

	/**
	 * @param string $securitySchemaName
	 * @return SecurityProviderInterface
	 */
	protected function getSecurityProvider(string $securitySchemaName): SecurityProviderInterface
	{
		return $this->securityProviderFactory->get($securitySchemaName);
	}

	/**
	 * @param ResponseInterface $response
	 * @param mixed $content
	 * @throws Exception\InvalidResponseBodySchema
	 */
	protected function parseBody(ResponseInterface $response, &$content): void
	{
		$contentData = null;
		$mediaType = $response->getHeader('Content-Type')[0] ?? null;
		if ($mediaType !== null)
		{
			$bodyDecoder = $this->getBodyDecoder($mediaType);
			$contentData = $bodyDecoder->decode($response->getBody());
		}
		if (\is_object($content))
		{
			$violations = $this->dt->transferToTypedData($contentData, $content);
			if (!empty($violations))
			{
				throw new Exception\InvalidResponseBodySchema($response, $violations);
			}
		}
		else
		{
			$content = $contentData;
		}
	}

	/**
	 * @param string $mediaType
	 * @return BodyDecoderInterface
	 */
	protected function getBodyDecoder(string $mediaType): BodyDecoderInterface
	{
		return $this->bodyDecoderFactory->get($mediaType);
	}

	/**
	 * @param $content
	 * @param iterable $headers
	 * @param int $statusCode
	 * @param string $reasonPhrase
	 * @return mixed
	 * @throws Exception\UnsuccessfulResponse
	 */
	protected function getSuccessfulContent($content, iterable $headers, int $statusCode, string $reasonPhrase)
	{
		if (($statusCode < 200) || ($statusCode > 299))
		{
			throw new Exception\UnsuccessfulResponse($content, $headers, $statusCode, $reasonPhrase);
		}
		return $content;
	}
}
