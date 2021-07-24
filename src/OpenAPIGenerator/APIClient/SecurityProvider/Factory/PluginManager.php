<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\SecurityProvider\Factory;

use Articus\DataTransfer\ConfigAwareFactory;
use Interop\Container\ContainerInterface;
use OpenAPIGenerator\APIClient as OAGAC;

class PluginManager extends ConfigAwareFactory
{
	public function __construct(string $configKey = OAGAC\SecurityProvider\PluginManager::class)
	{
		parent::__construct($configKey);
	}

	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
	{
		return new OAGAC\SecurityProvider\PluginManager($container, $this->getServiceConfig($container));
	}
}
