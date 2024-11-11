<?php
declare(strict_types=1);

namespace App\EconumoBundle\Domain\Service;

use App\EconumoBundle\Domain\Entity\User;
use App\EconumoBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoBundle\Domain\Entity\ValueObject\Email;
use App\EconumoBundle\Domain\Entity\ValueObject\Id;
use App\EconumoBundle\Domain\Entity\ValueObject\ReportPeriod;
use App\EconumoBundle\Domain\Exception\UserRegistrationDisabledException;

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

    public function updateBudget(Id $userId, ?Id $budgetId): void;
}
