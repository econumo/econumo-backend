<?php

declare(strict_types=1);

namespace _CG_APPROOT_\Tests\api\_CG_VERSION_LOWER_\_CG_MODULE_LOWER_;

use _CG_APPROOT_\Tests\ApiTester;
use Codeception\Util\HttpCode;

class _CG_ACTION__CG_SUBJECT_Cest
{
    private string $url = '_CG_URL_';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->send_CG_METHOD_($this->url, ['id' => 'test']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->send_CG_METHOD_($this->url, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->send_CG_METHOD_($this->url, ['id' => 'test']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->send_CG_METHOD_($this->url, ['id' => 'test']);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'result' => 'string',
            ],
        ]);
    }
}
