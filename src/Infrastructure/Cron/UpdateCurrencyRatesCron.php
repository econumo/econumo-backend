<?php

declare(strict_types=1);

namespace App\Infrastructure\Cron;

use Zenstruck\ScheduleBundle\Schedule;
use Zenstruck\ScheduleBundle\Schedule\ScheduleBuilder;

class UpdateCurrencyRatesCron implements ScheduleBuilder
{
    public function buildSchedule(Schedule $schedule): void
    {
        $schedule
            ->timezone('UTC')
            ->addCommand('app:update-currency-rates -q')
            ->description('Update currency rates list')
            ->onSingleServer()
            ->daily()
        ;
    }
}
