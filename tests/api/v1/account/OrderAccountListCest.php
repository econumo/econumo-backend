<?php

declare(strict_types=1);

namespace App\Tests\api\v1\account;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class OrderAccountListCest
{
    private string $url = '/api/v1/account/order-account-list';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'changes' => [
                [
                    'id' => 'b53cc423-4e33-49ba-98cc-ef80b2de9a86',
                    'folderId' => 'fe49bf88-0f8b-45b1-8feb-68eb38910e4d',
                    'position' => 0
                ],
                [
                    'id' => '2f8fa6a5-34a1-4ea4-b3ec-e11e22201578',
                    'folderId' => '0f8ab340-73b8-449a-b2ab-1286d8e709fc',
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
                    'id' => 'b53cc423-4e33-49ba-98cc-ef80b2de9a86',
                    'folderId' => 'fe49bf88-0f8b-45b1-8feb-68eb38910e4d',
                    'position' => 0
                ],
                [
                    'id' => '2f8fa6a5-34a1-4ea4-b3ec-e11e22201578',
                    'folderId' => '0f8ab340-73b8-449a-b2ab-1286d8e709fc',
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
                    'id' => 'b53cc423-4e33-49ba-98cc-ef80b2de9a86',
                    'folderId' => 'fe49bf88-0f8b-45b1-8feb-68eb38910e4d',
                    'position' => 0
                ],
                [
                    'id' => '2f8fa6a5-34a1-4ea4-b3ec-e11e22201578',
                    'folderId' => '0f8ab340-73b8-449a-b2ab-1286d8e709fc',
                    'position' => 0
                ],
            ]
        ]);
        $I->seeResponseMatchesJsonType($I->getRootResponseWithItemsJsonType());
        $I->seeResponseMatchesJsonType($I->getAccountDtoJsonType(), '$.data.items[0]');
    }
}
