<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class OrderEnvelopeListCest
{
    private string $url = '/api/v1/budget/order-envelope-list';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'planId' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'changes' => [
                [
                    'id' => '96ab006d-4f9d-43e1-abfc-18151e9c59d7',
                    'folderId' => '62ccc225-b141-42a4-8063-825c8b72d135',
                    'position' => 10
                ],
                [
                    'id' => 'ccad84e4-6391-43b5-a7bd-a17b7622ad90',
                    'folderId' => '62ccc225-b141-42a4-8063-825c8b72d135',
                    'position' => 9
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
            'planId' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'changes' => [
                [
                    'id' => '96ab006d-4f9d-43e1-abfc-18151e9c59d7',
                    'folderId' => '62ccc225-b141-42a4-8063-825c8b72d135',
                    'position' => 10
                ],
                [
                    'id' => 'ccad84e4-6391-43b5-a7bd-a17b7622ad90',
                    'folderId' => '62ccc225-b141-42a4-8063-825c8b72d135',
                    'position' => 9
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
            'planId' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'changes' => [
                [
                    'id' => '96ab006d-4f9d-43e1-abfc-18151e9c59d7',
                    'folderId' => '62ccc225-b141-42a4-8063-825c8b72d135',
                    'position' => 10
                ],
                [
                    'id' => 'ccad84e4-6391-43b5-a7bd-a17b7622ad90',
                    'folderId' => '62ccc225-b141-42a4-8063-825c8b72d135',
                    'position' => 9
                ],
            ]
        ]);
        $I->seeResponseMatchesJsonType($I->getRootResponseWithItemsJsonType());
        $I->seeResponseMatchesJsonType($I->getPlanEnvelopeDtoJsonType(), '$.data.items[0]');
    }
}
