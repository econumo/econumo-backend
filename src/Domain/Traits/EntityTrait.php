<?php

declare(strict_types=1);


namespace App\Domain\Traits;

trait EntityTrait
{
    public function __toString(): string
    {
        return __CLASS__;
    }
}
