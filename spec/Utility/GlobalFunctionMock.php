<?php
declare(strict_types=1);

namespace spec\Utility;

use Mockery\Expectation;
use Mockery\MockInterface;
use function array_keys;
use function extension_loaded;
use function mock;
use function uopz_set_return;
use function uopz_unset_return;

class GlobalFunctionMock
{
	protected static ?MockInterface $innerMock = null;

	/**
	 * @var array<string, true>
	 */
	protected static array $functionNameMap = [];

	public static function disabled(): bool
	{
		return (!extension_loaded('uopz'));
	}

	/**
	 * @param string $functionName
	 * @return Expectation|\Mockery\ExpectationInterface|\Mockery\HigherOrderMessage
	 */
	public static function shouldReceive(string $functionName)
	{
		if (!isset(self::$innerMock))
		{
			self::$innerMock = mock();
		}
		if (!isset(self::$functionNameMap[$functionName]))
		{
			$mock = self::$innerMock;
			uopz_set_return(
				$functionName,
				static fn (...$arguments) => $mock->{$functionName}(...$arguments),
				true
			);
			self::$functionNameMap[$functionName] = true;
		}
		return self::$innerMock->shouldReceive($functionName);
	}

	public static function reset(): void
	{
		foreach (array_keys(self::$functionNameMap) as $functionName)
		{
			uopz_unset_return($functionName);
			unset(self::$functionNameMap[$functionName]);
		}
		self::$innerMock = null;
	}
}
