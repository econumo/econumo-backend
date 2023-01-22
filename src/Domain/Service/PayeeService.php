<?php

declare(strict_types=1);


namespace App\Domain\Service;


use App\Domain\Entity\Payee;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PayeeName;
use App\Domain\Exception\PayeeAlreadyExistsException;
use App\Domain\Factory\PayeeFactoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\PayeeRepositoryInterface;
use App\Domain\Service\Dto\PositionDto;
use DateTimeInterface;

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

    public function createPayee(Id $userId, PayeeName $name): Payee
    {
        $payees = $this->payeeRepository->findByOwnerId($userId);
        foreach ($payees as $payee) {
            if ($payee->getName()->isEqual($name)) {
                throw new PayeeAlreadyExistsException();
            }
        }

        $payee = $this->payeeFactory->create($userId, $name);
        $payee->updatePosition(count($payees));

        $this->payeeRepository->save($payee);

        return $payee;
    }

    public function createPayeeForAccount(Id $userId, Id $accountId, PayeeName $name): Payee
    {
        $account = $this->accountRepository->get($accountId);
        if ($userId->isEqual($account->getUserId())) {
            return $this->createPayee($userId, $name);
        }

        return $this->createPayee($account->getUserId(), $name);
    }

    public function updatePayee(Id $payeeId, PayeeName $name): void
    {
        $payee = $this->payeeRepository->get($payeeId);
        $userPayees = $this->payeeRepository->findByOwnerId($payee->getUserId());
        foreach ($userPayees as $userPayee) {
            if ($userPayee->getName()->isEqual($name) && !$userPayee->getId()->isEqual($payeeId)) {
                throw new PayeeAlreadyExistsException();
            }
        }

        $payee->updateName($name);
        $this->payeeRepository->save($payee);
    }

    public function deletePayee(Id $payeeId): void
    {
        $payee = $this->payeeRepository->get($payeeId);
        $this->payeeRepository->delete($payee);
    }

    public function orderPayees(Id $userId, PositionDto ...$changes): void
    {
        $payees = $this->payeeRepository->findAvailableForUserId($userId);
        $changed = [];
        foreach ($payees as $payee) {
            foreach ($changes as $change) {
                if ($payee->getId()->isEqual($change->getId())) {
                    $payee->updatePosition($change->position);
                    $changed[] = $payee;
                    break;
                }
            }
        }

        if ($changed === []) {
            return;
        }

        $this->payeeRepository->save($changed);
    }

    public function archivePayee(Id $payeeId): void
    {
        $payee = $this->payeeRepository->get($payeeId);
        $payee->archive();

        $this->payeeRepository->save($payee);
    }

    public function unarchivePayee(Id $payeeId): void
    {
        $payee = $this->payeeRepository->get($payeeId);
        $payee->unarchive();

        $this->payeeRepository->save($payee);
    }

    public function getChanged(Id $userId, DateTimeInterface $lastUpdate): array
    {
        $payees = $this->payeeRepository->findAvailableForUserId($userId);
        $result = [];
        foreach ($payees as $payee) {
            if ($payee->getUpdatedAt() > $lastUpdate) {
                $result[] = $payee;
            }
        }

        return $result;
    }
}
