<?php

declare(strict_types=1);

namespace App\Tests\api\v1\account;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class GetInviteListCest
{
    private string $url = '/api/v1/account/get-invite-list';

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
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendGET($this->url);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
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
                'accepted' => 'array',
                'waiting' => 'array',
            ],
        ]);
        $I->seeResponseMatchesJsonType([
            'accountId' => 'string',
            'code' => 'string|null',
            'role' => 'string',
            'recipientUsername' => 'string',
            'recipientName' => 'string',
        ], '$.data.accepted[0]');
        $I->seeResponseMatchesJsonType([
            'accountId' => 'string',
            'code' => 'string|null',
            'role' => 'string',
            'recipientUsername' => 'string',
            'recipientName' => 'string',
        ], '$.data.waiting[0]');
    }
}
