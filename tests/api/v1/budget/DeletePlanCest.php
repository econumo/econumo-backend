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
        $I->sendPOST($this->url, ['id' => '16c88ac2-b548-4446-9e27-51a28156b299']);
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
        $I->sendPOST($this->url, ['id' => '16c88ac2-b548-4446-9e27-51a28156b299']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn403ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsDany();
        $I->sendPOST($this->url, ['id' => '16c88ac2-b548-4446-9e27-51a28156b299']);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['id' => '16c88ac2-b548-4446-9e27-51a28156b299']);
        $I->seeResponseCodeIs(HttpCode::OK);
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
        $I->sendPOST($this->url, ['id' => 'bceed17e-d492-40be-921a-e7fa6f663fa6']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseMatchesJsonType([
            'data' => [],
        ]);
    }
}
