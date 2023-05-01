<?php
declare(strict_types=1);

use Articus\PluginManager as PM;
use OpenAPIGenerator\APIClient as OAGAC;
use Psr\Container\ContainerInterface;

describe(OAGAC\SecurityProvider\Factory\SimplePluginManager::class, function ()
{
	it('creates service with empty options', function ()
	{
		$container = mock(ContainerInterface::class);

		$container->shouldReceive('get')->with('config')->andReturn([]);

		$factory = new OAGAC\SecurityProvider\Factory\SimplePluginManager();
		expect($factory($container, ''))->toBeAnInstanceOf(PM\Simple::class);
	});
	//TODO how to test passing options?
});
