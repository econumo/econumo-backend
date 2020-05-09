<?php
declare(strict_types=1);

namespace App\Application\Budget;

use App\Application\Budget\Assembler\GetItemDisplayAssembler;
use App\Application\Budget\Assembler\GetListDisplayAssembler;
use App\Application\Budget\Dto\GetItemDisplayDto;
use App\Application\Budget\Dto\GetListDisplayDto;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Repository\BudgetDataRepositoryInterface;
use App\Domain\Repository\BudgetRepositoryInterface;
use DateTimeImmutable;

class BudgetService
{
    /**
     * @var GetListDisplayAssembler
     */
    private $getListDisplayAssembler;
    /**
     * @var BudgetRepositoryInterface
     */
    private $budgetRepository;
    /**
     * @var GetItemDisplayAssembler
     */
    private $getItemDisplayAssembler;
    /**
     * @var BudgetDataRepositoryInterface
     */
    private $budgetDataRepository;

    public function __construct(
        GetListDisplayAssembler $getListDisplayAssembler,
        BudgetRepositoryInterface $budgetRepository,
        GetItemDisplayAssembler $getItemDisplayAssembler,
        BudgetDataRepositoryInterface $budgetDataRepository
    ) {
        $this->getListDisplayAssembler = $getListDisplayAssembler;
        $this->budgetRepository = $budgetRepository;
        $this->getItemDisplayAssembler = $getItemDisplayAssembler;
        $this->budgetDataRepository = $budgetDataRepository;
    }

    public function getList(Id $userId): GetListDisplayDto
    {
        $budgets = $this->budgetRepository->findByUserId($userId);

        return $this->getListDisplayAssembler->assemble($budgets);
    }

    public function getItem(string $id, string $from, string $to): GetItemDisplayDto
    {
        $budget = $this->budgetRepository->find(new Id($id));
        $tmpFromDate = DateTimeImmutable::createFromFormat('Y-m-d', $from);
        $fromDate = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $tmpFromDate->format('Y-m-01 00:00:00'));
        $tmpToDate = DateTimeImmutable::createFromFormat('Y-m-d', $to);
        $toDate = DateTimeImmutable::createFromFormat('Y-n-d H:i:s', $tmpToDate->format('Y-m-t 23:59:59'));

        $data = $this->budgetDataRepository->findByBudgetId($budget->getId(), $fromDate, $toDate,);

        return $this->getItemDisplayAssembler->assemble($budget, $data, $fromDate, $toDate);
    }
}
