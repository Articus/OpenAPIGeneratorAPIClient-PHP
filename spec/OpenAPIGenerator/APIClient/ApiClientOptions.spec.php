<?php
declare(strict_types=1);

use OpenAPIGenerator\APIClient as OAGAC;

describe(OAGAC\ApiClientOptions::class, function ()
{
	context('->__construct', function ()
	{
		it('allows snake-cased keys', function ()
		{
			$serverUrl = 'test_server_url';
			$dtServiceName = 'test_dt_service';
			$requestFactoryServiceName = 'test_request_factory_service';
			$httpClientServiceName = 'test_http_client_service';
			$securityProviderFactoryServiceName = 'test_security_provider_factory_service';
			$bodyCoderFactoryServiceName = 'test_body_code_factory_service';
			$options = new OAGAC\ApiClientOptions([
				'server_url' => $serverUrl,
				'data_transfer_service_name' => $dtServiceName,
				'request_factory_service_name' => $requestFactoryServiceName,
				'http_client_service_name' => $httpClientServiceName,
				'security_provider_factory_service_name' => $securityProviderFactoryServiceName,
				'body_coder_factory_service_name' => $bodyCoderFactoryServiceName,
			]);
			expect($options->serverUrl)->toBe($serverUrl);
			expect($options->dataTransferServiceName)->toBe($dtServiceName);
			expect($options->requestFactoryServiceName)->toBe($requestFactoryServiceName);
			expect($options->httpClientServiceName)->toBe($httpClientServiceName);
			expect($options->securityProviderFactoryServiceName)->toBe($securityProviderFactoryServiceName);
			expect($options->bodyCoderFactoryServiceName)->toBe($bodyCoderFactoryServiceName);
		});
		it('allows camel-cased keys', function ()
		{
			$serverUrl = 'test_server_url';
			$dtServiceName = 'test_dt_service';
			$requestFactoryServiceName = 'test_request_factory_service';
			$httpClientServiceName = 'test_http_client_service';
			$securityProviderFactoryServiceName = 'test_security_provider_factory_service';
			$bodyCoderFactoryServiceName = 'test_body_code_factory_service';
			$options = new OAGAC\ApiClientOptions([
				'serverUrl' => $serverUrl,
				'dataTransferServiceName' => $dtServiceName,
				'requestFactoryServiceName' => $requestFactoryServiceName,
				'httpClientServiceName' => $httpClientServiceName,
				'securityProviderFactoryServiceName' => $securityProviderFactoryServiceName,
				'bodyCoderFactoryServiceName' => $bodyCoderFactoryServiceName,
			]);
			expect($options->serverUrl)->toBe($serverUrl);
			expect($options->dataTransferServiceName)->toBe($dtServiceName);
			expect($options->requestFactoryServiceName)->toBe($requestFactoryServiceName);
			expect($options->httpClientServiceName)->toBe($httpClientServiceName);
			expect($options->securityProviderFactoryServiceName)->toBe($securityProviderFactoryServiceName);
			expect($options->bodyCoderFactoryServiceName)->toBe($bodyCoderFactoryServiceName);
		});
	});
});
