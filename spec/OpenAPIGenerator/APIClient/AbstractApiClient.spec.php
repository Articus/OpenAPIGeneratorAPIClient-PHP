<?php
declare(strict_types=1);

use Articus\DataTransfer\Exception as DTException;
use Articus\DataTransfer\Service as DTService;
use Articus\DataTransfer\Strategy as DTStrategy;
use Articus\DataTransfer\Validator as DTValidator;
use Articus\PluginManager\PluginManagerInterface;
use OpenAPIGenerator\APIClient as OAGAC;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use spec\Example;

describe(OAGAC\AbstractApiClient::class, function ()
{
	afterEach(function ()
	{
		Mockery::close();
	});
	context('->createRequest', function ()
	{
		it('creates request without parameters', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$request = mock(RequestInterface::class);
			$method = 'TEST_METHOD';
			$path = '/test/path';
			$requestFactory->shouldReceive('createRequest')->with($method, $url . $path)->andReturn($request)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->createRequest($method, $path, [], []))->toBe($request);
		});
		it('creates request with path parameters', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$request = mock(RequestInterface::class);
			$method = 'TEST_METHOD';
			$pathTemplate = '/test/path/{param}/and/{param with &}';
			$pathParameters = ['param' => 'abc123', 'param with &' => 'value with ='];
			$path = '/test/path/abc123/and/value%20with%20%3D';
			$requestFactory->shouldReceive('createRequest')->with($method, $url . $path)->andReturn($request)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->createRequest($method, $pathTemplate, $pathParameters, []))->toBe($request);
		});
		it('creates request with query parameters', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$request = mock(RequestInterface::class);
			$method = 'TEST_METHOD';
			$path = '/test/path';
			$queryParameters = ['param' => 'abc123', 'param with &' => 'value with ='];
			$queryString = '?param=abc123&param%20with%20%26=value%20with%20%3D';
			$requestFactory->shouldReceive('createRequest')->with($method, $url . $path . $queryString)->andReturn($request)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->createRequest($method, $path, [], $queryParameters))->toBe($request);
		});
	});
	context('->getPathParameters', function ()
	{
		it('extracts path parameters from object', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$parameterObject = mock();
			$parameterArray = ['abc' => '123', 'def' => '456'];
			$dt->shouldReceive('extractFromTypedData')->with($parameterObject, OAGAC\AbstractApiClient::SUBSET_PATH)->andReturn($parameterArray)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->getPathParameters($parameterObject))->toBe($parameterArray);
		});
	});
	context('->getQueryParameters', function ()
	{
		it('extracts query parameters from object', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$parameterObject = mock();
			$parameterArray = ['abc' => '123', 'def' => '456'];
			$dt->shouldReceive('extractFromTypedData')->with($parameterObject, OAGAC\AbstractApiClient::SUBSET_QUERY)->andReturn($parameterArray)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->getQueryParameters($parameterObject))->toBe($parameterArray);
		});
	});
	context('->addCustomHeaders', function ()
	{
		it('does not add custom header to request if header value is null', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$request = mock(RequestInterface::class);
			$parameterObject = mock();
			$headers = ['header' => null];
			$dt->shouldReceive('extractFromTypedData')->with($parameterObject, OAGAC\AbstractApiClient::SUBSET_HEADER)->andReturn($headers)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->addCustomHeaders($request, $parameterObject))->toBe($request);
		});
		it('adds custom headers to request', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$request0 = mock(RequestInterface::class);
			$request1 = mock(RequestInterface::class);
			$request2 = mock(RequestInterface::class);
			$parameterObject = mock();
			$headerName1 = 'header1';
			$headerName2 = 'header2';
			$headers = [$headerName1 => 'value1', $headerName2 => 'value2'];
			$dt->shouldReceive('extractFromTypedData')->with($parameterObject, OAGAC\AbstractApiClient::SUBSET_HEADER)->andReturn($headers)->once();
			$request0->shouldReceive('withHeader')->with($headerName1, $headers[$headerName1])->andReturn($request1)->once();
			$request1->shouldReceive('withHeader')->with($headerName2, $headers[$headerName2])->andReturn($request2)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->addCustomHeaders($request0, $parameterObject))->toBe($request2);
		});
	});
	context('->addCookies', function ()
	{
		it('does not add cookie header to request if there is no custom cookies', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$request = mock(RequestInterface::class);
			$parameterObject = mock();
			$dt->shouldReceive('extractFromTypedData')->with($parameterObject, OAGAC\AbstractApiClient::SUBSET_COOKIE)->andReturn([])->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->addCookies($request, $parameterObject))->toBe($request);
		});
		it('adds cookie header with custom cookies to request', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$request0 = mock(RequestInterface::class);
			$request1 = mock(RequestInterface::class);
			$parameterObject = mock();
			$cookies = ['cookie' => 'abc123', 'cookie with ;' => 'value with ='];
			$cookieHeader = 'cookie=abc123; cookie%20with%20%3B=value%20with%20%3D';
			$dt->shouldReceive('extractFromTypedData')->with($parameterObject, OAGAC\AbstractApiClient::SUBSET_COOKIE)->andReturn($cookies)->once();
			$request0->shouldReceive('withHeader')->with('Cookie', $cookieHeader)->andReturn($request1)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->addCookies($request0, $parameterObject))->toBe($request1);
		});
	});
	context('->addBody', function ()
	{
		it('encodes non-object data and writes it to request body', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$request0 = mock(RequestInterface::class);
			$request1 = mock(RequestInterface::class);
			$request2 = mock(RequestInterface::class);
			$mediaType = 'media/test';
			$encoder = mock(OAGAC\BodyEncoderInterface::class);
			$data = ['test' => 123];
			$body = mock(StreamInterface::class);
			$bodyEncoderFactory->shouldReceive('__invoke')->with($mediaType, [])->andReturn($encoder)->once();
			$encoder->shouldReceive('encode')->with($data)->andReturn($body)->once();
			$request0->shouldReceive('withHeader')->with('Content-Type', $mediaType)->andReturn($request1)->once();
			$request1->shouldReceive('withBody')->with($body)->andReturn($request2)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->addBody($request0, $mediaType, $data))->toBe($request2);
		});
		it('extracts object data, encodes it and writes it to request body', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$request0 = mock(RequestInterface::class);
			$request1 = mock(RequestInterface::class);
			$request2 = mock(RequestInterface::class);
			$mediaType = 'media/test';
			$object = mock();
			$encoder = mock(OAGAC\BodyEncoderInterface::class);
			$data = ['test' => 123];
			$body = mock(StreamInterface::class);
			$dt->shouldReceive('extractFromTypedData')->with($object)->andReturn($data)->once();
			$bodyEncoderFactory->shouldReceive('__invoke')->with($mediaType, [])->andReturn($encoder)->once();
			$encoder->shouldReceive('encode')->with($data)->andReturn($body)->once();
			$request0->shouldReceive('withHeader')->with('Content-Type', $mediaType)->andReturn($request1)->once();
			$request1->shouldReceive('withBody')->with($body)->andReturn($request2)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->addBody($request0, $mediaType, $object))->toBe($request2);
		});
		it('extracts data with specified strategy, encodes it and writes it to request body', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$request0 = mock(RequestInterface::class);
			$request1 = mock(RequestInterface::class);
			$request2 = mock(RequestInterface::class);
			$mediaType = 'media/test';
			$object = mock();
			$contentStrategy = mock(DTStrategy\StrategyInterface::class);
			$noopStrategy = mock(DTStrategy\StrategyInterface::class);
			$noopValidator = mock(DTValidator\ValidatorInterface::class);
			$encoder = mock(OAGAC\BodyEncoderInterface::class);
			$data = ['test' => 123];
			$body = mock(StreamInterface::class);
			$contentStrategies->shouldReceive('__invoke')->with(DTStrategy\Whatever::class, [])->andReturn($noopStrategy)->once();
			$contentValidators->shouldReceive('__invoke')->with(DTValidator\Whatever::class, [])->andReturn($noopValidator)->once();
			$dt->shouldReceive('transfer')->withArgs(
				function ($from, $fromExtractor, &$to, $toExtractor, $toMerger, $toValidator, $toHydrator) use (&$object, &$data, &$contentStrategy, &$noopStrategy, &$noopValidator)
				{
					$result = ($from === $object)
						&& ($to === null)
						&& ($fromExtractor === $contentStrategy)
						&& ($toExtractor === $noopStrategy)
						&& ($toMerger === $noopStrategy)
						&& ($toValidator === $noopValidator)
						&& ($toHydrator === $noopStrategy)
					;
					if ($result)
					{
						$to = $data;
					}
					return $result;
				}
			)->andReturn([])->once();
			$bodyEncoderFactory->shouldReceive('__invoke')->with($mediaType, [])->andReturn($encoder)->once();
			$encoder->shouldReceive('encode')->with($data)->andReturn($body)->once();
			$request0->shouldReceive('withHeader')->with('Content-Type', $mediaType)->andReturn($request1)->once();
			$request1->shouldReceive('withBody')->with($body)->andReturn($request2)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->addBody($request0, $mediaType, $object, $contentStrategy))->toBe($request2);
		});
		it('throws if specified strategy fails to extract data', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$request = mock(RequestInterface::class);
			$mediaType = 'media/test';
			$object = mock();
			$contentStrategy = mock(DTStrategy\StrategyInterface::class);
			$noopStrategy = mock(DTStrategy\StrategyInterface::class);
			$noopValidator = mock(DTValidator\ValidatorInterface::class);
			$violations = ['test' => 123];
			$contentStrategies->shouldReceive('__invoke')->with(DTStrategy\Whatever::class, [])->andReturn($noopStrategy)->once();
			$contentValidators->shouldReceive('__invoke')->with(DTValidator\Whatever::class, [])->andReturn($noopValidator)->once();
			$dt->shouldReceive('transfer')->with($object, $contentStrategy, null, $noopStrategy, $noopStrategy, $noopValidator, $noopStrategy)->andReturn($violations)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			try
			{
				$client->addBody($request, $mediaType, $object, $contentStrategy);
				throw new LogicException('No expected exception');
			}
			catch (DTException\InvalidData $e)
			{
				expect($e->getViolations())->toBe($violations);
			}
		});
	});
	context('->addAcceptHeader', function ()
	{
		it('adds accept header to request', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$request0 = mock(RequestInterface::class);
			$request1 = mock(RequestInterface::class);
			$mediaType = 'media/test';
			$request0->shouldReceive('withHeader')->with('Accept', $mediaType)->andReturn($request1)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->addAcceptHeader($request0, $mediaType))->toBe($request1);
		});
	});
	context('->addSecurity', function ()
	{
		it('applies security providers to request', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$request0 = mock(RequestInterface::class);
			$request1 = mock(RequestInterface::class);
			$request2 = mock(RequestInterface::class);
			$securityName1 = 'security1';
			$securityName2 = 'security2';
			$security = [$securityName1 => ['abc' => 123, 'def' => 456], $securityName2 => []];
			$provider1 = mock(OAGAC\SecurityProviderInterface::class);
			$provider2 = mock(OAGAC\SecurityProviderInterface::class);
			$securityProviderFactory->shouldReceive('__invoke')->with($securityName1, [])->andReturn($provider1)->once();
			$securityProviderFactory->shouldReceive('__invoke')->with($securityName2, [])->andReturn($provider2)->once();
			$provider1->shouldReceive('fulfillRequirements')->with($request0, $security[$securityName1])->andReturn($request1)->once();
			$provider2->shouldReceive('fulfillRequirements')->with($request1, $security[$securityName2])->andReturn($request2)->once();

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->addSecurity($request0, $security))->toBe($request2);
		});
	});
	context('->parseBody', function ()
	{
		context('response has no content type', function ()
		{
			it('writes null to content if content is non-object', function ()
			{
				$url = 'http://test.url:1234';
				$dt = mock(DTService::class);
				$requestFactory = mock(RequestFactoryInterface::class);
				$httpClient = mock(ClientInterface::class);
				$securityProviderFactory = mock(PluginManagerInterface::class);
				$bodyEncoderFactory = mock(PluginManagerInterface::class);
				$bodyDecoderFactory = mock(PluginManagerInterface::class);
				$contentStrategies = mock(PluginManagerInterface::class);
				$contentValidators = mock(PluginManagerInterface::class);

				$response = mock(ResponseInterface::class);
				$content = ['test' => 123];
				$response->shouldReceive('getHeader')->with('Content-Type')->andReturn([])->once();

				$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
				$client->parseBody($response, $content);
				expect($content)->toBeNull();
			});
			it('transfers null to content if content is object', function ()
			{
				$url = 'http://test.url:1234';
				$dt = mock(DTService::class);
				$requestFactory = mock(RequestFactoryInterface::class);
				$httpClient = mock(ClientInterface::class);
				$securityProviderFactory = mock(PluginManagerInterface::class);
				$bodyEncoderFactory = mock(PluginManagerInterface::class);
				$bodyDecoderFactory = mock(PluginManagerInterface::class);
				$contentStrategies = mock(PluginManagerInterface::class);
				$contentValidators = mock(PluginManagerInterface::class);

				$response = mock(ResponseInterface::class);
				$content0 = mock();
				$content1 = mock();
				$response->shouldReceive('getHeader')->with('Content-Type')->andReturn([])->once();
				$dt->shouldReceive('transferToTypedData')->withArgs(
					function ($a, &$b) use (&$content0, &$content1)
					{
						$result = ($a === null) && ($b === $content0);
						if ($result)
						{
							$b = $content1;
						}
						return $result;
					}
				)->andReturn([])->once();

				$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
				$client->parseBody($response, $content0);
				expect($content0)->toBe($content1);
			});
			it('throws if transfer null to content fails', function ()
			{
				$url = 'http://test.url:1234';
				$dt = mock(DTService::class);
				$requestFactory = mock(RequestFactoryInterface::class);
				$httpClient = mock(ClientInterface::class);
				$securityProviderFactory = mock(PluginManagerInterface::class);
				$bodyEncoderFactory = mock(PluginManagerInterface::class);
				$bodyDecoderFactory = mock(PluginManagerInterface::class);
				$contentStrategies = mock(PluginManagerInterface::class);
				$contentValidators = mock(PluginManagerInterface::class);

				$response = mock(ResponseInterface::class);
				$content = mock();
				$violations = ['test' => 123];
				$response->shouldReceive('getHeader')->with('Content-Type')->andReturn([])->once();
				$dt->shouldReceive('transferToTypedData')->with(null, $content)->andReturn($violations)->once();

				$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
				try
				{
					$client->parseBody($response, $content);
					throw new LogicException('No expected exception');
				}
				catch (OAGAC\Exception\InvalidResponseBodySchema $e)
				{
					expect($e->getResponse())->toBe($response);
					expect($e->getViolations())->toBe($violations);
				}
			});
			it('transfers null to content with specified strategy and validator', function ()
			{
				$url = 'http://test.url:1234';
				$dt = mock(DTService::class);
				$requestFactory = mock(RequestFactoryInterface::class);
				$httpClient = mock(ClientInterface::class);
				$securityProviderFactory = mock(PluginManagerInterface::class);
				$bodyEncoderFactory = mock(PluginManagerInterface::class);
				$bodyDecoderFactory = mock(PluginManagerInterface::class);
				$contentStrategies = mock(PluginManagerInterface::class);
				$contentValidators = mock(PluginManagerInterface::class);

				$response = mock(ResponseInterface::class);
				$content0 = mock();
				$content1 = mock();
				$contentStrategy = mock(DTStrategy\StrategyInterface::class);
				$contentValidator = mock(DTValidator\ValidatorInterface::class);
				$noopStrategy = mock(DTStrategy\StrategyInterface::class);
				$response->shouldReceive('getHeader')->with('Content-Type')->andReturn([])->once();
				$contentStrategies->shouldReceive('__invoke')->with(DTStrategy\Whatever::class, [])->andReturn($noopStrategy)->once();
				$dt->shouldReceive('transfer')->withArgs(
					function ($from, $fromExtractor, &$to, $toExtractor, $toMerger, $toValidator, $toHydrator) use (&$content0, &$content1, &$contentStrategy, &$contentValidator, &$noopStrategy)
					{
						$result = ($from === null)
							&& ($to === $content0)
							&& ($fromExtractor === $noopStrategy)
							&& ($toExtractor === $contentStrategy)
							&& ($toMerger === $contentStrategy)
							&& ($toValidator === $contentValidator)
							&& ($toHydrator === $contentStrategy)
						;
						if ($result)
						{
							$to = $content1;
						}
						return $result;
					}
				)->andReturn([])->once();

				$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
				$client->parseBody($response, $content0, $contentStrategy, $contentValidator);
				expect($content0)->toBe($content1);
			});
			it('throws if specified strategy and validator fail to transfer null to content', function ()
			{
				$url = 'http://test.url:1234';
				$dt = mock(DTService::class);
				$requestFactory = mock(RequestFactoryInterface::class);
				$httpClient = mock(ClientInterface::class);
				$securityProviderFactory = mock(PluginManagerInterface::class);
				$bodyEncoderFactory = mock(PluginManagerInterface::class);
				$bodyDecoderFactory = mock(PluginManagerInterface::class);
				$contentStrategies = mock(PluginManagerInterface::class);
				$contentValidators = mock(PluginManagerInterface::class);

				$response = mock(ResponseInterface::class);
				$content = mock();
				$contentStrategy = mock(DTStrategy\StrategyInterface::class);
				$contentValidator = mock(DTValidator\ValidatorInterface::class);
				$noopStrategy = mock(DTStrategy\StrategyInterface::class);
				$violations = ['test' => 123];
				$response->shouldReceive('getHeader')->with('Content-Type')->andReturn([])->once();
				$contentStrategies->shouldReceive('__invoke')->with(DTStrategy\Whatever::class, [])->andReturn($noopStrategy)->once();
				$dt->shouldReceive('transfer')->with(null, $noopStrategy, $content, $contentStrategy, $contentStrategy, $contentValidator, $contentStrategy)->andReturn($violations)->once();

				$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
				try
				{
					$client->parseBody($response, $content, $contentStrategy, $contentValidator);
					throw new LogicException('No expected exception');
				}
				catch (OAGAC\Exception\InvalidResponseBodySchema $e)
				{
					expect($e->getResponse())->toBe($response);
					expect($e->getViolations())->toBe($violations);
				}
			});
		});
		context('response has content type', function ()
		{
			it('decodes response body and writes it to content if content is non-object', function ()
			{
				$url = 'http://test.url:1234';
				$dt = mock(DTService::class);
				$requestFactory = mock(RequestFactoryInterface::class);
				$httpClient = mock(ClientInterface::class);
				$securityProviderFactory = mock(PluginManagerInterface::class);
				$bodyEncoderFactory = mock(PluginManagerInterface::class);
				$bodyDecoderFactory = mock(PluginManagerInterface::class);
				$contentStrategies = mock(PluginManagerInterface::class);
				$contentValidators = mock(PluginManagerInterface::class);

				$response = mock(ResponseInterface::class);
				$content0 = ['abc' => 123];
				$content1 = ['def' => 456];
				$mediaType = 'media/test';
				$decoder = mock(OAGAC\BodyDecoderInterface::class);
				$body = mock(StreamInterface::class);
				$response->shouldReceive('getHeader')->with('Content-Type')->andReturn([$mediaType])->once();
				$response->shouldReceive('getBody')->andReturn($body)->once();
				$bodyDecoderFactory->shouldReceive('__invoke')->with($mediaType, [])->andReturn($decoder)->once();
				$decoder->shouldReceive('decode')->with($body)->andReturn($content1)->once();

				$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
				$client->parseBody($response, $content0);
				expect($content0)->toBe($content1);
			});
			it('decodes response body and transfers it to content if content is object', function ()
			{
				$url = 'http://test.url:1234';
				$dt = mock(DTService::class);
				$requestFactory = mock(RequestFactoryInterface::class);
				$httpClient = mock(ClientInterface::class);
				$securityProviderFactory = mock(PluginManagerInterface::class);
				$bodyEncoderFactory = mock(PluginManagerInterface::class);
				$bodyDecoderFactory = mock(PluginManagerInterface::class);
				$contentStrategies = mock(PluginManagerInterface::class);
				$contentValidators = mock(PluginManagerInterface::class);

				$response = mock(ResponseInterface::class);
				$content0 = mock();
				$content1 = mock();
				$content2 = mock();
				$mediaType = 'media/test';
				$decoder = mock(OAGAC\BodyDecoderInterface::class);
				$body = mock(StreamInterface::class);
				$response->shouldReceive('getHeader')->with('Content-Type')->andReturn([$mediaType])->once();
				$response->shouldReceive('getBody')->andReturn($body)->once();
				$bodyDecoderFactory->shouldReceive('__invoke')->with($mediaType, [])->andReturn($decoder)->once();
				$decoder->shouldReceive('decode')->with($body)->andReturn($content1)->once();
				$dt->shouldReceive('transferToTypedData')->withArgs(
					function ($a, &$b) use (&$content0, &$content1, &$content2)
					{
						$result = ($a === $content1) && ($b === $content0);
						if ($result)
						{
							$b = $content2;
						}
						return $result;
					}
				)->andReturn([])->once();

				$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
				$client->parseBody($response, $content0);
				expect($content0)->toBe($content2);
			});
			it('decodes response body and throws if transfer it to content fails', function ()
			{
				$url = 'http://test.url:1234';
				$dt = mock(DTService::class);
				$requestFactory = mock(RequestFactoryInterface::class);
				$httpClient = mock(ClientInterface::class);
				$securityProviderFactory = mock(PluginManagerInterface::class);
				$bodyEncoderFactory = mock(PluginManagerInterface::class);
				$bodyDecoderFactory = mock(PluginManagerInterface::class);
				$contentStrategies = mock(PluginManagerInterface::class);
				$contentValidators = mock(PluginManagerInterface::class);

				$response = mock(ResponseInterface::class);
				$content0 = mock();
				$content1 = mock();
				$mediaType = 'media/test';
				$decoder = mock(OAGAC\BodyDecoderInterface::class);
				$body = mock(StreamInterface::class);
				$violations = ['test' => 123];
				$response->shouldReceive('getHeader')->with('Content-Type')->andReturn([$mediaType])->once();
				$response->shouldReceive('getBody')->andReturn($body)->once();
				$bodyDecoderFactory->shouldReceive('__invoke')->with($mediaType, [])->andReturn($decoder)->once();
				$decoder->shouldReceive('decode')->with($body)->andReturn($content1)->once();
				$dt->shouldReceive('transferToTypedData')->with($content1, $content0)->andReturn($violations)->once();

				$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
				try
				{
					$client->parseBody($response, $content0);
					throw new LogicException('No expected exception');
				}
				catch (OAGAC\Exception\InvalidResponseBodySchema $e)
				{
					expect($e->getResponse())->toBe($response);
					expect($e->getViolations())->toBe($violations);
				}
			});
			it('decodes response body and transfers it to content with specified strategy and validator', function ()
			{
				$url = 'http://test.url:1234';
				$dt = mock(DTService::class);
				$requestFactory = mock(RequestFactoryInterface::class);
				$httpClient = mock(ClientInterface::class);
				$securityProviderFactory = mock(PluginManagerInterface::class);
				$bodyEncoderFactory = mock(PluginManagerInterface::class);
				$bodyDecoderFactory = mock(PluginManagerInterface::class);
				$contentStrategies = mock(PluginManagerInterface::class);
				$contentValidators = mock(PluginManagerInterface::class);

				$response = mock(ResponseInterface::class);
				$content0 = mock();
				$content1 = mock();
				$content2 = mock();
				$mediaType = 'media/test';
				$decoder = mock(OAGAC\BodyDecoderInterface::class);
				$body = mock(StreamInterface::class);
				$contentStrategy = mock(DTStrategy\StrategyInterface::class);
				$contentValidator = mock(DTValidator\ValidatorInterface::class);
				$noopStrategy = mock(DTStrategy\StrategyInterface::class);
				$response->shouldReceive('getHeader')->with('Content-Type')->andReturn([$mediaType])->once();
				$response->shouldReceive('getBody')->andReturn($body)->once();
				$bodyDecoderFactory->shouldReceive('__invoke')->with($mediaType, [])->andReturn($decoder)->once();
				$decoder->shouldReceive('decode')->with($body)->andReturn($content1)->once();
				$contentStrategies->shouldReceive('__invoke')->with(DTStrategy\Whatever::class, [])->andReturn($noopStrategy)->once();
				$dt->shouldReceive('transfer')->withArgs(
					function ($from, $fromExtractor, &$to, $toExtractor, $toMerger, $toValidator, $toHydrator) use (&$content0, &$content1, &$content2, &$contentStrategy, &$contentValidator, &$noopStrategy)
					{
						$result = ($from === $content1)
							&& ($to === $content0)
							&& ($fromExtractor === $noopStrategy)
							&& ($toExtractor === $contentStrategy)
							&& ($toMerger === $contentStrategy)
							&& ($toValidator === $contentValidator)
							&& ($toHydrator === $contentStrategy)
						;
						if ($result)
						{
							$to = $content2;
						}
						return $result;
					}
				)->andReturn([])->once();

				$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
				$client->parseBody($response, $content0, $contentStrategy, $contentValidator);
				expect($content0)->toBe($content2);
			});
			it('decodes response body and throws if specified strategy and validator fail to transfer it to content', function ()
			{
				$url = 'http://test.url:1234';
				$dt = mock(DTService::class);
				$requestFactory = mock(RequestFactoryInterface::class);
				$httpClient = mock(ClientInterface::class);
				$securityProviderFactory = mock(PluginManagerInterface::class);
				$bodyEncoderFactory = mock(PluginManagerInterface::class);
				$bodyDecoderFactory = mock(PluginManagerInterface::class);
				$contentStrategies = mock(PluginManagerInterface::class);
				$contentValidators = mock(PluginManagerInterface::class);

				$response = mock(ResponseInterface::class);
				$content0 = mock();
				$content1 = mock();
				$mediaType = 'media/test';
				$decoder = mock(OAGAC\BodyDecoderInterface::class);
				$body = mock(StreamInterface::class);
				$contentStrategy = mock(DTStrategy\StrategyInterface::class);
				$contentValidator = mock(DTValidator\ValidatorInterface::class);
				$noopStrategy = mock(DTStrategy\StrategyInterface::class);
				$violations = ['test' => 123];
				$response->shouldReceive('getHeader')->with('Content-Type')->andReturn([$mediaType])->once();
				$response->shouldReceive('getBody')->andReturn($body)->once();
				$bodyDecoderFactory->shouldReceive('__invoke')->with($mediaType, [])->andReturn($decoder)->once();
				$decoder->shouldReceive('decode')->with($body)->andReturn($content1)->once();
				$contentStrategies->shouldReceive('__invoke')->with(DTStrategy\Whatever::class, [])->andReturn($noopStrategy)->once();
				$dt->shouldReceive('transfer')->with($content1, $noopStrategy, $content0, $contentStrategy, $contentStrategy, $contentValidator, $contentStrategy)->andReturn($violations)->once();

				$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
				try
				{
					$client->parseBody($response, $content0, $contentStrategy, $contentValidator);
					throw new LogicException('No expected exception');
				}
				catch (OAGAC\Exception\InvalidResponseBodySchema $e)
				{
					expect($e->getResponse())->toBe($response);
					expect($e->getViolations())->toBe($violations);
				}
			});
		});
	});
	context('->getSuccessfulContent', function ()
	{
		it('throws on non-2xx status codes', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$content = mock();
			$headers = ['abc' => ['def', 'ghi']];
			$reason = 'Test Reason Phrase';

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			try
			{
				$client->getSuccessfulContent($content, $headers, 100, $reason);
				throw new LogicException('No expected exception');
			}
			catch (OAGAC\Exception\UnsuccessfulResponse $e)
			{
				expect($e->getResponseContent())->toBe($content);
				expect($e->getCode())->toBe(100);
				expect($e->getResponseHeaders())->toBe($headers);
				expect($e->getMessage())->toBe($reason);
			}
			try
			{
				$client->getSuccessfulContent($content, $headers, 199, $reason);
				throw new LogicException('No expected exception');
			}
			catch (OAGAC\Exception\UnsuccessfulResponse $e)
			{
				expect($e->getResponseContent())->toBe($content);
				expect($e->getCode())->toBe(199);
				expect($e->getResponseHeaders())->toBe($headers);
				expect($e->getMessage())->toBe($reason);
			}
			try
			{
				$client->getSuccessfulContent($content, $headers, 300, $reason);
				throw new LogicException('No expected exception');
			}
			catch (OAGAC\Exception\UnsuccessfulResponse $e)
			{
				expect($e->getResponseContent())->toBe($content);
				expect($e->getCode())->toBe(300);
				expect($e->getResponseHeaders())->toBe($headers);
				expect($e->getMessage())->toBe($reason);
			}
			try
			{
				$client->getSuccessfulContent($content, $headers, 400, $reason);
				throw new LogicException('No expected exception');
			}
			catch (OAGAC\Exception\UnsuccessfulResponse $e)
			{
				expect($e->getResponseContent())->toBe($content);
				expect($e->getCode())->toBe(400);
				expect($e->getResponseHeaders())->toBe($headers);
				expect($e->getMessage())->toBe($reason);
			}
		});
		it('returns content on 2xx status code', function ()
		{
			$url = 'http://test.url:1234';
			$dt = mock(DTService::class);
			$requestFactory = mock(RequestFactoryInterface::class);
			$httpClient = mock(ClientInterface::class);
			$securityProviderFactory = mock(PluginManagerInterface::class);
			$bodyEncoderFactory = mock(PluginManagerInterface::class);
			$bodyDecoderFactory = mock(PluginManagerInterface::class);
			$contentStrategies = mock(PluginManagerInterface::class);
			$contentValidators = mock(PluginManagerInterface::class);

			$content = mock();
			$headers = ['abc' => ['def', 'ghi']];
			$reason = 'Test Reason Phrase';

			$client = new Example\DummyApiClient($url, $dt, $requestFactory, $httpClient, $securityProviderFactory, $bodyEncoderFactory, $bodyDecoderFactory, $contentStrategies, $contentValidators);
			expect($client->getSuccessfulContent($content, $headers, 200, $reason))->toBe($content);
			expect($client->getSuccessfulContent($content, $headers, 204, $reason))->toBe($content);
			expect($client->getSuccessfulContent($content, $headers, 299, $reason))->toBe($content);
		});
	});
});
