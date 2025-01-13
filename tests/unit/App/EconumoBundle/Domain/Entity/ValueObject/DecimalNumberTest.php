<?php

declare(strict_types=1);

namespace App\Tests\unit\App\EconumoBundle\Domain\Entity\ValueObject;

use App\EconumoBundle\Domain\Entity\ValueObject\DecimalNumber;
use App\Tests\UnitTester;
use Codeception\Test\Unit;
use TypeError;

class DecimalNumberTest extends Unit
{
    protected UnitTester $tester;

    /**
     * @dataProvider constructorProvider
     */
    public function testConstructor(string|int|float $input, string $expected): void
    {
        $number = new DecimalNumber($input);
        $this->assertEquals($expected, $number->getValue());
    }

    public function constructorProvider(): array
    {
        return [
            'integer' => [123, '123'],
            'float' => [123.456, '123.456'],
            'string' => ['123.456', '123.456'],
            'zero' => [0, '0'],
            'negative' => [-123.456, '-123.456'],
            'large number' => [12345678999999.0, '12345678999999'],
            'large precision' => [1.012345678999999, '1.01234568'],
            'trailing zeros' => [123.4500, '123.45'],
            'zero decimal' => [123.0, '123'],
            'string with trailing zeros' => ['123.4500', '123.45'],
            'leading zeros integer' => ['00123', '123'],
            'leading zeros decimal' => ['00123.456', '123.456'],
            'leading zeros after decimal' => ['123.0456', '123.0456'],
            'negative with leading zeros' => ['-00123.456', '-123.456'],
            'zero with leading zeros' => ['00.123', '0.123'],
            'negative zero with leading zeros' => ['-00.123', '-0.123'],
            'multiple leading zeros' => ['000000123', '123'],
            'zero with multiple leading zeros' => ['000000', '0'],
        ];
    }

    /**
     * @dataProvider invalidConstructorProvider
     */
    public function testConstructorWithInvalidInput(mixed $input): void
    {
        $this->expectException(TypeError::class);
        new DecimalNumber($input);
    }

    public function invalidConstructorProvider(): array
    {
        return [
            'string' => ['abc'],
            'array' => [[]],
            'null' => [null],
            'boolean' => [true],
        ];
    }

    /**
     * @dataProvider additionProvider
     */
    public function testAdd(string|int|float $a, string|int|float $b, string $expected): void
    {
        $number = new DecimalNumber($a);
        $result = $number->add($b);
        $this->assertEquals($expected, $result->getValue());
    }

    public function additionProvider(): array
    {
        return [
            'positive numbers' => [1.5, 2.5, '4'],
            'negative numbers' => [-1.5, -2.5, '-4'],
            'mixed signs' => [1.5, -2.5, '-1'],
            'zero addition' => [1.5, 0, '1.5'],
            'precision test' => [0.1, 0.2, '0.3'],
        ];
    }

    /**
     * @dataProvider subtractionProvider
     */
    public function testSub(string|int|float $a, string|int|float $b, string $expected): void
    {
        $number = new DecimalNumber($a);
        $result = $number->sub($b);
        $this->assertEquals($expected, $result->getValue());
    }

    public function subtractionProvider(): array
    {
        return [
            'positive numbers' => [5.5, 2.5, '3'],
            'negative numbers' => [-1.5, -2.5, '1'],
            'mixed signs' => [1.5, -2.5, '4'],
            'zero subtraction' => [1.5, 0, '1.5'],
            'precision test' => [0.3, 0.1, '0.2'],
        ];
    }

    /**
     * @dataProvider multiplicationProvider
     */
    public function testMul(string|int|float $a, string|int|float $b, string $expected): void
    {
        $number = new DecimalNumber($a);
        $result = $number->mul($b);
        $this->assertEquals($expected, $result->getValue());
    }

    public function multiplicationProvider(): array
    {
        return [
            'positive numbers' => [2, 3, '6'],
            'negative numbers' => [-2, -3, '6'],
            'mixed signs' => [2, -3, '-6'],
            'zero multiplication' => [1.5, 0, '0'],
            'decimal multiplication' => [0.1, 0.2, '0.02'],
        ];
    }

    /**
     * @dataProvider divisionProvider
     */
    public function testDiv(string|int|float $a, string|int|float $b, string $expected): void
    {
        $number = new DecimalNumber($a);
        $result = $number->div($b);
        $this->assertEquals($expected, $result->getValue());
    }

    public function divisionProvider(): array
    {
        return [
            'positive numbers' => [6, 2, '3'],
            'negative numbers' => [-6, -2, '3'],
            'mixed signs' => [6, -2, '-3'],
            'decimal division' => [1, 2, '0.5'],
            'recurring decimal' => [1, 3, '0.33333333'],
        ];
    }

    public function testDivisionByZero(): void
    {
        $this->expectException(\DivisionByZeroError::class);
        $number = new DecimalNumber(1);
        $number->div(0);
    }

    /**
     * @dataProvider comparisonProvider
     */
    public function testComparisons(string|int|float $a, string|int|float $b, bool $expectedEquals, bool $expectedGreater, bool $expectedLess): void
    {
        $number = new DecimalNumber($a);
        $this->assertEquals($expectedEquals, $number->equals($b));
        $this->assertEquals($expectedGreater, $number->isGreaterThan($b));
        $this->assertEquals($expectedLess, $number->isLessThan($b));
    }

