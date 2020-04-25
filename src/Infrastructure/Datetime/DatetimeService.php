<?php

declare(strict_types=1);

namespace App\Infrastructure\Datetime;

use App\Domain\Service\Datetime\DatetimeServiceInterface;

class DatetimeService implements DatetimeServiceInterface
{
    /**
     * @inheritDoc
     */
    public function getCurrentDatetime(): \DateTimeInterface
    {
        return new \DateTimeImmutable();
    }

}
