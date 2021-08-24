<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\Payee;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\DatetimeServiceInterface;

class PayeeFactory implements PayeeFactoryInterface
{
    private DatetimeServiceInterface $datetimeService;

    public function __construct(DatetimeServiceInterface $datetimeService)
    {
        $this->datetimeService = $datetimeService;
    }

    public function create(Id $userId, Id $payeeId, string $name): Payee
    {
        return new Payee($payeeId, $userId, $name, $this->datetimeService->getCurrentDatetime());
    }
}
