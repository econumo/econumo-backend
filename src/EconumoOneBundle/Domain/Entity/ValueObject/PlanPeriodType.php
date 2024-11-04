<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Entity\ValueObject;

use App\EconumoOneBundle\Domain\Exception\DomainException;
use App\EconumoOneBundle\Domain\Traits\ValueObjectTrait;
use JsonSerializable;

final class PlanPeriodType implements JsonSerializable, \Stringable
{
    use ValueObjectTrait;

    /**
     * @var int
     */
    final public const MONTHLY = 'month';

    private const MAPPING = [self::MONTHLY,];

    public static function validate($value): void
    {
        if (!in_array($value, self::MAPPING, true)) {
            throw new DomainException('PlanPeriodType name is incorrect');
        }
    }
}
