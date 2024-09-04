<?php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Entity\ValueObject\CurrencyCode;
use App\Domain\Entity\ValueObject\Email;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\ReportPeriod;
use App\Domain\Exception\UserRegistrationDisabledException;

interface UserServiceInterface
{
    /**
     * @param Email $email
     * @param string $password
     * @param string $name
     * @return User
     * @throws UserRegistrationDisabledException
     */
    public function register(Email $email, string $password, string $name): User;

    public function updateName(Id $userId, string $name): void;

    public function updateCurrency(Id $userId, CurrencyCode $currencyCode): void;

    public function updateReportPeriod(Id $userId, ReportPeriod $reportPeriod): void;

    public function updateDefaultBudget(Id $userId, Id $budgetId): void;
}
