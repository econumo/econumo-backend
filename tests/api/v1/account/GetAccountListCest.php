<?php

declare(strict_types=1);

namespace App\Tests\api\v1\account;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class GetAccountListCest
{
    private string $url = '/api/v1/account/get-account-list';

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
        $I->amAuthenticatedAsJohn();
        $I->sendGET($this->url);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendGET($this->url, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendGET($this->url);
        $I->seeResponseMatchesJsonType($I->getRootResponseWithItemsJsonType());
        $I->seeResponseMatchesJsonType($I->getAccountDtoJsonType(), '$.data.items[1]');
        $I->seeResponseMatchesJsonType($I->getSharedAccessDtoJsonType(), '$.data.items[1].sharedAccess[0]');
    }
}
