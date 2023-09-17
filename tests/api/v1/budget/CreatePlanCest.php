<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class CreatePlanCest
{
    private string $url = '/api/v1/budget/create-plan';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['id' => '14f1d804-4bce-44b3-a3ac-a6e9f55f824e', 'name' => 'New plan']);
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
        $I->sendPOST($this->url, ['id' => '295ba0d9-6080-434b-a05f-e1e3a21b60cd', 'name' => 'New plan']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['id' => '77e270f6-16d5-4c38-bd08-b434786f7dd2', 'name' => 'My new plan']);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'item' => $I->getShortPlanDtoJsonType(),
            ],
        ]);
        $I->seeResponseMatchesJsonType($I->getSharedAccessDtoJsonType(), '$.data.item.sharedAccess[0]');
    }
}
