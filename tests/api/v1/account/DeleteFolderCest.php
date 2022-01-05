<?php

declare(strict_types=1);

namespace App\Tests\api\v1\account;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class DeleteFolderCest
{
    private string $url = '/api/v1/account/delete-folder';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendPOST($this->url, ['id' => '1ad16d32-36af-496e-9867-3919436b8d86']);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseMatchesJsonType([
            'message' => 'string',
            'data' => 'array',
        ]);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendPOST($this->url, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendPOST($this->url, ['id' => '1ad16d32-36af-496e-9867-3919436b8d86']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400IfRemoveLastFolder(ApiTester $I): void
    {
        $I->amAuthenticatedAsMargo($I);
        $I->sendPOST($this->url, ['id' => '3798a279-c4b5-4488-bada-16c31d41f6a6']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }
}
