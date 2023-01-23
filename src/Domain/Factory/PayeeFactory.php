<?php

declare(strict_types=1);


namespace App\Domain\Factory;


use App\Domain\Entity\Payee;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PayeeName;
use App\Domain\Repository\PayeeRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\DatetimeServiceInterface;

class PayeeFactory implements PayeeFactoryInterface
{
    public function __construct(private readonly DatetimeServiceInterface $datetimeService, private readonly PayeeRepositoryInterface $payeeRepository, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function create(Id $userId, PayeeName $name): Payee
    {
        return new Payee(
            $this->payeeRepository->getNextIdentity(),
            $this->userRepository->getReference($userId),
            $name,
            $this->datetimeService->getCurrentDatetime()
        );
    }
}
