<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\BodyCoder\Factory;

use Articus\PluginManager as PM;
use OpenAPIGenerator\APIClient\ApiClientOptions;
use OpenAPIGenerator\APIClient\BodyCoder;
use Psr\Container\ContainerInterface;
use function array_merge_recursive;

class LaminasPluginManager extends PM\Factory\Laminas
{
	public function __construct(string $configKey = ApiClientOptions::DEFAULT_BODY_CODER_PLUGIN_MANAGER)
	{
		parent::__construct($configKey);
	}

	protected function getServiceConfig(ContainerInterface $container): array
	{
		$defaultConfig = [
			'factories' => [
				BodyCoder\Json::class => BodyCoder\Factory\Json::class,
			],
			'aliases' => [
				'application/json' => BodyCoder\Json::class,
			],
			'shared' => [
				BodyCoder\Json::class => true,
			],
		];

		return array_merge_recursive($defaultConfig, parent::getServiceConfig($container));
	}
}
