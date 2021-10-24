<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\Exception\ValidationException;
use App\Application\RequestIdLockServiceInterface;
use App\Domain\Entity\RequestId;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;
use App\Infrastructure\Doctrine\Factory\RequestIdFactory;
use App\Infrastructure\Doctrine\Repository\RequestIdRepository;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\FlockStore;

class RequestIdLockService implements RequestIdLockServiceInterface
{
    private RequestIdRepository $requestIdRepository;
    private RequestIdFactory $requestIdFactory;
    private LockFactory $lockFactory;

    public function __construct(RequestIdRepository $requestIdRepository, RequestIdFactory $requestIdFactory)
    {
        $this->requestIdRepository = $requestIdRepository;
        $this->requestIdFactory = $requestIdFactory;
        $this->lockFactory = new LockFactory(new FlockStore());
    }

    public function register(Id $id): RequestId
    {
        $lock = $this->lockFactory->createLock($id->getValue(), 10);
        if ($lock->acquire()) {
            try {
                $this->requestIdRepository->get($id);
                $lock->release();
            } catch (NotFoundException $exception) {
                $requestId = $this->requestIdFactory->create($id);
                $this->requestIdRepository->save($requestId);
                $lock->release();
                return $requestId;
            }
        }

        throw new ValidationException('Operation locked');
    }

    public function update(RequestId $requestId, Id $id): void
    {
        $requestId->updateInternal($id);
        $this->requestIdRepository->save($requestId);
    }

    public function remove(RequestId $requestId): void
    {
        $this->requestIdRepository->remove($requestId);
    }
}
