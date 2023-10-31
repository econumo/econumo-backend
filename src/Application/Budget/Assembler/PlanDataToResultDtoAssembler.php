<?php

declare(strict_types=1);

namespace App\Application\Budget\Assembler;

use App\Application\Budget\Dto\PlanDataBalanceResultDto;
use App\Application\Budget\Dto\PlanDataCategoryResultDto;
use App\Application\Budget\Dto\PlanDataCurrencyRateResultDto;
use App\Application\Budget\Dto\PlanDataEnvelopeResultDto;
use App\Application\Budget\Dto\PlanDataExchangeResultDto;
use App\Application\Budget\Dto\PlanDataResultDto;
use App\Application\Budget\Dto\PlanDataTagResultDto;
use App\Domain\Service\Dto\PlanDataDto;

readonly class PlanDataToResultDtoAssembler
{
    /**
     * @param PlanDataDto $item
     * @return PlanDataResultDto
     */
    public function assemble(PlanDataDto $item): PlanDataResultDto {
        $dto = new PlanDataResultDto();
        $dto->periodStart = $item->periodStart->format('Y-m-d H:i:s');
        $dto->periodEnd = $item->periodEnd->format('Y-m-d H:i:s');
        $dto->balances = [];
        foreach ($item->balances as $balance) {
            $balanceDto = new PlanDataBalanceResultDto();
            $balanceDto->currencyId = $balance->currencyId->getValue();
            $balanceDto->startBalance = $balance->startBalance;
            $balanceDto->endBalance = $balance->endBalance;
            $balanceDto->currentBalance = $balance->currentBalance;
            $balanceDto->income = $balance->income;
            $balanceDto->expenses = $balance->expenses;
            $balanceDto->exchanges = $balance->exchanges;
            $dto->balances[] = $balanceDto;
        }

        $dto->exchanges = [];
        foreach ($item->exchanges as $exchange) {
            $exchangeDto = new PlanDataExchangeResultDto();
            $exchangeDto->currencyId = $exchange->currencyId->getValue();
            $exchangeDto->amount = $exchange->amount;
            $exchangeDto->budget = $exchange->budget;
            $dto->exchanges[] = $exchangeDto;
        }

        $dto->currencyRates = [];
        foreach ($item->currencyRates as $currencyRate) {
            $currencyRateDto = new PlanDataCurrencyRateResultDto();
            $currencyRateDto->currencyId = $currencyRate->currencyId->getValue();
            $currencyRateDto->baseCurrencyId = $currencyRate->baseCurrencyId->getValue();
            $currencyRateDto->rate = $currencyRate->rate;
            $currencyRateDto->date = $currencyRate->date->format('Y-m-d H:i:s');
            $dto->currencyRates[] = $currencyRateDto;
        }

        $dto->envelopes = [];
        foreach ($item->envelopes as $envelope) {
            $envelopeDto = new PlanDataEnvelopeResultDto();
            $envelopeDto->id = $envelope->id->getValue();
            $envelopeDto->budget = $envelope->budget;
            $envelopeDto->available = $envelope->available;
            $dto->envelopes[] = $envelopeDto;
        }

        $dto->categories = [];
        foreach ($item->categories as $category) {
            $categoryDto = new PlanDataCategoryResultDto();
            $categoryDto->id = $category->id->getValue();
            $categoryDto->currencyId = $category->currencyId->getValue();
            $categoryDto->amount = $category->amount;
            $dto->categories[] = $categoryDto;
        }

        $dto->tags = [];
        foreach ($item->tags as $tag) {
            $tagDto = new PlanDataTagResultDto();
            $tagDto->id = $tag->id->getValue();
            $tagDto->currencyId = $tag->currencyId->getValue();
            $tagDto->amount = $tag->amount;
            $dto->tags[] = $tagDto;
        }

        return $dto;
    }
}
