<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\BodyCoder\Factory;

use Articus\PluginManager as PM;
use OpenAPIGenerator\APIClient as OAGAC;
use Psr\Container\ContainerInterface;

class Json implements PM\ServiceFactoryInterface
{
	use PM\ConfigAwareFactoryTrait;

	public function __construct(string $configKey = OAGAC\BodyCoder\Json::class)
	{
		$this->configKey = $configKey;
	}

	public function __invoke(ContainerInterface $container, string $name): OAGAC\BodyCoder\Json
	{
		$config = new OAGAC\BodyCoder\Options\Json($this->getServiceConfig($container));
		return new OAGAC\BodyCoder\Json(
			$container->get($config->streamFactoryServiceName),
			$config->encodeFlags,
			$config->decodeFlags,
			$config->depth
		);
	}
}
