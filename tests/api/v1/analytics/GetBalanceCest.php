<?php

declare(strict_types=1);

namespace App\Tests\api\v1\analytics;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class GetBalanceCest
{
    private string $url = '/api/v1/analytics/get-balance';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendGET($this->url, ['from' => '2021-01-01', 'to' => '2021-02-01']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendGET($this->url, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendGET($this->url, ['from' => '2021-01-01', 'to' => '2021-02-01']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendGET($this->url, ['from' => '2021-01-01', 'to' => '2021-02-01']);
        $I->seeResponseMatchesJsonType($I->getRootResponseWithItemsJsonType());
        $I->seeResponseMatchesJsonType($I->getBalanceAnalyticsDtoJsonType(), '$.data.items[0]');
    }
}
