<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class DeleteFolderCest
{
    private string $url = '/api/v1/budget/delete-folder';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['id' => '860f0c50-bb33-42c1-955d-b3ce112462b8']);
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
        $I->sendPOST($this->url, ['id' => '860f0c50-bb33-42c1-955d-b3ce112462b8']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['id' => '860f0c50-bb33-42c1-955d-b3ce112462b8']);
        $I->seeResponseMatchesJsonType(['data' => [],]);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn400ResponseCodeIfFolderIsNotEmpty(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, ['id' => 'e776079f-33a8-4c61-aa6c-21192daa50e7']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }
}
