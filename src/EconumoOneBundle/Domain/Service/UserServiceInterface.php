<?php
declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service;

use App\EconumoOneBundle\Domain\Entity\User;
use App\EconumoOneBundle\Domain\Entity\ValueObject\CurrencyCode;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Email;
use App\EconumoOneBundle\Domain\Entity\ValueObject\Id;
use App\EconumoOneBundle\Domain\Entity\ValueObject\ReportPeriod;
use App\EconumoOneBundle\Domain\Exception\UserRegistrationDisabledException;

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
