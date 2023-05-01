<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\SecurityProvider\Factory;

use Articus\PluginManager as PM;
use OpenAPIGenerator\APIClient\ApiClientOptions;
use OpenAPIGenerator\APIClient\SecurityProvider;
use Psr\Container\ContainerInterface;
use function array_merge_recursive;

class LaminasPluginManager extends PM\Factory\Laminas
{
	public function __construct(string $configKey = ApiClientOptions::DEFAULT_SECURITY_PROVIDER_PLUGIN_MANAGER)
	{
		parent::__construct($configKey);
	}

	protected function getServiceConfig(ContainerInterface $container): array
	{
		$defaultConfig = [
			'invokables' => [
				SecurityProvider\HttpBearer::class => SecurityProvider\HttpBearer::class,
			],
			'aliases' => [
				'HttpBearer' => SecurityProvider\HttpBearer::class,
				'http-bearer' => SecurityProvider\HttpBearer::class,
			],
			'shared' => [
				SecurityProvider\HttpBearer::class => true,
			],
		];

		return array_merge_recursive($defaultConfig, parent::getServiceConfig($container));
	}
}
