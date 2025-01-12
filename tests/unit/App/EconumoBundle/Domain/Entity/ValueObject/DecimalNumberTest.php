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
            'integer' => [123, '123.00000000'],
            'float' => [123.456, '123.45600000'],
            'string' => ['123.456', '123.45600000'],
            'zero' => [0, '0.00000000'],
            'negative' => [-123.456, '-123.45600000'],
            'large number' => [12345678999999.0, '12345678999999.00000000'],
            'large precision' => [1.012345678999999, '1.01234568'],
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
            'positive numbers' => [1.5, 2.5, '4.00000000'],
            'negative numbers' => [-1.5, -2.5, '-4.00000000'],
            'mixed signs' => [1.5, -2.5, '-1.00000000'],
            'zero addition' => [1.5, 0, '1.50000000'],
            'precision test' => [0.1, 0.2, '0.30000000'],
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
            'positive numbers' => [5.5, 2.5, '3.00000000'],
            'negative numbers' => [-1.5, -2.5, '1.00000000'],
            'mixed signs' => [1.5, -2.5, '4.00000000'],
            'zero subtraction' => [1.5, 0, '1.50000000'],
            'precision test' => [0.3, 0.1, '0.20000000'],
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
            'positive numbers' => [2, 3, '6.00000000'],
            'negative numbers' => [-2, -3, '6.00000000'],
            'mixed signs' => [2, -3, '-6.00000000'],
            'zero multiplication' => [1.5, 0, '0.00000000'],
            'decimal multiplication' => [0.1, 0.2, '0.02000000'],
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
            'positive numbers' => [6, 2, '3.00000000'],
            'negative numbers' => [-6, -2, '3.00000000'],
            'mixed signs' => [6, -2, '-3.00000000'],
            'decimal division' => [1, 2, '0.50000000'],
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
    public function testRounding(string|int|float $input, int $precision, string $expectedRound, string $expectedFloor, string $expectedCeil): void
    {
        $number = new DecimalNumber($input);
        $this->assertEquals($expectedRound, $number->round($precision)->getValue());
        $this->assertEquals($expectedFloor, $number->floor()->getValue());
        $this->assertEquals($expectedCeil, $number->ceil()->getValue());
    }

    public function roundingProvider(): array
    {
        return [
            'positive decimal' => [1.55, 1, '1.60000000', '1.00000000', '2.00000000'],
            'negative decimal' => [-1.55, 1, '-1.60000000', '-2.00000000', '-1.00000000'],
            'integer' => [2, 0, '2.00000000', '2.00000000', '2.00000000'],
        ];
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
            'positive number' => [1.5, '1.50000000'],
            'negative number' => [-1.5, '1.50000000'],
            'zero' => [0, '0.00000000'],
        ];
    }
}
