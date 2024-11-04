<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Datetime;

use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;
use DateTimeImmutable;

class DatetimeService implements DatetimeServiceInterface
{
    /**
     * @inheritDoc
     */
    public function getCurrentDatetime(): \DateTimeInterface
    {
        return new DateTimeImmutable();
    }
}
