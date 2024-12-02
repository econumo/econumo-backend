<?php

declare(strict_types=1);

namespace App\EconumoFamilyBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EconumoFamilyBundle extends Bundle
{
    public function isActive(): bool
    {
        return !file_exists(__DIR__.'/.disabled');
    }
}
