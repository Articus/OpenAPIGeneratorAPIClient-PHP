<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient;

use Articus\DataTransfer\Service as DTService;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class ApiClientOptions
{
	/**
	 * @var string
	 */
	public $serverUrl = 'http://localhost';

	/**
	 * @var string
	 */
	public $dataTransferServiceName = DTService::class;

	/**
	 * @var string
	 */
	public $requestFactoryServiceName = RequestFactoryInterface::class;

	/**
	 * @var string
	 */
	public $httpClientServiceName = ClientInterface::class;

	/**
	 * @var string
	 */
	public $securityProviderFactoryServiceName = SecurityProvider\PluginManager::class;

	/**
	 * @var string
	 */
	public $bodyCoderFactoryServiceName = BodyCoder\PluginManager::class;

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
