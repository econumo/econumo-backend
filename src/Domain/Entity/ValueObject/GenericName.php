<?php

declare(strict_types=1);

namespace App\Domain\Entity\ValueObject;

use App\Domain\Exception\DomainException;
use App\Domain\Traits\ValueObjectTrait;
use JsonSerializable;

class GenericName implements ValueObjectInterface, JsonSerializable, NameInterface
{
    use ValueObjectTrait;

    /**
     * @var int
     */
    public const MIN_LENGTH = 3;

    /**
     * @var int
     */
    public const MAX_LENGTH = 18;

    public static function validate($value): void
    {
        if (!is_string($value)) {
            throw new DomainException(sprintf('%s is incorrect', static::class));
        }

        $length = mb_strlen($value);
        if ($length < static::MIN_LENGTH || $length > static::MAX_LENGTH) {
            throw new DomainException(sprintf('%s is incorrect', static::class));
        }
    }
}
