<?php

declare(strict_types=1);

namespace App\Domain\Entity\ValueObject;

use App\Domain\Exception\DomainException;
use App\Domain\Traits\ValueObjectTrait;
use JsonSerializable;

class ReportPeriod implements ValueObjectInterface, JsonSerializable
{
    use ValueObjectTrait;

    /**
     * @var string
     */
    public const MONTHLY = 'monthly';

    /**
     * @var array<string, int>
     */
    private const OPTIONS = [
        self::MONTHLY
    ];

    public static function validate($value): void
    {
        if (!in_array($value, self::OPTIONS, true)) {
            throw new DomainException('ReportPeriod is incorrect');
        }
    }
}
