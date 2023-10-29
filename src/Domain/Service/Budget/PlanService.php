<?php

declare(strict_types=1);


namespace App\Domain\Service\Budget;

use App\Domain\Entity\Envelope;
use App\Domain\Entity\Plan;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Entity\ValueObject\PlanFolderName;
use App\Domain\Entity\ValueObject\PlanName;
use App\Domain\Entity\ValueObject\PlanPeriodType;
use App\Domain\Entity\ValueObject\UserRole;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\PlanAlreadyExistsException;
use App\Domain\Exception\RevokeOwnerAccessException;
use App\Domain\Factory\PlanFolderFactoryInterface;
use App\Domain\Factory\PlanAccessFactoryInterface;
use App\Domain\Factory\PlanFactoryInterface;
use App\Domain\Factory\PlanOptionsFactoryInterface;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\CurrencyRepositoryInterface;
use App\Domain\Repository\EnvelopeBudgetRepositoryInterface;
use App\Domain\Repository\EnvelopeRepositoryInterface;
use App\Domain\Repository\PlanAccessRepositoryInterface;
use App\Domain\Repository\PlanFolderRepositoryInterface;
use App\Domain\Repository\PlanOptionsRepositoryInterface;
use App\Domain\Repository\PlanRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Service\AntiCorruptionServiceInterface;
use App\Domain\Service\Currency\CurrencyRateServiceInterface;
use App\Domain\Service\DatetimeServiceInterface;
use App\Domain\Service\Dto\PlanDataCategoryDto;
use App\Domain\Service\Dto\PlanDataCurrencyRateDto;
use App\Domain\Service\Dto\PlanDataDto;
use App\Domain\Service\Dto\PlanDataEnvelopeDto;
use App\Domain\Service\Dto\PlanDataExchangeDto;
use App\Domain\Service\Dto\PlanDataTagDto;
use App\Domain\Service\Dto\PlanDto;
use App\Domain\Service\UserServiceInterface;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Throwable;

use function PHPUnit\Framework\isFinite;

