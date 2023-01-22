<?php

declare(strict_types=1);

namespace App\Infrastructure\Cron;

use Zenstruck\ScheduleBundle\Schedule;
use Zenstruck\ScheduleBundle\Schedule\ScheduleBuilder;

class UpdateCurrenciesCron implements ScheduleBuilder
{
    public function buildSchedule(Schedule $schedule): void
    {
        $schedule
            ->timezone('UTC')
            ->addCommand('app:update-currencies -q')
            ->description('Update currencies list')
            ->onSingleServer()
            ->weekly();
    }
}
