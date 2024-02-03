<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient;

use Articus\DataTransfer\Exception as DTException;
use Articus\DataTransfer\Service as DTService;
use Articus\DataTransfer\Strategy as DTStrategy;
use Articus\DataTransfer\Validator as DTValidator;
use Articus\PluginManager\PluginManagerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use function http_build_query;
use function is_object;
use function rawurlencode;
use function strtr;
use const PHP_QUERY_RFC3986;

abstract class AbstractApiClient
{
	public const SUBSET_PATH = 'path';
	public const SUBSET_QUERY = 'query';
	public const SUBSET_HEADER = 'header';
	public const SUBSET_COOKIE = 'cookie';

	protected string $serverUrl;

	protected DTService $dt;

	protected RequestFactoryInterface $requestFactory;

	protected ClientInterface $httpClient;

	/**
	 * @var PluginManagerInterface<SecurityProviderInterface>
	 */
	protected PluginManagerInterface $securityProviders;

	/**
	 * @var PluginManagerInterface<BodyEncoderInterface>
	 */
	protected PluginManagerInterface $bodyEncoders;

	/**
	 * @var PluginManagerInterface<BodyDecoderInterface>
	 */
	protected PluginManagerInterface $bodyDecoders;

	/**
	 * @var PluginManagerInterface<DTStrategy\StrategyInterface>
	 */
	protected PluginManagerInterface $contentStrategies;

	/**
	 * @var PluginManagerInterface<DTValidator\ValidatorInterface>
	 */
	protected PluginManagerInterface $contentValidators;


	public function __construct(
		string $serverUrl,
		DTService $dt,
		RequestFactoryInterface $requestFactory,
		ClientInterface $httpClient,
		PluginManagerInterface $securityProviders,
		PluginManagerInterface $bodyEncoders,
		PluginManagerInterface $bodyDecoders,
		PluginManagerInterface $contentStrategies,
		PluginManagerInterface $contentValidators
	)
	{
		$this->serverUrl = $serverUrl;
		$this->dt = $dt;
		$this->requestFactory = $requestFactory;
		$this->httpClient = $httpClient;
		$this->securityProviders = $securityProviders;
		$this->bodyEncoders = $bodyEncoders;
		$this->bodyDecoders = $bodyDecoders;
		$this->contentStrategies = $contentStrategies;
		$this->contentValidators = $contentValidators;
	}

	protected function getNoopStrategy(): DTStrategy\StrategyInterface
	{
		return ($this->contentStrategies)(DTStrategy\Whatever::class, []);
	}

	protected function getNoopValidator(): DTValidator\ValidatorInterface
	{
		return ($this->contentValidators)(DTValidator\Whatever::class, []);
	}

	/**
	 * @param string $method
	 * @param string $pathTemplate
	 * @param array<string, string> $pathParameters map <parameter name> -> <parameter value>
	 * @param array<string, mixed> $queryParameters map <parameter name> -> <parameter value>
	 * @return RequestInterface
	 */
	protected function createRequest(string $method, string $pathTemplate, array $pathParameters, array $queryParameters): RequestInterface
	{
		$pathTemplateReplacements = [];
		foreach ($pathParameters as $pathParameterName => $pathParameterValue)
		{
			$pathTemplateReplacements['{' . $pathParameterName . '}'] = rawurlencode($pathParameterValue);
		}
		$path = empty($pathTemplateReplacements) ? $pathTemplate : strtr($pathTemplate, $pathTemplateReplacements);
		$queryString = empty($queryParameters) ? '' : '?' . http_build_query($queryParameters, '', '&', PHP_QUERY_RFC3986);
		return $this->requestFactory->createRequest($method, $this->serverUrl . $path . $queryString);
	}

	/**
	 * @param object $parameters
	 * @return array<string, string> map <parameter name> -> <parameter value>
	 * @throws DTException\InvalidData
	 */
	protected function getPathParameters(object $parameters): array
	{
		return $this->dt->extractFromTypedData($parameters, self::SUBSET_PATH) ?? [];
	}

	/**
	 * @param object $parameters
	 * @return array<string, mixed> map <parameter name> -> <parameter value>
	 * @throws DTException\InvalidData
	 */
	protected function getQueryParameters(object $parameters): array
	{
		return $this->dt->extractFromTypedData($parameters, self::SUBSET_QUERY) ?? [];
	}

