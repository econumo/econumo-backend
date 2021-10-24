<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Entity\RequestId;
use App\Domain\Entity\ValueObject\Id;

interface RequestIdLockServiceInterface
{
    public function register(Id $id): RequestId;

    public function update(RequestId $requestId, Id $id): void;

    public function remove(RequestId $requestId): void;
}
