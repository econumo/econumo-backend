<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service;

use DateTimeInterface;

interface DatetimeServiceInterface
{
    public function getCurrentDatetime(): DateTimeInterface;
}
