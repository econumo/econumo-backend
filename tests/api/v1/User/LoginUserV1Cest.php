<?php

declare(strict_types=1);

namespace App\Tests\api\v1\User;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class LoginUserV1Cest
{
    private string $url = '/api/v1/user/login-user';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->sendPOST($this->url, ['username' => 'dmitry@econumo', 'password' => 'pass']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

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
//        $I->sendPOST($this->url, ['username' => 'test', 'password' => '123']);
//        $I->seeResponseMatchesJsonType([
//            'data' => [
//                'token' => 'string',
//            ],
//        ]);
//    }
}
