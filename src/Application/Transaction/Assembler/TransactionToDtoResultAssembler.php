<?php

declare(strict_types=1);

namespace App\Application\Transaction\Assembler;

use App\Application\Category\Assembler\CategoryIdToDtoResultAssembler;
use App\Application\Payee\Assembler\PayeeIdToDtoV1ResultAssembler;
use App\Application\Tag\Assembler\TagIdToDtoResultAssembler;
use App\Application\Transaction\Dto\TransactionResultDto;
use App\Application\User\Assembler\UserIdToDtoResultAssembler;
use App\Domain\Entity\Transaction;
use App\Domain\Exception\NotFoundException;

class TransactionToDtoResultAssembler
{
    private UserIdToDtoResultAssembler $userIdToDtoResultAssembler;
    private CategoryIdToDtoResultAssembler $categoryIdToDtoResultAssembler;
    private PayeeIdToDtoV1ResultAssembler $payeeIdToDtoV1ResultAssembler;
    private TagIdToDtoResultAssembler $tagIdToDtoResultAssembler;

    public function __construct(
        UserIdToDtoResultAssembler $userIdToDtoResultAssembler,
        CategoryIdToDtoResultAssembler $categoryIdToDtoResultAssembler,
        PayeeIdToDtoV1ResultAssembler $payeeIdToDtoV1ResultAssembler,
        TagIdToDtoResultAssembler $tagIdToDtoResultAssembler
    ) {
        $this->userIdToDtoResultAssembler = $userIdToDtoResultAssembler;
        $this->categoryIdToDtoResultAssembler = $categoryIdToDtoResultAssembler;
        $this->payeeIdToDtoV1ResultAssembler = $payeeIdToDtoV1ResultAssembler;
        $this->tagIdToDtoResultAssembler = $tagIdToDtoResultAssembler;
    }

    public function assemble(
        Transaction $transaction
    ): TransactionResultDto {
        $item = new TransactionResultDto();
        $item->id = $transaction->getId()->getValue();
        $item->author = $this->userIdToDtoResultAssembler->assemble($transaction->getUserId());
        $item->type = $transaction->getType()->getAlias();
        $item->accountId = $transaction->getAccountId()->getValue();
        $item->accountRecipientId = $transaction->getAccountRecipientId(
        ) === null ? null : $transaction->getAccountRecipientId()->getValue();
        $item->amount = $transaction->getAmount();
        $item->amountRecipient = $transaction->getAmount();
        $item->category = null;
        if ($transaction->getCategoryId() !== null) {
            try {
                $item->category = $this->categoryIdToDtoResultAssembler->assemble($transaction->getCategoryId());
            } catch (NotFoundException $e) {
            }
        }
        $item->description = $transaction->getDescription();
        $item->payee = null;
        if ($transaction->getPayeeId()) {
            try {
                $item->payee = $this->payeeIdToDtoV1ResultAssembler->assemble($transaction->getPayeeId());
            } catch (NotFoundException $e) {
            }
        }
        $item->tag = null;
        if ($transaction->getTagId()) {
            try {
                $item->tag = $this->tagIdToDtoResultAssembler->assemble($transaction->getTagId());
            } catch (NotFoundException $e) {
            }
        }
        $item->date = $transaction->getSpentAt()->format('Y-m-d H:i:s');

        return $item;
    }
}
