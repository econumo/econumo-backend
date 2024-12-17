<?php

declare(strict_types=1);


namespace App\EconumoBundle\Domain\Service;


readonly class EconumoService implements EconumoServiceInterface
{
    public function __construct(
        private string $baseUrl
    ) {
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}
