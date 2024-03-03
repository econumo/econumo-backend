<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class GetDataCest
{
    private string $url = '/api/v1/budget/get-data';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $date = new \DateTimeImmutable('-1 month');
        $I->sendGet($this->url, [
            'id' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'periodStart' => $date->format('Y-m-01 00:00:00'),
            'periodType' => 'month',
            'numberOfPeriods' => '2',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendGet($this->url, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendGet($this->url, [
            'id' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'periodStart' => '2020-01-01 00:00:00',
            'periodType' => 'month',
            'numberOfPeriods' => '2',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $date = new \DateTimeImmutable('-1 month');
        $I->sendGet($this->url, [
            'id' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'periodStart' => $date->format('Y-m-01 00:00:00'),
            'periodType' => 'month',
            'numberOfPeriods' => '2',
        ]);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'items' => 'array',
            ],
        ]);
        $I->seeResponseMatchesJsonType($I->getPlanDataDtoJsonType(), '$.data.items[0]');
        $I->seeResponseMatchesJsonType($I->getPlanDataBalanceDtoJsonType(), '$.data.items[0].balances[0]');
        $I->seeResponseMatchesJsonType($I->getPlanDataExchangeDtoJsonType(), '$.data.items[0].exchanges[0]');
        $I->seeResponseMatchesJsonType($I->getPlanDataCurrencyRateDtoJsonType(), '$.data.items[0].currencyRates[0]');
        $I->seeResponseMatchesJsonType($I->getPlanDataEnvelopeDtoJsonType(), '$.data.items[0].envelopes[0]');
        $I->seeResponseMatchesJsonType($I->getPlanDataCategoryDtoJsonType(), '$.data.items[0].categories[0]');
        $I->seeResponseMatchesJsonType($I->getPlanDataTagDtoJsonType(), '$.data.items[0].tags[0]');
    }
}
