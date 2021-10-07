<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\AccountAccessInvite;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Exception\NotFoundException;

interface AccountAccessInviteRepositoryInterface
{
    public function save(AccountAccessInvite ...$items): void;

    public function get(Id $accountId, Id $recipientId): AccountAccessInvite;

    public function getByUserAndCode(Id $userId, string $code): AccountAccessInvite;

    public function delete(AccountAccessInvite $invite): void;
}
