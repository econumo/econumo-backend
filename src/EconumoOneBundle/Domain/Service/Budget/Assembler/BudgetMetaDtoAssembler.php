<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Domain\Service\Budget\Assembler;

use App\EconumoOneBundle\Domain\Entity\Budget;
use App\EconumoOneBundle\Domain\Entity\BudgetAccess;
use App\EconumoOneBundle\Domain\Entity\ValueObject\UserName;
use App\EconumoOneBundle\Domain\Entity\ValueObject\UserRole;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetMetaDto;
use App\EconumoOneBundle\Domain\Service\Budget\Dto\BudgetUserAccessDto;

readonly class BudgetMetaDtoAssembler
{
    public function __construct()
    {
    }

    public function assemble(Budget $budget): BudgetMetaDto
    {
        /** @var BudgetUserAccessDto[] $accessList */
        $accessList = [];
        foreach ($budget->getAccessList() as $access) {
            $dto = new BudgetUserAccessDto(
                $access->getUserId(),
                new UserName($access->getUser()->getName()),
                $access->getUser()->getAvatarUrl(),
                $access->getRole(),
                $access->isAccepted()
            );
            $accessList[] = $dto;
        }
        $owner = $budget->getUser();
        $accessList[] = new BudgetUserAccessDto(
            $owner->getId(),
            new UserName($owner->getName()),
            $owner->getAvatarUrl(),
            UserRole::owner(),
            true
        );

        return new BudgetMetaDto(
            $budget->getId(),
            $budget->getUser()->getId(),
            $budget->getName(),
            $budget->getStartedAt(),
            $budget->getCurrencyId(),
            $accessList
        );
    }
}