	/**
	 * @param RequestInterface $request
	 * @param object $parameters
	 * @return RequestInterface
	 * @throws DTException\InvalidData
	 */
	protected function addCustomHeaders(RequestInterface $request, object $parameters): RequestInterface
	{
		$headers = $this->dt->extractFromTypedData($parameters, self::SUBSET_HEADER) ?? [];
		foreach ($headers as $headerName => $headerValue)
		{
			if ($headerValue !== null)
			{
				$request = $request->withHeader($headerName, $headerValue);
			}
		}
		return $request;
	}

	/**
	 * @param RequestInterface $request
	 * @param object $parameters
	 * @return RequestInterface
	 * @throws DTException\InvalidData
	 */
	protected function addCookies(RequestInterface $request, object $parameters): RequestInterface
	{
		$cookies = $this->dt->extractFromTypedData($parameters, self::SUBSET_COOKIE) ?? [];
		if (!empty($cookies))
		{
			$request = $request->withHeader('Cookie', http_build_query($cookies, '', '; ', PHP_QUERY_RFC3986));
		}
		return $request;
	}

	/**
	 * @param RequestInterface $request
	 * @param string $mediaType
	 * @param mixed $content
	 * @param null|DTStrategy\StrategyInterface $contentStrategy
	 * @return RequestInterface
	 * @throws DTException\InvalidData
	 */
	protected function addBody(
		RequestInterface $request,
		string $mediaType,
		$content,
		?DTStrategy\StrategyInterface $contentStrategy = null
	): RequestInterface
	{
		$contentData = null;
		if ($contentStrategy !== null)
		{
			$noopStrategy = $this->getNoopStrategy();
			$noopValidator = $this->getNoopValidator();
			$violations = $this->dt->transfer($content, $contentStrategy, $contentData, $noopStrategy, $noopStrategy, $noopValidator, $noopStrategy);
			if (!empty($violations))
			{
				throw new DTException\InvalidData($violations);
			}
		}
		elseif (is_object($content))
		{
			$contentData = $this->dt->extractFromTypedData($content);
		}
		else
		{
			$contentData = $content;
		}

		$bodyEncoder = $this->getBodyEncoder($mediaType);
		return $request
			->withHeader('Content-Type', $mediaType)
			->withBody($bodyEncoder->encode($contentData))
		;
	}

	protected function getBodyEncoder(string $mediaType): BodyEncoderInterface
	{
		return ($this->bodyEncoders)($mediaType, []);
	}

	protected function addAcceptHeader(RequestInterface $request, string $mediaTypeRange): RequestInterface
	{
		return $request->withHeader('Accept', $mediaTypeRange);
	}

	/**
	 * @param RequestInterface $request
	 * @param iterable<string, string[]> $security
	 * @return RequestInterface
	 */
	protected function addSecurity(RequestInterface $request, iterable $security): RequestInterface
	{
		foreach ($security as $securitySchemaName => $securityRequirements)
		{
			$securityProvider = $this->getSecurityProvider($securitySchemaName);
			$request = $securityProvider->fulfillRequirements($request, $securityRequirements);
		}
		return $request;
	}

	protected function getSecurityProvider(string $securitySchemaName): SecurityProviderInterface
	{
		return ($this->securityProviders)($securitySchemaName, []);
	}

	/**
	 * @param ResponseInterface $response
	 * @param mixed $content
	 * @param null|DTStrategy\StrategyInterface $contentStrategy
	 * @param null|DTValidator\ValidatorInterface $contentValidator
	 * @return void
	 * @throws Exception\InvalidResponseBodySchema
	 */
	protected function parseBody(
		ResponseInterface $response,
		&$content,
		?DTStrategy\StrategyInterface $contentStrategy = null,
		?DTValidator\ValidatorInterface $contentValidator = null
	): void
	{
		$contentData = null;
		$mediaType = $response->getHeader('Content-Type')[0] ?? null;
		if ($mediaType !== null)
		{
			$bodyDecoder = $this->getBodyDecoder($mediaType);
			$contentData = $bodyDecoder->decode($response->getBody());
		}

		if (($contentStrategy !== null) && ($contentValidator !== null))
		{
			$noopStrategy = $this->getNoopStrategy();
			$violations = $this->dt->transfer($contentData, $noopStrategy, $content, $contentStrategy, $contentStrategy, $contentValidator, $contentStrategy);
			if (!empty($violations))
			{
				throw new Exception\InvalidResponseBodySchema($response, $violations);
			}
		}
		elseif (is_object($content))
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

	protected function getBodyDecoder(string $mediaType): BodyDecoderInterface
	{
		return ($this->bodyDecoders)($mediaType, []);
	}

	/**
	 * @param mixed $content
	 * @param iterable<string, string[]> $headers
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
