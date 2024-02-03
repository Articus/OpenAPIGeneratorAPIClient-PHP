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
			$contentStrategyFactoryServiceName = 'test_content_strategy_factory_service';
			$contentValidatorFactoryServiceName = 'test_content_validator_factory_service';
			$options = new OAGAC\ApiClientOptions([
				'server_url' => $serverUrl,
				'data_transfer_service_name' => $dtServiceName,
				'request_factory_service_name' => $requestFactoryServiceName,
				'http_client_service_name' => $httpClientServiceName,
				'security_provider_factory_service_name' => $securityProviderFactoryServiceName,
				'body_coder_factory_service_name' => $bodyCoderFactoryServiceName,
				'content_strategy_factory_service_name' => $contentStrategyFactoryServiceName,
				'content_validator_factory_service_name' => $contentValidatorFactoryServiceName,
			]);
			expect($options->serverUrl)->toBe($serverUrl);
			expect($options->dataTransferServiceName)->toBe($dtServiceName);
			expect($options->requestFactoryServiceName)->toBe($requestFactoryServiceName);
			expect($options->httpClientServiceName)->toBe($httpClientServiceName);
			expect($options->securityProviderFactoryServiceName)->toBe($securityProviderFactoryServiceName);
			expect($options->bodyCoderFactoryServiceName)->toBe($bodyCoderFactoryServiceName);
			expect($options->contentStrategyFactoryServiceName)->toBe($contentStrategyFactoryServiceName);
			expect($options->contentValidatorFactoryServiceName)->toBe($contentValidatorFactoryServiceName);
		});
		it('allows camel-cased keys', function ()
		{
			$serverUrl = 'test_server_url';
			$dtServiceName = 'test_dt_service';
			$requestFactoryServiceName = 'test_request_factory_service';
			$httpClientServiceName = 'test_http_client_service';
			$securityProviderFactoryServiceName = 'test_security_provider_factory_service';
			$bodyCoderFactoryServiceName = 'test_body_code_factory_service';
			$contentStrategyFactoryServiceName = 'test_content_strategy_factory_service';
			$contentValidatorFactoryServiceName = 'test_content_validator_factory_service';
			$options = new OAGAC\ApiClientOptions([
				'serverUrl' => $serverUrl,
				'dataTransferServiceName' => $dtServiceName,
				'requestFactoryServiceName' => $requestFactoryServiceName,
				'httpClientServiceName' => $httpClientServiceName,
				'securityProviderFactoryServiceName' => $securityProviderFactoryServiceName,
				'bodyCoderFactoryServiceName' => $bodyCoderFactoryServiceName,
				'contentStrategyFactoryServiceName' => $contentStrategyFactoryServiceName,
				'contentValidatorFactoryServiceName' => $contentValidatorFactoryServiceName,
			]);
			expect($options->serverUrl)->toBe($serverUrl);
			expect($options->dataTransferServiceName)->toBe($dtServiceName);
			expect($options->requestFactoryServiceName)->toBe($requestFactoryServiceName);
			expect($options->httpClientServiceName)->toBe($httpClientServiceName);
			expect($options->securityProviderFactoryServiceName)->toBe($securityProviderFactoryServiceName);
			expect($options->bodyCoderFactoryServiceName)->toBe($bodyCoderFactoryServiceName);
			expect($options->contentStrategyFactoryServiceName)->toBe($contentStrategyFactoryServiceName);
			expect($options->contentValidatorFactoryServiceName)->toBe($contentValidatorFactoryServiceName);
		});
	});
});
