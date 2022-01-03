<?php

declare(strict_types=1);


namespace App\Domain\Service;


use App\Domain\Entity\Payee;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\PayeeAlreadyExistsException;
use App\Domain\Factory\PayeeFactoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\PayeeRepositoryInterface;

class PayeeService implements PayeeServiceInterface
{
    private PayeeFactoryInterface $payeeFactory;
    private PayeeRepositoryInterface $payeeRepository;
    private AccountRepositoryInterface $accountRepository;

    public function __construct(PayeeFactoryInterface $payeeFactory, PayeeRepositoryInterface $payeeRepository, AccountRepositoryInterface $accountRepository)
    {
        $this->payeeFactory = $payeeFactory;
        $this->payeeRepository = $payeeRepository;
        $this->accountRepository = $accountRepository;
    }

    public function createPayee(Id $userId, string $name): Payee
    {
        $payees = $this->payeeRepository->findByUserId($userId);
        foreach ($payees as $payee) {
            if (strcasecmp($payee->getName(), $name) === 0) {
                throw new PayeeAlreadyExistsException();
            }
        }

        $payee = $this->payeeFactory->create($userId, $name);
        $this->payeeRepository->save($payee);

        return $payee;
    }

    public function createPayeeForAccount(Id $userId, Id $accountId, string $name): Payee
    {
        $account = $this->accountRepository->get($accountId);
        if ($userId->isEqual($account->getUserId())) {
            return $this->createPayee($userId, $name);
        }

        return $this->createPayee($account->getUserId(), $name);
    }

    public function updatePayee(Id $payeeId, string $name, bool $isArchived): void
    {
        $payee = $this->payeeRepository->get($payeeId);
        $userPayees = $this->payeeRepository->findByUserId($payee->getUserId());
        foreach ($userPayees as $userPayee) {
            if (strcasecmp($userPayee->getName(), $name) === 0 && !$userPayee->getId()->isEqual($payeeId)) {
                throw new PayeeAlreadyExistsException();
            }
        }

        $payee->updateName($name);
        if ($isArchived) {
            $payee->archive();
        } else {
            $payee->unarchive();
        }
        $this->payeeRepository->save($payee);
    }

    public function deletePayee(Id $payeeId): void
    {
        $payee = $this->payeeRepository->get($payeeId);
        $this->payeeRepository->delete($payee);
    }

    public function orderPayees(Id $userId, Id ...$ids): void
    {
        $payees = $this->payeeRepository->findByUserId($userId);
        $position = 0;
        $changed = [];
        foreach ($ids as $id) {
            foreach ($payees as $payee) {
                if ($payee->getId()->isEqual($id)) {
                    $payee->updatePosition($position++);
                    $changed[] = $payee;
                    break;
                }
            }
        }

        $this->payeeRepository->save(...$changed);
    }
}
