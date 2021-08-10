<?php

namespace App\Tests\functional;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class SwaggerCest
{
    public function shouldApiDocJsonReturn200(ApiTester $I): void
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGET('/_/api/doc.json');
        $I->canSeeResponseCodeIs(HttpCode::OK);
    }
}
