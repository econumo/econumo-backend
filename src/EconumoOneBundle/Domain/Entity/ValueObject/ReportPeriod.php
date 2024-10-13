<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Entity\ValueObject;

use App\EconumoOneBundle\Domain\Entity\ValueObject\ValueObjectInterface;
use App\EconumoOneBundle\Domain\Exception\DomainException;
use App\EconumoOneBundle\Domain\Traits\ValueObjectTrait;
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
