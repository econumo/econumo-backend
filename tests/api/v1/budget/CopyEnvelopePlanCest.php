<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class CopyEnvelopePlanCest
{
    private string $url = '/api/v1/budget/copy-envelope-plan';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'planId' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'fromPeriod' => '2020-01-01 00:00:00',
            'toPeriod' => '2020-02-01 00:00:00',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendPOST($this->url, [
            'planId' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'fromPeriod' => '2020-01-01 00:00:00',
            'toPeriod' => '2020-02-01 00:00:00',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'planId' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'fromPeriod' => '2020-01-01 00:00:00',
            'toPeriod' => '2020-02-01 00:00:00',
        ]);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'item' => $I->getPlanDataDtoJsonType(),
            ],
        ]);
        $I->seeResponseMatchesJsonType($I->getPlanDataBalanceDtoJsonType(), '$.data.item.balances[0]');
        $I->seeResponseMatchesJsonType($I->getPlanDataExchangeDtoJsonType(), '$.data.item.exchanges[0]');
        $I->seeResponseMatchesJsonType($I->getPlanDataCurrencyRateDtoJsonType(), '$.data.item.currencyRates[0]');
        $I->seeResponseMatchesJsonType($I->getPlanDataEnvelopeDtoJsonType(), '$.data.item.envelopes[0]');
        $I->seeResponseMatchesJsonType($I->getPlanDataCategoryDtoJsonType(), '$.data.item.categories[0]');
        $I->seeResponseMatchesJsonType($I->getPlanDataTagDtoJsonType(), '$.data.item.tags[0]');
    }
}
