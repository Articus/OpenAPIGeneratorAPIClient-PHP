<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient;

use Articus\DataTransfer\Service as DTService;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class ApiClientOptions
{
	public const DEFAULT_SECURITY_PROVIDER_PLUGIN_MANAGER = 'OpenAPIGenerator\APIClient\SecurityProvider\PluginManager';
	public const DEFAULT_BODY_CODER_PLUGIN_MANAGER = 'OpenAPIGenerator\APIClient\BodyCoder\PluginManager';

	public string $serverUrl = 'http://localhost';

	public string $dataTransferServiceName = DTService::class;

	public string $requestFactoryServiceName = RequestFactoryInterface::class;

	public string $httpClientServiceName = ClientInterface::class;

	public string $securityProviderFactoryServiceName = self::DEFAULT_SECURITY_PROVIDER_PLUGIN_MANAGER;

	public string $bodyCoderFactoryServiceName = self::DEFAULT_BODY_CODER_PLUGIN_MANAGER;

	public function __construct(iterable $options)
	{
		foreach ($options as $key => $value)
		{
			switch ($key)
			{
				case 'serverUrl':
				case 'server_url':
					$this->serverUrl = $value;
					break;
				case 'dataTransferServiceName':
				case 'data_transfer_service_name':
					$this->dataTransferServiceName = $value;
					break;
				case 'requestFactoryServiceName':
				case 'request_factory_service_name':
					$this->requestFactoryServiceName = $value;
					break;
				case 'httpClientServiceName':
				case 'http_client_service_name':
					$this->httpClientServiceName = $value;
					break;
				case 'securityProviderFactoryServiceName':
				case 'security_provider_factory_service_name':
					$this->securityProviderFactoryServiceName = $value;
					break;
				case 'bodyCoderFactoryServiceName':
				case 'body_coder_factory_service_name':
					$this->bodyCoderFactoryServiceName = $value;
					break;
			}
		}
	}
}
