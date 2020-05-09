<?php
declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\GetListDisplayDto;
use App\Application\Budget\Dto\GetListItemDisplayDto;
use App\Domain\Entity\Budget;

class GetListDisplayAssembler
{
    /**
     * @param Budget[] $budgets
     * @return GetListDisplayDto
     */
    public function assemble(array $budgets): GetListDisplayDto
    {
        $dto = new GetListDisplayDto();
        $dto->items = [];
        foreach ($budgets as $budget) {
            $item = new GetListItemDisplayDto();
            $item->id = $budget->getId();
            $item->name = $budget->getName();
            $item->position = $budget->getPosition();
            $item->currencyId = $budget->getCurrencyId();
            $dto->items[] = $item;
        }

        return $dto;
    }
}
