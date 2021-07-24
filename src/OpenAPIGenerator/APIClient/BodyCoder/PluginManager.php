<?php
declare(strict_types=1);

namespace OpenAPIGenerator\APIClient\BodyCoder;

use Laminas\ServiceManager\AbstractPluginManager;
use OpenAPIGenerator\APIClient\BodyCoderInterface;

class PluginManager extends AbstractPluginManager
{
	protected $instanceOf = BodyCoderInterface::class;

	protected $factories = [
		Json::class => Factory\Json::class,
	];

	protected $shared = [
		Json::class => true,
	];

	protected $aliases = [
		'application/json' => Json::class,
	];
}
