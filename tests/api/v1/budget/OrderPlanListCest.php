<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class OrderPlanListCest
{
    private string $url = '/api/v1/budget/order-plan-list';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'changes' => [
                [
                    'id' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
                    'position' => 0
                ],
                [
                    'id' => '16c88ac2-b548-4446-9e27-51a28156b299',
                    'position' => 1
                ],
            ]
        ]);
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
        $I->sendPOST($this->url, [
            'changes' => [
                [
                    'id' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
                    'position' => 0
                ],
                [
                    'id' => '16c88ac2-b548-4446-9e27-51a28156b299',
                    'position' => 1
                ],
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'changes' => [
                [
                    'id' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
                    'position' => 0
                ],
                [
                    'id' => '16c88ac2-b548-4446-9e27-51a28156b299',
                    'position' => 1
                ],
            ]
        ]);
        $I->seeResponseMatchesJsonType($I->getRootResponseWithItemsJsonType());
        $I->seeResponseMatchesJsonType($I->getPlanDtoJsonType(), '$.data.items[0]');
    }
}
