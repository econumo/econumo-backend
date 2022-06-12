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
    private DatetimeServiceInterface $datetimeService;
    private PayeeRepositoryInterface $payeeRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        DatetimeServiceInterface $datetimeService,
        PayeeRepositoryInterface $payeeRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->datetimeService = $datetimeService;
        $this->payeeRepository = $payeeRepository;
        $this->userRepository = $userRepository;
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
