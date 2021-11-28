<?php

declare(strict_types=1);

namespace App\Tests\api\v1\account;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class DeleteAccountCest
{
    private string $url = '/api/v1/account/delete-account';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['id' => '6c7b8af8-2f8a-4d6b-855c-ca6ff26952ff']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['id' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn403ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['id' => 'a62c06a0-d2b5-4564-a09b-703912c01481']);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendPOST($this->url, ['id' => '6c7b8af8-2f8a-4d6b-855c-ca6ff26952ff']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'id' => '6c7b8af8-2f8a-4d6b-855c-ca6ff26952ff',
        ]);
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
            'data' => 'array'
        ]);
    }
}
