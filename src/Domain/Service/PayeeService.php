<?php

declare(strict_types=1);


namespace App\Domain\Service;


use App\Domain\Entity\Payee;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Factory\PayeeFactoryInterface;
use App\Domain\Repository\PayeeRepositoryInterface;

class PayeeService implements PayeeServiceInterface
{
    private PayeeFactoryInterface $payeeFactory;
    private PayeeRepositoryInterface $payeeRepository;

    public function __construct(PayeeFactoryInterface $payeeFactory, PayeeRepositoryInterface $payeeRepository)
    {
        $this->payeeFactory = $payeeFactory;
        $this->payeeRepository = $payeeRepository;
    }

    public function createPayee(Id $userId, Id $payeeId, string $name): Payee
    {
        $payee = $this->payeeFactory->create($userId, $payeeId, $name);
        $this->payeeRepository->save($payee);

        return $payee;
    }
}
