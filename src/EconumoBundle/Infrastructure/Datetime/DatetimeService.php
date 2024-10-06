<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Datetime;

use App\EconumoBundle\Domain\Service\DatetimeServiceInterface;
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
