<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\Account\Assembler\AccountToDtoV1ResultAssembler;
use App\Application\Account\Assembler\FolderToDtoV1ResultAssembler;
use App\Application\Category\Assembler\CategoryToDtoResultAssembler;
use App\Application\Currency\Assembler\CurrencyRateToDtoV1ResultAssembler;
use App\Application\Currency\Assembler\CurrencyToDtoV1ResultAssembler;
use App\Application\Payee\Assembler\PayeeToDtoV1ResultAssembler;
use App\Application\Tag\Assembler\TagToDtoResultAssembler;
use App\Application\Transaction\Assembler\TransactionToDtoResultAssembler;
use App\Application\User\Dto\GetChangesListV1RequestDto;
use App\Application\User\Dto\GetChangesListV1ResultDto;
use App\Domain\Entity\Account;
use App\Domain\Entity\Category;
use App\Domain\Entity\Currency;
use App\Domain\Entity\CurrencyRate;
use App\Domain\Entity\Folder;
use App\Domain\Entity\Payee;
use App\Domain\Entity\Tag;
use App\Domain\Entity\Transaction;
use App\Domain\Entity\ValueObject\Id;

class GetChangesListV1ResultAssembler
{
    private FolderToDtoV1ResultAssembler $folderToDtoV1ResultAssembler;

    private AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler;

    private CategoryToDtoResultAssembler $categoryToDtoResultAssembler;

    private TagToDtoResultAssembler $tagToDtoResultAssembler;

    private PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler;

    private CurrencyToDtoV1ResultAssembler $currencyToDtoV1ResultAssembler;

    private CurrencyRateToDtoV1ResultAssembler $currencyRateToDtoV1ResultAssembler;

    private TransactionToDtoResultAssembler $transactionToDtoResultAssembler;

    public function __construct(
        FolderToDtoV1ResultAssembler $folderToDtoV1ResultAssembler,
        AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler,
        CategoryToDtoResultAssembler $categoryToDtoResultAssembler,
        TagToDtoResultAssembler $tagToDtoResultAssembler,
        PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler,
        CurrencyToDtoV1ResultAssembler $currencyToDtoV1ResultAssembler,
        CurrencyRateToDtoV1ResultAssembler $currencyRateToDtoV1ResultAssembler,
        TransactionToDtoResultAssembler $transactionToDtoResultAssembler
    )
    {
        $this->folderToDtoV1ResultAssembler = $folderToDtoV1ResultAssembler;
        $this->accountToDtoV1ResultAssembler = $accountToDtoV1ResultAssembler;
        $this->categoryToDtoResultAssembler = $categoryToDtoResultAssembler;
        $this->tagToDtoResultAssembler = $tagToDtoResultAssembler;
        $this->payeeToDtoV1ResultAssembler = $payeeToDtoV1ResultAssembler;
        $this->currencyToDtoV1ResultAssembler = $currencyToDtoV1ResultAssembler;
        $this->currencyRateToDtoV1ResultAssembler = $currencyRateToDtoV1ResultAssembler;
        $this->transactionToDtoResultAssembler = $transactionToDtoResultAssembler;
    }

    /**
     * @param GetChangesListV1RequestDto $dto
     * @param Id $userId
     * @param Folder[] $folders
     * @param Account[] $accounts
     * @param Category[] $categories
     * @param Tag[] $tags
     * @param Payee[] $payees
     * @param Currency[] $currencies
     * @param CurrencyRate[] $currencyRates
     * @param Transaction[] $transactions
     * @param array $connections
     * @return GetChangesListV1ResultDto
     */
    public function assemble(
        GetChangesListV1RequestDto $dto,
        Id $userId,
        array $folders,
        array $accounts,
        array $categories,
        array $tags,
        array $payees,
        array $currencies,
        array $currencyRates,
        array $transactions,
        array $connections
    ): GetChangesListV1ResultDto {
        $result = new GetChangesListV1ResultDto();
        $result->folders = [];
        foreach ($folders as $item) {
            $result->folders[] = $this->folderToDtoV1ResultAssembler->assemble($item);
        }

        $result->accounts = [];
        foreach ($accounts as $item) {
            $result->accounts[] = $this->accountToDtoV1ResultAssembler->assemble($userId, $item);
        }

        $result->categories = [];
        foreach ($categories as $item) {
            $result->categories[] = $this->categoryToDtoResultAssembler->assemble($item);
        }

        $result->tags = [];
        foreach ($tags as $item) {
            $result->tags[] = $this->tagToDtoResultAssembler->assemble($item);
        }

        $result->payees = [];
        foreach ($payees as $item) {
            $result->payees[] = $this->payeeToDtoV1ResultAssembler->assemble($item);
        }

        $result->currencies = [];
        foreach ($currencies as $item) {
            $result->currencies[] = $this->currencyToDtoV1ResultAssembler->assemble($item);
        }

        $result->currencyRates = [];
        foreach ($currencyRates as $item) {
            $result->currencyRates[] = $this->currencyRateToDtoV1ResultAssembler->assemble($item);
        }

        $result->transactions = [];
        foreach ($transactions as $item) {
            $result->transactions[] = $this->transactionToDtoResultAssembler->assemble($userId, $item);
        }

        $result->connections = [];

        return $result;
    }
}
