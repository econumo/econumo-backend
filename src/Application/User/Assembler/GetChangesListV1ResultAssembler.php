<?php

declare(strict_types=1);

namespace App\Application\User\Assembler;

use App\Application\Account\Assembler\AccountToDtoV1ResultAssembler;
use App\Application\Account\Assembler\FolderToDtoV1ResultAssembler;
use App\Application\Category\Assembler\UserCategoryToDtoResultAssembler;
use App\Application\Currency\Assembler\CurrencyRateToDtoV1ResultAssembler;
use App\Application\Currency\Assembler\CurrencyToDtoV1ResultAssembler;
use App\Application\Payee\Assembler\PayeeToDtoV1ResultAssembler;
use App\Application\Tag\Assembler\TagToUserTagDtoResultAssembler;
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
    public function __construct(private readonly FolderToDtoV1ResultAssembler $folderToDtoV1ResultAssembler, private readonly AccountToDtoV1ResultAssembler $accountToDtoV1ResultAssembler, private readonly UserCategoryToDtoResultAssembler $categoryToDtoResultAssembler, private readonly TagToUserTagDtoResultAssembler $tagToDtoResultAssembler, private readonly PayeeToDtoV1ResultAssembler $payeeToDtoV1ResultAssembler, private readonly CurrencyToDtoV1ResultAssembler $currencyToDtoV1ResultAssembler, private readonly CurrencyRateToDtoV1ResultAssembler $currencyRateToDtoV1ResultAssembler, private readonly TransactionToDtoResultAssembler $transactionToDtoResultAssembler)
    {
    }

    /**
     * @param GetChangesListV1RequestDto $dto
     * @param Folder[] $folders
     * @param Account[] $accounts
     * @param Category[] $categories
     * @param Tag[] $tags
     * @param Payee[] $payees
     * @param Currency[] $currencies
     * @param CurrencyRate[] $currencyRates
     * @param Transaction[] $transactions
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
