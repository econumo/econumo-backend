<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service;

use DateTimeInterface;

interface DatetimeServiceInterface
{
    public function getCurrentDatetime(): DateTimeInterface;
}
