<?php

declare(strict_types=1);

namespace App\Domain\Service;

interface MetricServiceInterface
{
    /**
     * Increase metrics value for 1
     * @param string $metric
     */
    public function increment(string $metric): void;

    /**
     * Count metrics value
     * @param string $metric
     * @param int $count
     */
    public function count(string $metric, int $count): void;

    /**
     * Set metrics value
     * @param string $metric
     * @param int $value
     */
    public function gauge(string $metric, int $value): void;
}
