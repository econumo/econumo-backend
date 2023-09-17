<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class DeletePlanCest
{
    private string $url = '/api/v1/budget/delete-plan';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['id' => '05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9']);
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
        $I->sendPOST($this->url, ['id' => '05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn403ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsDany();
        $I->sendPOST($this->url, ['id' => '05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9']);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['id' => '05c8f3e1-d77f-4b37-b2ca-0fc5f0f0c7a9']);
        $I->seeResponseMatchesJsonType([
            'data' => [],
        ]);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure2(ApiTester $I): void
    {
        $I->amAuthenticatedAsDany();
        $I->sendPOST($this->url, ['id' => '229f97a8-e9c9-4d45-8405-91b7f315f014']);
        $I->seeResponseMatchesJsonType([
            'data' => [],
        ]);
    }
}
