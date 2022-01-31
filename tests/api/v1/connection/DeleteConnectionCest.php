<?php

declare(strict_types=1);

namespace App\Tests\api\v1\connection;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class DeleteConnectionCest
{
    private string $url = '/api/v1/connection/delete-connection';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendPOST($this->url, ['id' => '77be9577-147b-4f05-9aa7-91d9b159de5b']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendPOST($this->url, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendPOST($this->url, ['id' => '77be9577-147b-4f05-9aa7-91d9b159de5b']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendPOST($this->url, ['id' => '77be9577-147b-4f05-9aa7-91d9b159de5b']);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'accounts' => 'array'
            ],
        ]);
        $I->seeResponseMatchesJsonType($I->getAccountDtoJsonType(), '$.data.accounts[0]');
    }

    public function shouldDeleteConnectionAndSharedAccountsForJohn(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['id' => '77be9577-147b-4f05-9aa7-91d9b159de5b']);
        $response = json_decode($I->grabResponse(), true);
        $actualIds = array_column($response['data']['accounts'], 'id');
        sort($actualIds);
        $expectedIds = [
            '6c7b8af8-2f8a-4d6b-855c-ca6ff26952ff',
            '5f3834d1-34e8-4f60-a697-004e63854513',
            '4eec1ee6-1992-4222-b9ab-31ece5eaad5d',
        ];
        sort($expectedIds);
        $I->assertEquals($expectedIds, $actualIds);
    }

    public function shouldDeleteConnectionAndSharedAccountsForDany(ApiTester $I): void
    {
        $I->amAuthenticatedAsDany();
        $I->sendPOST($this->url, ['id' => 'aff21334-96f0-4fb1-84d8-0223d0280954']);
        $response = json_decode($I->grabResponse(), true);
        $actualIds = array_column($response['data']['accounts'], 'id');
        sort($actualIds);
        $expectedIds = [
            'a62c06a0-d2b5-4564-a09b-703912c01481',
            '0aaa0450-564e-411e-8018-7003f6dbeb92'
        ];
        sort($expectedIds);
        $I->assertEquals($expectedIds, $actualIds);
    }
}
