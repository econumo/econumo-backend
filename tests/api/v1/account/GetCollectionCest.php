<?php

declare(strict_types=1);

namespace App\Tests\api\v1\account;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class GetCollectionCest
{
    private string $url = '/api/v1/account/get-collection';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendGET($this->url);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendGET($this->url);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendGET($this->url, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendGET($this->url);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'items' => 'array',
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
        ], '$.data.items[3]');
        $I->seeResponseMatchesJsonType([
            'userId' => 'string',
            'userAvatar' => 'string',
            'role' => 'string',
        ], '$.data.items[3].sharedAccess[0]');
    }
}
