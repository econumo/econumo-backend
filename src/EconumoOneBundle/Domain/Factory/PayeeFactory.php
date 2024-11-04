<?php

declare(strict_types=1);


namespace App\EconumoOneBundle\Domain\Factory;


use App\EconumoOneBundle\Domain\Entity\Payee;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\PayeeName;
use App\EconumoOneBundle\Domain\Repository\PayeeRepositoryInterface;
use App\EconumoOneBundle\Domain\Repository\UserRepositoryInterface;
use App\EconumoOneBundle\Domain\Service\DatetimeServiceInterface;
use App\EconumoOneBundle\Domain\Factory\PayeeFactoryInterface;

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
