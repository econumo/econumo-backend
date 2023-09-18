<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class GrantAccessCest
{
    private string $url = '/api/v1/budget/grant-access';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['planId' => '229f97a8-e9c9-4d45-8405-91b7f315f014', 'userId' => '77be9577-147b-4f05-9aa7-91d9b159de5b', 'role' => 'user']);
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
        $I->sendPOST($this->url, ['planId' => '229f97a8-e9c9-4d45-8405-91b7f315f014', 'userId' => '77be9577-147b-4f05-9aa7-91d9b159de5b', 'role' => 'user']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['planId' => '229f97a8-e9c9-4d45-8405-91b7f315f014', 'userId' => '77be9577-147b-4f05-9aa7-91d9b159de5b', 'role' => 'user']);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'item' => $I->getPlanDtoJsonType(),
            ],
        ]);
        $I->seeResponseMatchesJsonType($I->getPlanSharedAccessDtoJsonType(), '$.data.item.sharedAccess[0]');
    }
}
