<?php

declare(strict_types=1);

namespace App\Tests\api\v1\account;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class CreateAccountCest
{
    private string $url = '/api/v1/account/create-account';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendPOST($this->url, [
            'id' => '4b7946ca-2a48-4ea3-8645-2960cea6b94f',
            'name' => 'Savings Account',
            'currencyId' => 'fe5d9269-b69c-4841-9c04-136225447eca',
            'balance' => 100.13,
            'icon' => 'savings',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendPOST($this->url, [
            'id' => '',
            'name' => '',
            'currencyId' => '',
            'balance' => 0,
            'icon' => 'no',
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendPOST($this->url, ['id' => 'test']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendPOST($this->url, [
            'id' => '4b7946ca-2a48-4ea3-8645-2960cea6b94f',
            'name' => 'Savings Account',
            'currencyId' => 'fe5d9269-b69c-4841-9c04-136225447eca',
            'balance' => 100.13,
            'icon' => 'savings',
        ]);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'item' => 'array',
            ],
        ]);
        $I->seeResponseMatchesJsonType([
            'id' => 'string',
            'ownerId' => 'string',
            'name' => 'string',
            'position' => 'integer',
            'currencyId' => 'string',
            'currencyAlias' => 'string',
            'currencySign' => 'string',
            'balance' => 'float',
            'type' => 'integer',
            'icon' => 'string',
            'sharedAccess' => 'array',
        ], '$.data.item');
        $data = $I->grabDataFromResponseByJsonPath('$.data.item.sharedAccess[0]');
        $I->assertEmpty($data);
    }
}
