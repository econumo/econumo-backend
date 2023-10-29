<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ResetPlanCest
{
    private string $url = '/api/v1/budget/reset-plan';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'id' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'periodStart' => '2020-01-01 00:00:00',
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
            'id' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'periodStart' => '2020-01-01 00:00:00',
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
            'id' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'periodStart' => '2020-01-01 00:00:00',
        ]);
        $I->seeResponseMatchesJsonType(['data' => [],]);
    }
}
