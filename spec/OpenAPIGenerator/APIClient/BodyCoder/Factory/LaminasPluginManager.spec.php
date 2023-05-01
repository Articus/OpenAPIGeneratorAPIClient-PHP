<?php
declare(strict_types=1);

use Articus\PluginManager as PM;
use OpenAPIGenerator\APIClient as OAGAC;
use Psr\Container\ContainerInterface;

describe(OAGAC\BodyCoder\Factory\LaminasPluginManager::class, function ()
{
	it('creates service with empty options', function ()
	{
		$container = mock(ContainerInterface::class);

		$container->shouldReceive('get')->with('config')->andReturn([]);

		$factory = new OAGAC\BodyCoder\Factory\LaminasPluginManager();
		expect($factory($container, ''))->toBeAnInstanceOf(PM\Laminas::class);
	});
	//TODO how to test passing options?
});
