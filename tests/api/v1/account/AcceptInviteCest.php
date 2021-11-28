<?php

declare(strict_types=1);

namespace App\Tests\api\v1\account;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class AcceptInviteCest
{
    private string $url = '/api/v1/account/accept-invite';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsMargo();
        $I->sendPOST($this->url, ['code' => '12345']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendPOST($this->url, ['code' => '12345']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsMargo();
        $I->sendPOST($this->url, ['code' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsMargo();
        $I->sendPOST($this->url, ['code' => '12345']);
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
            'data' => [
                'account' => $I->getAccountDtoJsonType(),
            ],
        ]);
        $I->seeResponseMatchesJsonType($I->getSharedAccessDtoJsonType(), '$.data.account.sharedAccess[0]');
    }
}