readonly class PlanService implements PlanServiceInterface
{
    public function __construct(
        private AntiCorruptionServiceInterface $antiCorruptionService,
        private PlanFactoryInterface $planFactory,
        private PlanRepositoryInterface $planRepository,
        private PlanOptionsRepositoryInterface $planOptionsRepository,
        private PlanOptionsFactoryInterface $planOptionsFactory,
        private PlanAccessRepositoryInterface $planAccessRepository,
        private UserServiceInterface $userService,
        private UserRepositoryInterface $userRepository,
        private PlanAccessFactoryInterface $planAccessFactory,
        private PlanFolderRepositoryInterface $planFolderRepository,
        private EnvelopeRepositoryInterface $envelopeRepository,
        private AccountRepositoryInterface $accountRepository,
        private PlanFolderFactoryInterface $planFolderFactory,
        private EnvelopeServiceInterface $envelopeService,
        private CurrencyRateServiceInterface $currencyRateService,
        private DatetimeServiceInterface $datetimeService,
        private PlanAccountsService $planAccountsService,
        private CurrencyRepositoryInterface $currencyRepository,
        private PlanBalanceService $planBalanceService,
        private PlanReportService $planReportService,
        private EnvelopeBudgetRepositoryInterface $envelopeBudgetRepository,
    ) {
    }

    public function createPlan(Id $userId, PlanName $name): Plan
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $plans = $this->planRepository->findByOwnerId($userId);
            foreach ($plans as $plan) {
                if ($plan->getName()->isEqual($name)) {
                    throw new PlanAlreadyExistsException();
                }
            }

            $userPlanOptions = $this->planOptionsRepository->getByUserId($userId);
            $position = 0;
            foreach ($userPlanOptions as $option) {
                if ($option->getPosition() > $position) {
                    $position = $option->getPosition();
                }
            }

            if ($position === 0) {
                $position = count($this->planRepository->getAvailableForUserId($userId));
            } else {
                $position++;
            }

            $plan = $this->planFactory->create($userId, $name);
            $this->planRepository->save([$plan]);

            $planOptions = $this->planOptionsFactory->create($plan->getId(), $userId, $position);
            $this->planOptionsRepository->save([$planOptions]);

            $planFolder = $this->planFolderFactory->create($plan->getId(), new PlanFolderName('Expenses'), 0);
            $this->planFolderRepository->save([$planFolder]);

            $envelopePosition = 0;
            $currencyId = $this->getAvailableCurrencyIdsForUserId($userId)[0];
            $this->envelopeService->createEnvelopesForUser(
                $plan->getId(),
                $userId,
                $currencyId,
                $envelopePosition,
                $planFolder->getId()
            );

            $this->userService->updateDefaultPlan($userId, $plan->getId());
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }

        return $plan;
    }

    /**
     * @inheritDoc
     */
    public function orderPlans(Id $userId, array $changes): void
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $plans = $this->planRepository->getAvailableForUserId($userId);
            $changed = [];
            foreach ($plans as $plan) {
                foreach ($changes as $change) {
                    if ($plan->getId()->isEqual($change->getId())) {
                        try {
                            $options = $this->planOptionsRepository->get($plan->getId(), $userId);
                            $options->updatePosition($change->position);
                        } catch (NotFoundException $e) {
                            $options = $this->planOptionsFactory->create($plan->getId(), $userId, $change->position);
                        }
                        $changed[] = $options;
                        break;
                    }
                }
            }

            if ($changed === []) {
                return;
            }

            $this->planOptionsRepository->save($changed);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }
    }

    public function deletePlan(Id $userId, Id $planId): void
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $plan = $this->planRepository->get($planId);

            if ($plan->getOwnerUserId()->isEqual($userId)) {
                $access = $this->planAccessRepository->getByPlanId($planId);
                foreach ($access as $item) {
                    $this->revokeAccess($planId, $item->getUserId());
                }
                $this->planRepository->delete($plan);
                // todo fix
//                $this->updateUserDefaultPlanWhenDeleted($userId, $planId);
            } else {
                $this->revokeAccess($planId, $userId);
                try {
                    $options = $this->planOptionsRepository->get($planId, $userId);
                    $this->planOptionsRepository->delete($options);
                } catch (NotFoundException $e) {
                }
            }
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }
    }

    public function updatePlan(Id $planId, PlanName $name): Plan
    {
        $plan = $this->planRepository->get($planId);
        $plan->updateName($name);
        $this->planRepository->save([$plan]);

        return $plan;
    }

    private function updateUserDefaultPlanWhenDeleted(Id $userId, Id $planId): void
    {
        $user = $this->userRepository->get($userId);
        if (!$user->getDefaultPlanId() || $user->getDefaultPlanId()->isEqual($planId)) {
            $availablePlans = $this->planRepository->getAvailableForUserId($userId);
            $planUpdated = false;
            if (count($availablePlans) > 0) {
                foreach ($availablePlans as $availablePlan) {
                    if ($availablePlan->getId()->isEqual($planId)) {
                        continue;
                    }
                    $user->updateDefaultPlan($availablePlan->getId());
                    $planUpdated = true;
                    break;
                }
            }
            if (!$planUpdated) {
                $user->updateDefaultPlan(null);
            }
            $this->userRepository->save([$user]);
        }
    }

    public function revokeAccess(Id $planId, Id $sharedUserId): void
    {
        $plan = $this->planRepository->get($planId);
        if ($plan->getOwnerUserId()->isEqual($sharedUserId)) {
            throw new RevokeOwnerAccessException();
        }
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $access = $this->planAccessRepository->get($planId, $sharedUserId);
            $this->planAccessRepository->delete($access);
            $this->updateUserDefaultPlanWhenDeleted($sharedUserId, $planId);

            $envelopesToDelete = [];
            $envelopes = $this->envelopeRepository->getByPlanId($planId);
            $changedEnvelopes = [];
            foreach ($envelopes as $envelope) {
                foreach ($envelope->getCategories() as $category) {
                    if ($category->getUserId()->isEqual($sharedUserId)) {
                        if ($envelope->isCategoryConnected()) {
                            $envelopesToDelete[$envelope->getId()->getValue()] = $envelope;
                        }
                        $envelope->removeCategory($category);
                        $changedEnvelopes[$envelope->getId()->getValue()] = $envelope;
                    }
                }
                foreach ($envelope->getTags() as $tag) {
                    if ($tag->getUserId()->isEqual($sharedUserId)) {
                        if ($envelope->isTagConnected()) {
                            $envelopesToDelete[$envelope->getId()->getValue()] = $envelope;
                        }
                        $envelope->removeTag($tag);
                        $changedEnvelopes[$envelope->getId()->getValue()] = $envelope;
                    }
                }
            }
            if (count($changedEnvelopes) > 0) {
                $this->envelopeRepository->save($changedEnvelopes);
            }
            foreach ($envelopesToDelete as $envelope) {
                $this->envelopeRepository->delete($envelope);
            }

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }
    }

    public function grantAccess(Id $planId, Id $sharedUserId, UserRole $role): void
    {
        //
        try {
            $access = $this->planAccessRepository->get($planId, $sharedUserId);
            $access->updateRole($role);
        } catch (NotFoundException) {
            $access = $this->planAccessFactory->create($planId, $sharedUserId, $role);
        }

        $this->planAccessRepository->save([$access]);
    }

    public function acceptAccess(Id $planId, Id $userId): void
    {
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $access = $this->planAccessRepository->get($planId, $userId);
            $access->accept();
            $this->planAccessRepository->save([$access]);
            $this->userService->updateDefaultPlan($userId, $planId);

            $envelopePosition = 0;
            $envelopes = $this->envelopeRepository->getByPlanId($planId);
            if (count($envelopes) > 0) {
                $envelopePosition = $envelopes[count($envelopes) - 1]->getPosition() + 1;
            }

            $folders = $this->planFolderRepository->getByPlanId($planId);
            $folderPosition = 0;
            if (count($folders) > 0) {
                $folderPosition = $folders[count($folders) - 1]->getPosition() + 1;
            }
            $user = $this->userRepository->get($userId);
            $planFolder = $this->planFolderFactory->create(
                $planId,
                new PlanFolderName($user->getName()),
                $folderPosition
            );
            $this->planFolderRepository->save([$planFolder]);
            $currencyId = $this->getAvailableCurrencyIdsForUserId($userId)[0];
            $this->envelopeService->createEnvelopesForUser(
                $planId,
                $userId,
                $currencyId,
                $envelopePosition,
                $planFolder->getId()
            );

            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }
    }

    public function getPlan(Id $planId): PlanDto
    {
        $plan = $this->planRepository->get($planId);
        $dto = new PlanDto();
        $dto->id = $plan->getId();
        $dto->name = $plan->getName();
        $dto->ownerUserId = $plan->getOwnerUserId();
        $dto->startDate = $plan->getStartDate();
        $dto->createdAt = $plan->getCreatedAt();
        $dto->updatedAt = $plan->getUpdatedAt();
        $dto->folders = [];
        foreach ($this->planFolderRepository->getByPlanId($planId) as $item) {
            $dto->folders[] = $item->getId();
        }
        $dto->envelopes = [];
        foreach ($this->envelopeRepository->getByPlanId($planId) as $item) {
            $dto->envelopes[] = $item->getId();
            $dto->categories[$item->getId()->getValue()] = [];
            foreach ($item->getCategories() as $category) {
                $dto->categories[$item->getId()->getValue()][] = $category->getId();
            }
            $dto->tags[$item->getId()->getValue()] = [];
            foreach ($item->getTags() as $tag) {
                $dto->tags[$item->getId()->getValue()][] = $tag->getId();
            }
        }
        $planAccess = $this->planAccessRepository->getByPlanId($planId);
        foreach ($planAccess as $item) {
            $dto->sharedAccess[] = $item->getUserId();
        }
        $dto->currencies = [];
        foreach ($planAccess as $item) {
            if ($item->isAccepted()) {
                foreach ($this->accountRepository->getUserAccounts($item->getUserId()) as $account) {
                    if ($account->isExcludedFromBudget()) {
                        continue;
                    }
                    if (!in_array($account->getCurrencyId(), $dto->currencies)) {
                        $dto->currencies[] = $account->getCurrencyId();
                    }
                }
            }
        }
        foreach ($this->accountRepository->getUserAccounts($plan->getOwnerUserId()) as $account) {
            if ($account->isExcludedFromBudget()) {
                continue;
            }
            if (!in_array($account->getCurrencyId(), $dto->currencies)) {
                $dto->currencies[] = $account->getCurrencyId();
            }
        }

        return $dto;
    }

    /**
     * @param Id $userId
     * @return Id[]
     */
    private function getAvailableCurrencyIdsForUserId(Id $userId): array
    {
        $result = [];
        $accounts = $this->accountRepository->getUserAccounts($userId);
        foreach ($accounts as $account) {
            $result[$account->getCurrencyId()->getValue()] = $account->getCurrencyId();
        }

        if (!count($result)) {
            $user = $this->userRepository->get($userId);
            $userCurrency = $this->currencyRepository->getByCode($user->getCurrency());
            $result[$userCurrency->getId()->getValue()] = $userCurrency->getId();
        }

        return array_values($result);
    }

    public function getPlanData(
        Id $planId,
        PlanPeriodType $periodType,
        DateTimeInterface $periodStart,
        int $numberOfPeriods
    ): array {
        $result = [];
        $rollingPeriodStart = DateTime::createFromInterface($periodStart);
        $rollingPeriodEnd = clone $rollingPeriodStart;
        $rollingPeriodEnd->modify('+1 ' . $periodType->getValue());
        $rollingPeriodEnd->modify('-1 microsecond');
        $currencyIds = $this->planAccountsService->getAvailableCurrencyIdsForPlanId($planId);
        $envelopes = $this->envelopeRepository->getByPlanId($planId);
        $currentDate = $this->datetimeService->getCurrentDatetime();
        $beforeRollingPeriodStart = clone $rollingPeriodStart;
        $beforeRollingPeriodStart->modify('-1 microsecond');
        $envelopesAvailable = $this->envelopeService->getEnvelopesAvailable($planId, $beforeRollingPeriodStart);
        for ($i = 0; $i < $numberOfPeriods; $i++) {
            $dto = new PlanDataDto();
            $dto->periodStart = clone $rollingPeriodStart;
            $dto->periodEnd = clone $rollingPeriodEnd;
            if ($i === 0) {
                $dto->balances = $this->planBalanceService->getBalance($planId, $dto->periodStart, $dto->periodEnd);
            } else {
                $dto->balances = $this->planBalanceService->getBalanceStubs($currencyIds);
            }

            $dto->exchanges = [];
            foreach ($currencyIds as $currencyId) {
                $currencyExchangeDto = new PlanDataExchangeDto();
                $currencyExchangeDto->currencyId = $currencyId;
                // todo fix
                $currencyExchangeDto->budget = 0;
                if ($dto->periodStart <= $currentDate) {
                    $currencyExchangeDto->amount = 0;
                } else {
                    $currencyExchangeDto->amount = 0;
                }
                $dto->exchanges[] = $currencyExchangeDto;
            }

            $dto->currencyRates = [];
            foreach($this->currencyRateService->getAverageCurrencyRates($dto->periodStart, $dto->periodEnd) as $rate) {
                $currencyRateDto = new PlanDataCurrencyRateDto();
                $currencyRateDto->baseCurrencyId = $rate->baseCurrencyId;
                $currencyRateDto->currencyId = $rate->currencyId;
                $currencyRateDto->rate = $rate->rate;
                $currencyRateDto->date = $rate->date;
                $dto->currencyRates[] = $currencyRateDto;
            }

            $dto->envelopes = [];
            $dto->categories = [];
            $dto->tags = [];
            $categoriesReport = $this->planReportService->getCategoriesReport($planId, $dto->periodStart, $dto->periodEnd);
            $tagsReport = $this->planReportService->getTagsReport($planId, $dto->periodStart, $dto->periodEnd);
            $envelopesBudgets = $this->envelopeService->getEnvelopesBudgets($planId, $dto->periodStart);
            foreach ($envelopes as $envelope) {
                $envelopeDto = new PlanDataEnvelopeDto();
                $envelopeDto->id = $envelope->getId();
                $envelopeDto->budget = 0;
                if (isset($envelopesBudgets[$envelope->getId()->getValue()])) {
                    $envelopeDto->budget = $envelopesBudgets[$envelope->getId()->getValue()]->getAmount();
                }

                foreach ($envelope->getCategories() as $category) {
                    $categoryDto = new PlanDataCategoryDto();
                    $categoryDto->id = $category->getId();
                    $categoryDto->currencyId = $envelope->getCurrency()->getId();
                    $categoryDto->amount = $categoriesReport[$category->getId()->getValue()] ?? .0;
                    $dto->categories[] = $categoryDto;
                }

                foreach ($envelope->getTags() as $tag) {
                    $tagDto = new PlanDataTagDto();
                    $tagDto->id = $tag->getId();
                    $tagDto->currencyId = $envelope->getCurrency()->getId();
                    $tagDto->amount = $tagsReport[$tag->getId()->getValue()] ?? .0;
                    $dto->tags[] = $tagDto;
                }

                if ($i === 0) {
                    $envelopeDto->available = $envelopesAvailable[$envelope->getId()->getValue()] ?? 0;
                } else {
                    $envelopeDto->available = null;
                }
                $dto->envelopes[] = $envelopeDto;
            }

            $result[] = $dto;
            $rollingPeriodStart->modify('+1 ' . $periodType->getValue());
            $rollingPeriodEnd = clone $rollingPeriodStart;
            $rollingPeriodEnd->modify('+1 ' . $periodType->getValue());
            $rollingPeriodEnd->modify('-1 microsecond');
        }

        return $result;
    }

    public function resetPlan(Id $planId, DateTimeImmutable $periodStart): void
    {
        $plan = $this->planRepository->get($planId);
        $this->antiCorruptionService->beginTransaction(__METHOD__);
        try {
            $this->envelopeBudgetRepository->deleteByPlanId($plan->getId());
            $plan->updateStartDate($periodStart);
            $this->planRepository->save([$plan]);
            $this->antiCorruptionService->commit(__METHOD__);
        } catch (Throwable $e) {
            $this->antiCorruptionService->rollback(__METHOD__);
            throw $e;
        }
    }
}
