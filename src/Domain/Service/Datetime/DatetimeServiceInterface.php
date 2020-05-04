<?php

declare(strict_types=1);

namespace App\Domain\Service\Datetime;

interface DatetimeServiceInterface
{
    /**
     * @return \DateTimeInterface
     */
    public function getCurrentDatetime(): \DateTimeInterface;
}
