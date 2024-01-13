<?php
declare(strict_types=1);

use Articus\PluginManager as PM;
use OpenAPIGenerator\APIClient as OAGAC;
use Psr\Container\ContainerInterface;

describe(OAGAC\BodyCoder\Factory\PluginManager::class, function ()
{
	afterEach(function ()
	{
		Mockery::close();
	});
	it('creates service with empty options', function ()
	{
		$container = mock(ContainerInterface::class);

		$container->shouldReceive('get')->with('config')->andReturn([]);

		$factory = new OAGAC\BodyCoder\Factory\PluginManager();
		$manager = $factory($container, '');
		expect($manager)->toBeAnInstanceOf(PM\Simple::class);
		$factories = propertyByPath($manager, ['factories']);
		expect(array_keys($factories))->toBe([
			OAGAC\BodyCoder\Json::class,
		]);
	});
	//TODO how to test passing options?
});
