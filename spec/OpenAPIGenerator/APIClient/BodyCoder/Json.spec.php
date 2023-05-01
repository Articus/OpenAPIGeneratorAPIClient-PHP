<?php
declare(strict_types=1);

use OpenAPIGenerator\APIClient as OAGAC;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use spec\Utility\GlobalFunctionMock;

describe(OAGAC\BodyCoder\Json::class, function ()
{
	describe('->encode', function ()
	{
		it('creates stream with data encoded to JSON', function ()
		{
			skipIf(GlobalFunctionMock::disabled());

			$data = mock();
			$json = 'test_string_123';
			$stream = mock(StreamInterface::class);
			$streamFactory = mock(StreamFactoryInterface::class);
			$encodeFlags = 123;
			$decodeFlags = 456;
			$depth = 789;

			GlobalFunctionMock::shouldReceive('json_encode')->with($data, $encodeFlags, $depth)->andReturn($json)->once();
			$streamFactory->shouldReceive('createStream')->with($json)->andReturn($stream)->once();

			$coder = new OAGAC\BodyCoder\Json($streamFactory, $encodeFlags, $decodeFlags, $depth);
			expect($coder->encode($data))->toBe($stream);
		});
		it('throws if data can not be encoded to JSON', function ()
		{
			skipIf(GlobalFunctionMock::disabled());

			$data = mock();
			$encodeError = 'Test JSON encode error text';
			$exception = new InvalidArgumentException('JSON encoding failure. ' . $encodeError);
			$streamFactory = mock(StreamFactoryInterface::class);
			$encodeFlags = 123;
			$decodeFlags = 456;
			$depth = 789;

			GlobalFunctionMock::shouldReceive('json_encode')->with($data, $encodeFlags, $depth)->andReturn(false)->once();
			GlobalFunctionMock::shouldReceive('json_last_error_msg')->andReturn($encodeError)->once();

			$coder = new OAGAC\BodyCoder\Json($streamFactory, $encodeFlags, $decodeFlags, $depth);
			expect(function () use (&$coder, &$data)
			{
				$coder->encode($data);
			})->toThrow($exception);
		});
	});
	describe('->decode', function ()
	{
		context('JSON_OBJECT_AS_ARRAY is set', function ()
		{
			it('parses data from stream with JSON', function ()
			{
				skipIf(GlobalFunctionMock::disabled());

				$stream = mock(StreamInterface::class);
				$json = 'test_string_123';
				$data = mock();
				$streamFactory = mock(StreamFactoryInterface::class);
				$encodeFlags = 123;
				$decodeFlags = 456 | JSON_OBJECT_AS_ARRAY;
				$depth = 789;

				$stream->shouldReceive('getContents')->andReturn($json);
				GlobalFunctionMock::shouldReceive('json_decode')->with($json, true, $depth, $decodeFlags)->andReturn($data)->once();

				$coder = new OAGAC\BodyCoder\Json($streamFactory, $encodeFlags, $decodeFlags, $depth);
				expect($coder->decode($stream))->toBe($data);
			});
			it('parses null from stream with JSON', function ()
			{
				skipIf(GlobalFunctionMock::disabled());

				$stream = mock(StreamInterface::class);
				$json = 'test_string_123';
				$streamFactory = mock(StreamFactoryInterface::class);
				$encodeFlags = 123;
				$decodeFlags = 456 | JSON_OBJECT_AS_ARRAY;
				$depth = 789;

				$stream->shouldReceive('getContents')->andReturn($json);
				GlobalFunctionMock::shouldReceive('json_decode')->with($json, true, $depth, $decodeFlags)->andReturnNull()->once();
				GlobalFunctionMock::shouldReceive('json_last_error')->andReturn(JSON_ERROR_NONE)->once();

				$coder = new OAGAC\BodyCoder\Json($streamFactory, $encodeFlags, $decodeFlags, $depth);
				expect($coder->decode($stream))->toBeNull();
			});
			it('throws if data can not be parsed from stream with JSON', function ()
			{
				skipIf(GlobalFunctionMock::disabled());

				$stream = mock(StreamInterface::class);
				$json = 'test_string_123';
				$decodeError = 'Test JSON decode error text';
				$exception = new InvalidArgumentException('JSON decoding failure. ' . $decodeError);
				$streamFactory = mock(StreamFactoryInterface::class);
				$encodeFlags = 123;
				$decodeFlags = 456 | JSON_OBJECT_AS_ARRAY;
				$depth = 789;

				$stream->shouldReceive('getContents')->andReturn($json);
				GlobalFunctionMock::shouldReceive('json_decode')->with($json, true, $depth, $decodeFlags)->andReturnNull()->once();
				GlobalFunctionMock::shouldReceive('json_last_error')->andReturn(123)->once();
				GlobalFunctionMock::shouldReceive('json_last_error_msg')->andReturn($decodeError)->once();

				$coder = new OAGAC\BodyCoder\Json($streamFactory, $encodeFlags, $decodeFlags, $depth);
				expect(function () use (&$coder, &$stream)
				{
					$coder->decode($stream);
				})->toThrow($exception);
			});
		});
		context('JSON_OBJECT_AS_ARRAY is not set', function ()
		{
			it('parses data from stream with JSON', function ()
			{
				skipIf(GlobalFunctionMock::disabled());

				$stream = mock(StreamInterface::class);
				$json = 'test_string_123';
				$data = mock();
				$streamFactory = mock(StreamFactoryInterface::class);
				$encodeFlags = 123;
				$decodeFlags = 456 & (~JSON_OBJECT_AS_ARRAY);
				$depth = 789;

				$stream->shouldReceive('getContents')->andReturn($json);
				GlobalFunctionMock::shouldReceive('json_decode')->with($json, false, $depth, $decodeFlags)->andReturn($data)->once();

				$coder = new OAGAC\BodyCoder\Json($streamFactory, $encodeFlags, $decodeFlags, $depth);
				expect($coder->decode($stream))->toBe($data);
			});
			it('parses null from stream with JSON', function ()
			{
				skipIf(GlobalFunctionMock::disabled());

				$stream = mock(StreamInterface::class);
				$json = 'test_string_123';
				$streamFactory = mock(StreamFactoryInterface::class);
				$encodeFlags = 123;
				$decodeFlags = 456 & (~JSON_OBJECT_AS_ARRAY);
				$depth = 789;

				$stream->shouldReceive('getContents')->andReturn($json);
				GlobalFunctionMock::shouldReceive('json_decode')->with($json, false, $depth, $decodeFlags)->andReturnNull()->once();
				GlobalFunctionMock::shouldReceive('json_last_error')->andReturn(JSON_ERROR_NONE)->once();

				$coder = new OAGAC\BodyCoder\Json($streamFactory, $encodeFlags, $decodeFlags, $depth);
				expect($coder->decode($stream))->toBeNull();
			});
			it('throws if data can not be parsed from stream with JSON', function ()
			{
				skipIf(GlobalFunctionMock::disabled());

				$stream = mock(StreamInterface::class);
				$json = 'test_string_123';
				$decodeError = 'Test JSON decode error text';
				$exception = new InvalidArgumentException('JSON decoding failure. ' . $decodeError);
				$streamFactory = mock(StreamFactoryInterface::class);
				$encodeFlags = 123;
				$decodeFlags = 456 & (~JSON_OBJECT_AS_ARRAY);
				$depth = 789;

				$stream->shouldReceive('getContents')->andReturn($json);
				GlobalFunctionMock::shouldReceive('json_decode')->with($json, false, $depth, $decodeFlags)->andReturnNull()->once();
				GlobalFunctionMock::shouldReceive('json_last_error')->andReturn(123)->once();
				GlobalFunctionMock::shouldReceive('json_last_error_msg')->andReturn($decodeError)->once();

				$coder = new OAGAC\BodyCoder\Json($streamFactory, $encodeFlags, $decodeFlags, $depth);
				expect(function () use (&$coder, &$stream)
				{
					$coder->decode($stream);
				})->toThrow($exception);
			});
		});
	});
});
