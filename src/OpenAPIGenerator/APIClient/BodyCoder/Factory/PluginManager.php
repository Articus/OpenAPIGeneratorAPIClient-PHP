<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\BodyCoder\Factory;

use Articus\DataTransfer\ConfigAwareFactory;
use Interop\Container\ContainerInterface;
use OpenAPIGenerator\APIClient as OAGAC;

class PluginManager extends ConfigAwareFactory
{
	public function __construct(string $configKey = OAGAC\BodyCoder\PluginManager::class)
	{
		parent::__construct($configKey);
	}

	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new OAGAC\BodyCoder\PluginManager($container, $this->getServiceConfig($container));
	}
}
