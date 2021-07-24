<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\SecurityProvider;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\Factory\InvokableFactory;
use OpenAPIGenerator\APIClient\SecurityProviderInterface;

class PluginManager extends AbstractPluginManager
{
	protected $instanceOf = SecurityProviderInterface::class;

	protected $factories = [
		HttpBearer::class => InvokableFactory::class,
	];

	protected $shared = [
		HttpBearer::class => true,
	];

	protected $aliases = [
		'HttpBearer' => HttpBearer::class,
		'http-bearer' => HttpBearer::class,
	];
}
