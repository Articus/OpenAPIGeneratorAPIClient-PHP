<?php
declare(strict_types=1);

use OpenAPIGenerator\APIClient as OAGAC;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\StreamFactoryInterface;

describe(OAGAC\BodyCoder\Factory\Json::class, function ()
{
	it('creates service with empty options', function ()
	{
		$container = mock(ContainerInterface::class);
		$streamFactory = mock(StreamFactoryInterface::class);

		$container->shouldReceive('get')->with('config')->andReturn([])->once();
		$container->shouldReceive('get')->with(StreamFactoryInterface::class)->andReturn($streamFactory)->once();

		$factory = new OAGAC\BodyCoder\Factory\Json();
		$service = $factory($container, '');
		expect($service)->toBeAnInstanceOf(OAGAC\BodyCoder\Json::class);
		expect(propertyByPath($service, ['streamFactory']))->toBe($streamFactory);
		expect(propertyByPath($service, ['encodeFlags']))->toBe(0);
		expect(propertyByPath($service, ['decodeFlags']))->toBe(JSON_OBJECT_AS_ARRAY);
		expect(propertyByPath($service, ['depth']))->toBe(512);
	});
	context('snake case names for custom options', function ()
	{
		it('creates service with custom options from config service', function ()
		{
			$container = mock(ContainerInterface::class);
			$streamFactory = mock(StreamFactoryInterface::class);
			$streamFactoryServiceName = 'abc';
			$encodeFlags = 123;
			$decodeFlags = 456;
			$depth = 789;
			$config = [
				OAGAC\BodyCoder\Json::class => [
					'stream_factory_service_name' => $streamFactoryServiceName,
					'encode_flags' => $encodeFlags,
					'decode_flags' => $decodeFlags,
					'depth' => $depth,
				],
			];

			$container->shouldReceive('get')->with('config')->andReturn($config)->once();
			$container->shouldReceive('get')->with($streamFactoryServiceName)->andReturn($streamFactory)->once();

			$factory = new OAGAC\BodyCoder\Factory\Json();
			$service = $factory($container, '');
			expect($service)->toBeAnInstanceOf(OAGAC\BodyCoder\Json::class);
			expect(propertyByPath($service, ['streamFactory']))->toBe($streamFactory);
			expect(propertyByPath($service, ['encodeFlags']))->toBe($encodeFlags);
			expect(propertyByPath($service, ['decodeFlags']))->toBe($decodeFlags);
			expect(propertyByPath($service, ['depth']))->toBe($depth);
		});
	});
	context('camel case names for custom options', function ()
	{
		it('creates service with custom options from config service', function ()
		{
			$container = mock(ContainerInterface::class);
			$streamFactory = mock(StreamFactoryInterface::class);
			$streamFactoryServiceName = 'abc';
			$encodeFlags = 123;
			$decodeFlags = 456;
			$depth = 789;
			$config = [
				OAGAC\BodyCoder\Json::class => [
					'streamFactoryServiceName' => $streamFactoryServiceName,
					'encodeFlags' => $encodeFlags,
					'decodeFlags' => $decodeFlags,
					'depth' => $depth,
				],
			];

			$container->shouldReceive('get')->with('config')->andReturn($config)->once();
			$container->shouldReceive('get')->with($streamFactoryServiceName)->andReturn($streamFactory)->once();

			$factory = new OAGAC\BodyCoder\Factory\Json();
			$service = $factory($container, '');
			expect($service)->toBeAnInstanceOf(OAGAC\BodyCoder\Json::class);
			expect(propertyByPath($service, ['streamFactory']))->toBe($streamFactory);
			expect(propertyByPath($service, ['encodeFlags']))->toBe($encodeFlags);
			expect(propertyByPath($service, ['decodeFlags']))->toBe($decodeFlags);
			expect(propertyByPath($service, ['depth']))->toBe($depth);
		});
	});
});
