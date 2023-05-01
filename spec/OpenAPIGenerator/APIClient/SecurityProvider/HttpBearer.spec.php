<?php
declare(strict_types=1);

use OpenAPIGenerator\APIClient as OAGAC;
use Psr\Http\Message\RequestInterface;

describe(OAGAC\SecurityProvider\HttpBearer::class, function ()
{
 	it('adds authorization header with provided token', function ()
	{
		$token1 = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890-._~+/';
		$token2 = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890-._~+/=';
		$token3 = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890-._~+/===';
		$request = mock(RequestInterface::class);
		$request1 = mock(RequestInterface::class);
		$request2 = mock(RequestInterface::class);
		$request3 = mock(RequestInterface::class);
		$request->shouldReceive('withHeader')->with('Authorization', 'Bearer ' . $token1)->andReturn($request1)->once();
		$request->shouldReceive('withHeader')->with('Authorization', 'Bearer ' . $token2)->andReturn($request2)->once();
		$request->shouldReceive('withHeader')->with('Authorization', 'Bearer ' . $token3)->andReturn($request3)->once();

		$provider = new OAGAC\SecurityProvider\HttpBearer();

		$provider->setToken($token1);
		expect($provider->getToken())->toBe($token1);
		expect($provider->fulfillRequirements($request, []))->toBe($request1);
		$provider->setToken($token2);
		expect($provider->getToken())->toBe($token2);
		expect($provider->fulfillRequirements($request, []))->toBe($request2);
		$provider->setToken($token3);
		expect($provider->getToken())->toBe($token3);
		expect($provider->fulfillRequirements($request, []))->toBe($request3);
	});
 	it('throws if token is not provided', function ()
	{
		$exception = new LogicException('Bearer token should be set before using security provider');
		expect(function ()
		{
			$request = mock(RequestInterface::class);

			$provider = new OAGAC\SecurityProvider\HttpBearer();
			$provider->fulfillRequirements($request, []);
		})->toThrow($exception);
	});
	it('throws if invalid token is provided', function ()
	{
		$exception = new InvalidArgumentException('Invalid bearer token for HTTP authentication');
		$provider = new OAGAC\SecurityProvider\HttpBearer();

		expect(function () use (&$provider)
		{
			$provider->setToken('');
		})->toThrow($exception);
		expect(function () use (&$provider)
		{
			$provider->setToken('=');
		})->toThrow($exception);
		expect(function () use (&$provider)
		{
			$provider->setToken('abc!');
		})->toThrow($exception);
	});
});
