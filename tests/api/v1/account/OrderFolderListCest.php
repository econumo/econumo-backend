<?php

declare(strict_types=1);

namespace App\Tests\api\v1\account;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class OrderFolderListCest
{
    private string $url = '/api/v1/account/order-folder-list';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'changes' => [
                [
                    'id' => 'fe49bf88-0f8b-45b1-8feb-68eb38910e4d',
                    'position' => 1
                ],
                [
                    'id' => '0f8ab340-73b8-449a-b2ab-1286d8e709fc',
                    'position' => 0
                ],
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendPOST($this->url, [
            'changes' => [
                [
                    'id' => 'fe49bf88-0f8b-45b1-8feb-68eb38910e4d',
                    'position' => 1
                ],
                [
                    'id' => '0f8ab340-73b8-449a-b2ab-1286d8e709fc',
                    'position' => 0
                ],
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'changes' => [
                [
                    'id' => 'fe49bf88-0f8b-45b1-8feb-68eb38910e4d',
                    'position' => 1
                ],
                [
                    'id' => '0f8ab340-73b8-449a-b2ab-1286d8e709fc',
                    'position' => 0
                ],
            ]
        ]);
        $I->seeResponseMatchesJsonType($I->getRootResponseWithItemsJsonType());
        $I->seeResponseMatchesJsonType($I->getAccountFolderDtoJsonType(), '$.data.items[0]');
    }
}
