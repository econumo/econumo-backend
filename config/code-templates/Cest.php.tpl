<?php
declare(strict_types=1);

namespace App\Tests\_DIRECTORY_;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

/**
 * Class _FILENAME_Cest
 * @package App\Tests\_DIRECTORY_
 */
class _FILENAME_Cest
{
    private $url = '_URL_';

    /**
     * @param ApiTester $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedByEmail('email_1@mail.com');
        $I->send_HTTPMETHOD_($this->url);

        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
    * @param ApiTester $I
    * @throws \Codeception\Exception\ModuleException
    */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedByEmail('email_1@mail.com');
        $I->send_HTTPMETHOD_($this->url);

        $I->seeResponseMatchesJsonType([
            'data' => [
                'id' => 'string'
            ]
        ]);
    }

    /**
    * @param ApiTester $I
    */
    public function requestWithoutTokenShould401ResponseCode(ApiTester $I): void
    {
        $I->send_HTTPMETHOD_($this->url);

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
    * @param ApiTester $I
    */
    public function requestWithIncorrectTokenShould401ResponseCode(ApiTester $I): void
    {
        $I->amBearerAuthenticated('incorrect_token');
        $I->send_HTTPMETHOD_($this->url);

        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}
