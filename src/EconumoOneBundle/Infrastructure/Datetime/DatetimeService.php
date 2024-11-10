<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Infrastructure\Datetime;

use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class DatetimeService implements DatetimeServiceInterface
{
    /**
     * @inheritDoc
     */
    public function getCurrentDatetime(): \DateTimeInterface
    {
        return new DateTimeImmutable();
    }

    public function getNextDay(): DateTimeInterface
    {
        $now = new DateTime();
        $now->setTime(0, 0, 0, 0);
        $now->modify('+1 day');
        return DateTimeImmutable::createFromMutable($now);
    }
}
