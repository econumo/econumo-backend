<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class RevokeAccessCest
{
    private string $url = '/api/v1/budget/revoke-access';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['planId' => '3a6d84be-d074-4a14-ab9a-86dfb083c91d', 'userId' => '77be9577-147b-4f05-9aa7-91d9b159de5b']);
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
        $I->sendPOST($this->url, ['planId' => '3a6d84be-d074-4a14-ab9a-86dfb083c91d', 'userId' => '77be9577-147b-4f05-9aa7-91d9b159de5b']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn400ResponseCodeWhenRevokeOwnerAccess(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['planId' => '3a6d84be-d074-4a14-ab9a-86dfb083c91d', 'userId' => 'aff21334-96f0-4fb1-84d8-0223d0280954']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn403ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsSansa();
        $I->sendPOST($this->url, ['planId' => '3a6d84be-d074-4a14-ab9a-86dfb083c91d', 'userId' => '77be9577-147b-4f05-9aa7-91d9b159de5b']);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['planId' => '3a6d84be-d074-4a14-ab9a-86dfb083c91d', 'userId' => '77be9577-147b-4f05-9aa7-91d9b159de5b']);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'item' => $I->getPlanDtoJsonType(),
            ],
        ]);
        $I->seeResponseMatchesJsonType($I->getPlanSharedAccessDtoJsonType(), '$.data.item.sharedAccess[0]');
    }
}