    public function comparisonProvider(): array
    {
        return [
            'equal numbers' => [1.5, 1.5, true, false, false],
            'greater than' => [2.5, 1.5, false, true, false],
            'less than' => [1.5, 2.5, false, false, true],
            'equal negative numbers' => [-1.5, -1.5, true, false, false],
            'greater than negative' => [2.5, -1.5, false, true, false],
            'greater negative than' => [-0.5, -1.5, false, true, false],
            'less negative than ' => [-2.5, 1.5, false, false, true],
            'less than negative' => [-1.5, -0.5, false, false, true],
            'zero comparison' => [0, 0, true, false, false],
        ];
    }

    /**
     * @dataProvider roundingProvider
     */
    public function testRounding(string|int|float $input, int $precision, string $expectedRound): void
    {
        $number = new DecimalNumber($input);
        $this->assertEquals($expectedRound, $number->round($precision)->getValue());
    }

    public function roundingProvider(): array
    {
        return [
            'positive decimal' => [1.55, 1, '1.6'],
            'negative decimal' => [-1.55, 1, '-1.6'],
            'integer' => [2, 0, '2'],
        ];
    }

    /**
     * @dataProvider floorProvider
     */
    public function testFloor(string|int|float $input, int $scale, string $expected): void
    {
        $number = new DecimalNumber($input);
        $this->assertEquals($expected, $number->floor($scale)->getValue());
    }

    public function floorProvider(): array
    {
        return [
            'positive integer' => [2, 0, '2'],
            'positive decimal' => [1.55, 0, '1'],
            'positive decimal with scale' => [1.55, 1, '1.5'],
            'positive decimal with larger scale' => [1.55, 2, '1.55'],
            'negative decimal' => [-1.55, 0, '-2'],
            'negative decimal with scale' => [-1.55, 1, '-1.6'],
            'negative decimal with larger scale' => [-1.55, 2, '-1.55'],
            'zero' => [0, 0, '0'],
            'zero with scale' => [0, 2, '0'],
            'small decimal' => [0.01234567, 4, '0.0123'],
            'recurring decimal' => [1/3, 2, '0.33'],
        ];
    }

    /**
     * @dataProvider ceilProvider
     */
    public function testCeil(string|int|float $input, int $scale, string $expected): void
    {
        $number = new DecimalNumber($input);
        $this->assertEquals($expected, $number->ceil($scale)->getValue());
    }

    public function ceilProvider(): array
    {
        return [
            'positive integer' => [2, 0, '2'],
            'positive decimal' => [1.55, 0, '2'],
            'positive decimal with scale' => [1.55, 1, '1.6'],
            'positive decimal with larger scale' => [1.55, 2, '1.55'],
            'negative decimal' => [-1.55, 0, '-1'],
            'negative decimal with scale' => [-1.55, 1, '-1.5'],
            'negative decimal with larger scale' => [-1.55, 2, '-1.55'],
            'zero' => [0, 0, '0'],
            'zero with scale' => [0, 2, '0'],
            'small decimal' => [0.01234567, 4, '0.0124'],
            'recurring decimal' => [1/3, 2, '0.34'],
        ];
    }

    public function testFloorWithNegativeScale(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Scale must be a non-negative integer');
        $number = new DecimalNumber(1.55);
        $number->floor(-1);
    }

    public function testCeilWithNegativeScale(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Scale must be a non-negative integer');
        $number = new DecimalNumber(1.55);
        $number->ceil(-1);
    }

    /**
     * @dataProvider absProvider
     */
    public function testAbs(string|int|float $input, string $expected): void
    {
        $number = new DecimalNumber($input);
        $this->assertEquals($expected, $number->abs()->getValue());
    }

    public function absProvider(): array
    {
        return [
            'positive number' => [1.5, '1.5'],
            'negative number' => [-1.5, '1.5'],
            'zero' => [0, '0'],
        ];
    }

    /**
     * @dataProvider floatProvider
     */
    public function testFloat(string|int|float $input, float $expected): void
    {
        $number = new DecimalNumber($input);
        $this->assertEquals($expected, $number->float());
    }

    public function floatProvider(): array
    {
        return [
            'integer' => [123, 123.0],
            'float' => [123.456, 123.456],
            'string' => ['123.456', 123.456],
            'zero' => [0, 0.0],
            'negative' => [-123.456, -123.456],
            'large number' => [12345678999999.0, 12345678999999.0],
            'large precision' => [1.012345678999999, 1.01234568],
            'trailing zeros' => [123.4500, 123.45],
            'zero decimal' => [123.0, 123.0],
            'leading zeros integer' => ['00123', 123.0],
            'leading zeros decimal' => ['00123.456', 123.456],
            'leading zeros after decimal' => ['123.0456', 123.0456],
            'negative with leading zeros' => ['-00123.456', -123.456],
            'zero with leading zeros' => ['00.123', 0.123],
            'negative zero with leading zeros' => ['-00.123', -0.123],
            'multiple leading zeros' => ['000000123', 123.0],
            'zero with multiple leading zeros' => ['000000', 0.0],
            'small decimal' => [0.00000001, 0.00000001],
            'very small decimal' => [0.000000001, 0.0],  // Beyond SCALE
            'recurring decimal' => [1/3, 0.33333333],  // Should be rounded to SCALE
        ];
    }
}
