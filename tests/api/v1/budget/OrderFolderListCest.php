<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class OrderFolderListCest
{
    private string $url = '/api/v1/budget/order-folder-list';

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
                    'id' => '949ec3ce-6379-406a-ae20-14ad63193d19',
                    'position' => 0
                ],
                [
                    'id' => 'e776079f-33a8-4c61-aa6c-21192daa50e7',
                    'position' => 1
                ],
                [
                    'id' => '62ccc225-b141-42a4-8063-825c8b72d135',
                    'position' => 2
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
                    'id' => '949ec3ce-6379-406a-ae20-14ad63193d19',
                    'position' => 0
                ],
                [
                    'id' => 'e776079f-33a8-4c61-aa6c-21192daa50e7',
                    'position' => 1
                ],
                [
                    'id' => '62ccc225-b141-42a4-8063-825c8b72d135',
                    'position' => 2
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
                    'id' => '949ec3ce-6379-406a-ae20-14ad63193d19',
                    'position' => 0
                ],
                [
                    'id' => 'e776079f-33a8-4c61-aa6c-21192daa50e7',
                    'position' => 1
                ],
                [
                    'id' => '62ccc225-b141-42a4-8063-825c8b72d135',
                    'position' => 2
                ],
            ]
        ]);
        $I->seeResponseMatchesJsonType($I->getRootResponseWithItemsJsonType());
        $I->seeResponseMatchesJsonType($I->getPlanFolderDtoJsonType(), '$.data.items[0]');
    }
}
