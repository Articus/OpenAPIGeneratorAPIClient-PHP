<?php
declare(strict_types=1);

namespace spec\OpenAPIGenerator\APIClient\BodyCoder\Factory;

use Interop\Container\ContainerInterface;
use OpenAPIGenerator\APIClient as OAGAC;
use Psr\Http\Message\StreamFactoryInterface;

\describe(OAGAC\BodyCoder\Factory\Json::class, function ()
{
	\it('creates service with empty options', function ()
	{
		$container = \mock(ContainerInterface::class);
		$streamFactory = \mock(StreamFactoryInterface::class);

		$container->shouldReceive('get')->with('config')->andReturn([])->once();
		$container->shouldReceive('get')->with(StreamFactoryInterface::class)->andReturn($streamFactory)->once();

		$factory = new OAGAC\BodyCoder\Factory\Json();
		$service = $factory($container, '');
		\expect($service)->toBeAnInstanceOf(OAGAC\BodyCoder\Json::class);
		\expect(\propertyByPath($service, ['streamFactory']))->toBe($streamFactory);
		\expect(\propertyByPath($service, ['encodeFlags']))->toBe(0);
		\expect(\propertyByPath($service, ['decodeFlags']))->toBe(\JSON_OBJECT_AS_ARRAY);
		\expect(\propertyByPath($service, ['depth']))->toBe(512);
	});
	\context('snake case names for custom options', function ()
	{
		\it('creates service with custom options from config service', function ()
		{
			$container = \mock(ContainerInterface::class);
			$streamFactory = \mock(StreamFactoryInterface::class);
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
			\expect($service)->toBeAnInstanceOf(OAGAC\BodyCoder\Json::class);
			\expect(\propertyByPath($service, ['streamFactory']))->toBe($streamFactory);
			\expect(\propertyByPath($service, ['encodeFlags']))->toBe($encodeFlags);
			\expect(\propertyByPath($service, ['decodeFlags']))->toBe($decodeFlags);
			\expect(\propertyByPath($service, ['depth']))->toBe($depth);
		});
		\it('creates service with custom options from method argument', function ()
		{
			$container = \mock(ContainerInterface::class);
			$streamFactory = \mock(StreamFactoryInterface::class);
			$streamFactoryServiceName = 'abc';
			$encodeFlags = 123;
			$decodeFlags = 456;
			$depth = 789;
			$options = [
				'stream_factory_service_name' => $streamFactoryServiceName,
				'encode_flags' => $encodeFlags,
				'decode_flags' => $decodeFlags,
				'depth' => $depth,
			];

			$container->shouldReceive('get')->with('config')->andReturn([])->once();
			$container->shouldReceive('get')->with($streamFactoryServiceName)->andReturn($streamFactory)->once();

			$factory = new OAGAC\BodyCoder\Factory\Json();
			$service = $factory($container, '', $options);
			\expect($service)->toBeAnInstanceOf(OAGAC\BodyCoder\Json::class);
			\expect(\propertyByPath($service, ['streamFactory']))->toBe($streamFactory);
			\expect(\propertyByPath($service, ['encodeFlags']))->toBe($encodeFlags);
			\expect(\propertyByPath($service, ['decodeFlags']))->toBe($decodeFlags);
			\expect(\propertyByPath($service, ['depth']))->toBe($depth);
		});
		\it('creates service with custom options merged from default values, config service and method argument', function ()
		{
			$container = \mock(ContainerInterface::class);
			$streamFactory = \mock(StreamFactoryInterface::class);
			$encodeFlags = 123;
			$decodeFlags = 456;
			$depth = 789;
			$config = [
				OAGAC\BodyCoder\Json::class => [
					'encode_flags' => 321,
					'decode_flags' => $decodeFlags,
				],
			];
			$options = [
				'encode_flags' => $encodeFlags,
				'depth' => $depth,
			];

			$container->shouldReceive('get')->with('config')->andReturn($config)->once();
			$container->shouldReceive('get')->with(StreamFactoryInterface::class)->andReturn($streamFactory)->once();

			$factory = new OAGAC\BodyCoder\Factory\Json();
			$service = $factory($container, '', $options);
			\expect($service)->toBeAnInstanceOf(OAGAC\BodyCoder\Json::class);
			\expect(\propertyByPath($service, ['streamFactory']))->toBe($streamFactory);
			\expect(\propertyByPath($service, ['encodeFlags']))->toBe($encodeFlags);
			\expect(\propertyByPath($service, ['decodeFlags']))->toBe($decodeFlags);
			\expect(\propertyByPath($service, ['depth']))->toBe($depth);
		});
	});
	\context('camel case names for custom options', function ()
	{
		\it('creates service with custom options from config service', function ()
		{
			$container = \mock(ContainerInterface::class);
			$streamFactory = \mock(StreamFactoryInterface::class);
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
			\expect($service)->toBeAnInstanceOf(OAGAC\BodyCoder\Json::class);
			\expect(\propertyByPath($service, ['streamFactory']))->toBe($streamFactory);
			\expect(\propertyByPath($service, ['encodeFlags']))->toBe($encodeFlags);
			\expect(\propertyByPath($service, ['decodeFlags']))->toBe($decodeFlags);
			\expect(\propertyByPath($service, ['depth']))->toBe($depth);
		});
		\it('creates service with custom options from method argument', function ()
		{
			$container = \mock(ContainerInterface::class);
			$streamFactory = \mock(StreamFactoryInterface::class);
			$streamFactoryServiceName = 'abc';
			$encodeFlags = 123;
			$decodeFlags = 456;
			$depth = 789;
			$options = [
				'streamFactoryServiceName' => $streamFactoryServiceName,
				'encodeFlags' => $encodeFlags,
				'decodeFlags' => $decodeFlags,
				'depth' => $depth,
			];

			$container->shouldReceive('get')->with('config')->andReturn([])->once();
			$container->shouldReceive('get')->with($streamFactoryServiceName)->andReturn($streamFactory)->once();

			$factory = new OAGAC\BodyCoder\Factory\Json();
			$service = $factory($container, '', $options);
			\expect($service)->toBeAnInstanceOf(OAGAC\BodyCoder\Json::class);
			\expect(\propertyByPath($service, ['streamFactory']))->toBe($streamFactory);
			\expect(\propertyByPath($service, ['encodeFlags']))->toBe($encodeFlags);
			\expect(\propertyByPath($service, ['decodeFlags']))->toBe($decodeFlags);
			\expect(\propertyByPath($service, ['depth']))->toBe($depth);
		});
		\it('creates service with custom options merged from default values, config service and method argument', function ()
		{
			$container = \mock(ContainerInterface::class);
			$streamFactory = \mock(StreamFactoryInterface::class);
			$encodeFlags = 123;
			$decodeFlags = 456;
			$depth = 789;
			$config = [
				OAGAC\BodyCoder\Json::class => [
					'encodeFlags' => 321,
					'decodeFlags' => $decodeFlags,
				],
			];
			$options = [
				'encodeFlags' => $encodeFlags,
				'depth' => $depth,
			];

			$container->shouldReceive('get')->with('config')->andReturn($config)->once();
			$container->shouldReceive('get')->with(StreamFactoryInterface::class)->andReturn($streamFactory)->once();

			$factory = new OAGAC\BodyCoder\Factory\Json();
			$service = $factory($container, '', $options);
			\expect($service)->toBeAnInstanceOf(OAGAC\BodyCoder\Json::class);
			\expect(\propertyByPath($service, ['streamFactory']))->toBe($streamFactory);
			\expect(\propertyByPath($service, ['encodeFlags']))->toBe($encodeFlags);
			\expect(\propertyByPath($service, ['decodeFlags']))->toBe($decodeFlags);
			\expect(\propertyByPath($service, ['depth']))->toBe($depth);
		});
	});
});
