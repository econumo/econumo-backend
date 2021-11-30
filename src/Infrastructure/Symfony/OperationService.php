<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony;

use App\Application\Exception\ValidationException;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Infrastructure\Doctrine\Factory\OperationIdFactory;
use App\Infrastructure\Doctrine\Repository\OperationIdRepository;
use App\Infrastructure\Exception\OperationObjectLockedException;
use App\UI\Service\Dto\OperationDto;
use App\UI\Service\OperationServiceInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\FlockStore;

class OperationService implements OperationServiceInterface
{
    private OperationIdRepository $operationIdRepository;
    private OperationIdFactory $operationIdFactory;
    private LockFactory $lockFactory;

    public function __construct(OperationIdRepository $operationIdRepository, OperationIdFactory $operationIdFactory)
    {
        $this->operationIdRepository = $operationIdRepository;
        $this->operationIdFactory = $operationIdFactory;
        $this->lockFactory = new LockFactory(new FlockStore());
    }

    /**
     * @param Id $id
     * @return bool
     * @throws OperationObjectLockedException
     */
    public function checkIfHandled(Id $id): bool
    {
        try {
            $registeredId = $this->operationIdRepository->get($id);
            if ($registeredId->isHandled()) {
                return true;
            }
        } catch (NotFoundException $exception) {
        }

        $lock = $this->lockFactory->createLock($id->getValue());
        if ($lock->isAcquired()) {
            throw new OperationObjectLockedException();
        }

        return false;
    }

    public function lock(Id $id): OperationDto
    {
        $lock = $this->lockFactory->createLock($id->getValue(), 10);
        if ($lock->acquire()) {
            try {
                $this->operationIdRepository->get($id);
            } catch (NotFoundException $exception) {
                $operationId = $this->operationIdFactory->create($id);
                $this->operationIdRepository->save($operationId);
            }

            $dto = new OperationDto();
            $dto->operationId = $id;
            $dto->lock = $lock;
            return $dto;
        }

        throw new ValidationException('Operation id locked');
    }

    public function release(OperationDto $dto): void
    {
        $dto->lock->release();
        $dto->lock = null;
        $saved = $this->operationIdRepository->get($dto->operationId);
        $saved->markHandled();
        $this->operationIdRepository->save($saved);
    }
}
