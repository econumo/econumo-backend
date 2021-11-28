<?php

declare(strict_types=1);

namespace App\Tests\api\v1\account;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class GenerateInviteCest
{
    private string $url = '/api/v1/account/generate-invite';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['accountId' => '4eec1ee6-1992-4222-b9ab-31ece5eaad5d', 'recipientUsername' => 'dany@targarien.test', 'role' => 'admin']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendPOST($this->url, ['accountId' => '99ff78ec-5081-11ec-bf63-0242ac130002', 'recipientUsername' => 'margo@tirrell.test', 'role' => 'admin']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['id' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['accountId' => '4eec1ee6-1992-4222-b9ab-31ece5eaad5d', 'recipientUsername' => 'dany@targarien.test', 'role' => 'admin']);
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
            'data' => [
                'invite' => $I->getInviteDtoJsonType(),
            ],
        ]);
    }
}
