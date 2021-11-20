<?php

declare(strict_types=1);

namespace App\Tests\api\v1\account;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class AddAccountCest
{
    private string $url = '/api/v1/account/add-account';

//    /**
//     * @throws \Codeception\Exception\ModuleException
//     */
//    public function requestShouldReturn200ResponseCode(ApiTester $I): void
//    {
//        $I->sendPOST($this->url, ['id' => 'test']);
//        $I->seeResponseCodeIs(HttpCode::OK);
//    }
//
//    /**
//     * @throws \Codeception\Exception\ModuleException
//     */
//    public function requestShouldReturn400ResponseCode(ApiTester $I): void
//    {
//        $I->sendPOST($this->url, ['unexpected_param' => 'test']);
//        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
//    }
//
//    /**
//     * @throws \Codeception\Exception\ModuleException
//     */
//    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
//    {
//        $I->sendPOST($this->url, ['id' => 'test']);
//        $I->seeResponseMatchesJsonType([
//            'data' => [
//                'result' => 'string',
//            ],
//        ]);
//    }
}
