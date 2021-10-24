<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Factory;

use App\Domain\Entity\RequestId;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\DatetimeServiceInterface;
use App\Infrastructure\Doctrine\Repository\RequestIdRepository;

class RequestIdFactory
{
    private RequestIdRepository $requestIdRepository;
    private DatetimeServiceInterface $datetimeService;

    public function __construct(DatetimeServiceInterface $datetimeService)
    {
        $this->datetimeService = $datetimeService;
    }

    public function create(Id $id): RequestId
    {
        return new RequestId($id, $this->datetimeService->getCurrentDatetime());
    }
}
