<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Service;


interface EncodeServiceInterface
{
    public function hash(string $value): string;

    public function encode(string $value): string;

    public function decode(string $value): ?string;
}
