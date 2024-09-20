<?php

declare(strict_types=1);

namespace App\Tests\api\v1\account;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class CreateAccountCest
{
    private string $url = '/api/v1/account/create-account';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'id' => '4b7946ca-2a48-4ea3-8645-2960cea6b94f',
            'name' => 'Savings Account',
            'currencyId' => 'e54f14e4-cdd3-4095-a892-ae7f532aaf7c',
            'balance' => 100.13,
            'icon' => 'savings',
            'folderId' => 'fe49bf88-0f8b-45b1-8feb-68eb38910e4d'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'id' => '',
            'name' => '',
            'currencyId' => '',
            'balance' => 0,
            'icon' => 'no',
            'folderId' => 'fe49bf88-0f8b-45b1-8feb-68eb38910e4d'
        ]);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendPOST($this->url, ['id' => 'test']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'id' => '4b7946ca-2a48-4ea3-8645-2960cea6b94e',
            'name' => 'Savings Account',
            'currencyId' => 'e54f14e4-cdd3-4095-a892-ae7f532aaf7c',
            'balance' => 100.13,
            'icon' => 'savings',
            'folderId' => 'fe49bf88-0f8b-45b1-8feb-68eb38910e4d'
        ]);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'item' => $I->getAccountDtoJsonType(),
            ],
        ]);
        $data = $I->grabDataFromResponseByJsonPath('$.data.item.sharedAccess[0]');
        $I->assertEmpty($data);
    }
}
