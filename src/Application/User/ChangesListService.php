<?php

declare(strict_types=1);

namespace App\Application\User;

use App\Application\User\Dto\GetChangesListV1RequestDto;
use App\Application\User\Dto\GetChangesListV1ResultDto;
use App\Application\User\Assembler\GetChangesListV1ResultAssembler;
use App\Domain\Entity\Account;
use App\Domain\Entity\Category;
use App\Domain\Entity\Currency;
use App\Domain\Entity\CurrencyRate;
use App\Domain\Entity\Folder;
use App\Domain\Entity\Payee;
use App\Domain\Entity\Tag;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;
use App\Domain\Service\AccountServiceInterface;
use App\Domain\Service\CategoryServiceInterface;
use App\Domain\Service\Currency\CurrencyRateServiceInterface;
use App\Domain\Service\Currency\CurrencyServiceInterface;
use App\Domain\Service\FolderServiceInterface;
use App\Domain\Service\PayeeServiceInterface;
use App\Domain\Service\TagServiceInterface;
use App\Domain\Service\TransactionServiceInterface;
use DateTimeImmutable;

class ChangesListService
{
    private GetChangesListV1ResultAssembler $getChangesListV1ResultAssembler;
    private FolderServiceInterface $folderService;
    private AccountServiceInterface $accountService;
    private CategoryServiceInterface $categoryService;
    private TagServiceInterface $tagService;
    private PayeeServiceInterface $payeeService;
    private CurrencyServiceInterface $currencyService;
    private CurrencyRateServiceInterface $currencyRateService;
    private TransactionServiceInterface $transactionService;

    public function __construct(
        GetChangesListV1ResultAssembler $getChangesListV1ResultAssembler,
        FolderServiceInterface $folderService,
        AccountServiceInterface $accountService,
        CategoryServiceInterface $categoryService,
        TagServiceInterface $tagService,
        PayeeServiceInterface $payeeService,
        CurrencyServiceInterface $currencyService,
        CurrencyRateServiceInterface $currencyRateService,
        TransactionServiceInterface $transactionService
    ) {
        $this->getChangesListV1ResultAssembler = $getChangesListV1ResultAssembler;
        $this->folderService = $folderService;
        $this->accountService = $accountService;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
        $this->payeeService = $payeeService;
        $this->currencyService = $currencyService;
        $this->currencyRateService = $currencyRateService;
        $this->transactionService = $transactionService;
    }

    public function getChangesList(
        GetChangesListV1RequestDto $dto,
        Id $userId
    ): GetChangesListV1ResultDto {
        /** @var Folder[] $folders */
        $folders = [];
        if ($dto->foldersUpdatedAt) {
            $foldersUpdatedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->foldersUpdatedAt);
            $folders = $this->folderService->getChanged($userId, $foldersUpdatedAt);
        }
        /** @var Account[] $accounts */
        $accounts = [];
        if ($dto->accountsUpdatedAt) {
            $accountsUpdatedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->foldersUpdatedAt);
            $accounts = $this->accountService->getChanged($userId, $accountsUpdatedAt);
        }
        /** @var Category[] $categories */
        $categories = [];
        if ($dto->categoriesUpdatedAt) {
            $categoriesUpdatedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->categoriesUpdatedAt);
            $categories = $this->categoryService->getChanged($userId, $categoriesUpdatedAt);
        }
        /** @var Tag[] $tags */
        $tags = [];
        if ($dto->tagsUpdatedAt) {
            $tagsUpdatedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->tagsUpdatedAt);
            $tags = $this->tagService->getChanged($userId, $tagsUpdatedAt);
        }
        /** @var Payee[] $payees */
        $payees = [];
        if ($dto->payeesUpdatedAt) {
            $payeesUpdatedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->payeesUpdatedAt);
            $payees = $this->payeeService->getChanged($userId, $payeesUpdatedAt);
        }
        /** @var Currency[] $currencies */
        $currencies = [];
        if ($dto->currenciesUpdatedAt) {
            $currenciesUpdatedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->currenciesUpdatedAt);
            $currencies = $this->currencyService->getChanged($currenciesUpdatedAt);
        }
        /** @var CurrencyRate[] $currencyRates */
        $currencyRates = [];
        if ($dto->currencyRatesUpdatedAt) {
            $currencyRatesUpdatedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->currencyRatesUpdatedAt);
            $currencyRates = $this->currencyRateService->getChanged($currencyRatesUpdatedAt);
        }
        /** @var Transaction[] $transactions */
        $transactions = [];
        if ($dto->transactionsUpdatedAt) {
            $transactionsUpdatedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->transactionsUpdatedAt);
            $transactions = $this->transactionService->getChanged($userId, $transactionsUpdatedAt);
        }
        $connections = [];
        if ($dto->connectionsUpdatedAt) {
            $connectionsUpdatedAt = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dto->connectionsUpdatedAt);
            $connections = [];
        }
        return $this->getChangesListV1ResultAssembler->assemble(
            $dto,
            $userId,
            $folders,
            $accounts,
            $categories,
            $tags,
            $payees,
            $currencies,
            $currencyRates,
            $transactions,
            $connections
        );
    }
}
