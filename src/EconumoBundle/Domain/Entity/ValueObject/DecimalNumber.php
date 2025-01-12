<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Entity\ValueObject;

use DivisionByZeroError;
use Stringable;
use TypeError;
use UnexpectedValueException;

final class DecimalNumber implements Stringable, ValueObjectInterface
{
    public const SCALE = 8;

    protected string $value;

    public function __construct(string|int|float|self $num = 0)
    {
        if (!$num instanceof self) {
            $this->value = $this->normalize($num);
        } else {
            $this->value = $this->normalize($num->getValue());
        }
    }

    private function normalize(string|int|float $num): string
    {
        self::validate($num);
        
        if (is_int($num)) {
            return (string)$num;
        } elseif (is_float($num)) {
            // Convert float to string with max precision and trim trailing zeros
            $str = number_format($num, self::SCALE, '.', '');
            return $this->cleanNumber($str);
        } else {
            // Handle string input
            return $this->cleanNumber($num);
        }
    }

    private function cleanNumber(string $num): string
    {
        // Handle empty string or zero
        if ($num === '' || $num === '0') {
            return '0';
        }

        $isNegative = str_starts_with($num, '-');
        if ($isNegative) {
            $num = substr($num, 1);
        }

        // Remove leading zeros but keep one if it's a decimal number
        if (str_contains($num, '.')) {
            $parts = explode('.', $num);
            $parts[0] = $parts[0] === '' || $parts[0] === '0' ? '0' : ltrim($parts[0], '0');
            // Limit decimal places to SCALE and trim trailing zeros
            $parts[1] = substr($parts[1], 0, self::SCALE);
            $parts[1] = rtrim($parts[1], '0');
            $num = empty($parts[1]) ? $parts[0] : $parts[0] . '.' . $parts[1];
        } else {
            $num = ltrim($num, '0');
            if ($num === '') {
                $num = '0';
            }
        }

        // Add leading zero for decimal numbers less than 1
        if (str_starts_with($num, '.')) {
            $num = '0' . $num;
        }

        return $isNegative ? '-' . $num : $num;
    }

    private function createNumber(string|int|float|self $num): self
    {
        if ($num instanceof self) {
            return new self($num->value);
        }
        return new self($num);
    }

    public function add(self|string|int|float $num): self
    {
        $num = $this->createNumber($num);
        return new self(bcadd($this->value, $num->value, self::SCALE));
    }

    public function sub(self|string|int|float $num): self
    {
        $num = $this->createNumber($num);
        return new self(bcsub($this->value, $num->value, self::SCALE));
    }

    public function mul(self|string|int|float $num): self
    {
        $num = $this->createNumber($num);
        return new self(bcmul($this->value, $num->value, self::SCALE));
    }

    public function div(self|string|int|float $num): self
    {
        $num = $this->createNumber($num);
        if ($num->isZero()) {
            throw new DivisionByZeroError();
        }
        return new self(bcdiv($this->value, $num->value, self::SCALE));
    }

    public function mod(self|string|int|float $num): self
    {
        $num = $this->createNumber($num);
        return new self(bcmod($this->value, $num->value));
    }

    public function pow(self|string|int|float $exponent): self
    {
        $exponent = $this->createNumber($exponent);
        return new self(bcpow($this->value, $exponent->value, self::SCALE));
    }

    public function sqrt(): self
    {
        return new self(bcsqrt($this->value, self::SCALE));
    }

    public function floor(): self
    {
        $parts = explode('.', $this->value);
        $isNegative = str_starts_with($parts[0], '-');
        $result = $parts[0];
        
        if ($isNegative && isset($parts[1]) && bccomp($parts[1], '0', self::SCALE) !== 0) {
            $result = bcsub($result, '1', 0);
        }
        
        return new self($result);
    }

    public function ceil(): self
    {
        $parts = explode('.', $this->value);
        $isNegative = str_starts_with($parts[0], '-');
        $result = $parts[0];
        
        if (!$isNegative && isset($parts[1]) && bccomp($parts[1], '0', self::SCALE) !== 0) {
            $result = bcadd($result, '1', 0);
        }
        return new self($result);
    }

    public function round(int $precision = 0): self
    {
        $multiplier = bcpow('10', (string)$precision, 0);
        $value = bcmul($this->value, $multiplier, self::SCALE);
        $rounded = round((float)$value);
        $result = bcdiv((string)$rounded, $multiplier, self::SCALE);
        return new self($result);
    }

    private function compare(self|string|int|float $num): int
    {
        $num = $this->createNumber($num);
        return bccomp($this->value, $num->value, self::SCALE);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function __serialize(): array
    {
        return ['value' => $this->value];
    }

    public function __unserialize(array $data): void
    {
        if (!isset($data['value'])) {
            throw new UnexpectedValueException('Invalid serialized data');
        }
        $this->value = $data['value'];
    }

    public static function validate($value): void
    {
        if (!is_numeric($value)) {
            throw new TypeError(sprintf('%s is not a number!', $value));
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isEqual(ValueObjectInterface $valueObject): bool
    {
        if (!$valueObject instanceof self) {
            throw new UnexpectedValueException('Unexpected argument');
        }

        return $this->compare($valueObject) === 0;
    }

    public function isZero(): bool
    {
        return $this->compare('0.00000000') === 0;
    }

    public function equals(self|string|int|float $num): bool
    {
        return $this->compare($num) === 0;
    }

    public function isGreaterThan(self|string|int|float $num): bool
    {
        return $this->compare($num) === 1;
    }

    public function isLessThan(self|string|int|float $num): bool
    {
        return $this->compare($num) === -1;
    }

    public function float(): float
    {
        $num = (float)$this->value;
        return round($num, self::SCALE);
    }

    public function abs(): self
    {
        if (str_starts_with($this->value, '-')) {
            return new self(substr($this->value, 1));
        }

        return $this;
    }
}
