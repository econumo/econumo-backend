<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\ValueObject\Id;

interface AccountAccessServiceInterface
{
    public function isAccessAllowed(Id $userId, Id $accountId): bool;
    public function canDeleteAccount(Id $userId, Id $accountId): bool;
    public function canUpdateAccount(Id $userId, Id $accountId): bool;
    public function canViewTransactions(Id $userId, Id $accountId): bool;
    public function canAddTransaction(Id $userId, Id $accountId): bool;
    public function canUpdateTransaction(Id $userId, Id $accountId): bool;
    public function canDeleteTransaction(Id $userId, Id $accountId): bool;
    public function canGenerateInvite(Id $userId, Id $accountId): bool;
    public function checkGenerateInviteAccess(Id $userId, Id $accountId): void;
}
