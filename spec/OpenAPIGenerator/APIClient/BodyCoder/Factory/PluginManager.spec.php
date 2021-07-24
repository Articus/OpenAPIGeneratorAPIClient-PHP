<?php
declare(strict_types=1);

namespace spec\OpenAPIGenerator\APIClient\BodyCoder\Factory;

use Interop\Container\ContainerInterface;
use OpenAPIGenerator\APIClient as OAGAC;

\describe(OAGAC\BodyCoder\Factory\PluginManager::class, function ()
{
	\it('creates service with empty options', function ()
	{
		$container = \mock(ContainerInterface::class);

		$container->shouldReceive('get')->with('config')->andReturn([]);

		$factory = new OAGAC\BodyCoder\Factory\PluginManager();
		\expect($factory($container, ''))->toBeAnInstanceOf(OAGAC\BodyCoder\PluginManager::class);
	});
	//TODO how to test passing options?
});
