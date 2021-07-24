<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\BodyCoder\Factory;

use Articus\DataTransfer\ConfigAwareFactory;
use Interop\Container\ContainerInterface;
use OpenAPIGenerator\APIClient as OAGAC;
use Psr\Http\Message\StreamFactoryInterface;

class Json extends ConfigAwareFactory
{
	public function __construct(string $configKey = OAGAC\BodyCoder\Json::class)
	{
		parent::__construct($configKey);
	}

	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		$config = new OAGAC\BodyCoder\Options\Json(\array_merge($this->getServiceConfig($container), $options ?? []));
		return new OAGAC\BodyCoder\Json(
			$this->getStreamFactory($container, $config->streamFactoryServiceName),
			$config->encodeFlags,
			$config->decodeFlags,
			$config->depth
		);
	}

	protected function getStreamFactory(ContainerInterface $container, string $serviceName): StreamFactoryInterface
	{
		return $container->get($serviceName);
	}
}
