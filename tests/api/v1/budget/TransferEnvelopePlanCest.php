<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class TransferEnvelopePlanCest
{
    private string $url = '/api/v1/budget/transfer-envelope-plan';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'fromEnvelopeId' => '10d26248-9518-46eb-84ca-6ab8d4cbcdbb',
            'toEnvelopeId' => 'acb421b0-ea48-4efe-ab04-7bda5a5c68ae',
            'period' => '2020-01-01 00:00:00',
            'amount' => 100.0
        ]);
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
        $I->sendPOST($this->url, [
            'fromEnvelopeId' => '10d26248-9518-46eb-84ca-6ab8d4cbcdbb',
            'toEnvelopeId' => 'acb421b0-ea48-4efe-ab04-7bda5a5c68ae',
            'period' => '2020-01-01 00:00:00',
            'amount' => 100.0
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'fromEnvelopeId' => '10d26248-9518-46eb-84ca-6ab8d4cbcdbb',
            'toEnvelopeId' => 'acb421b0-ea48-4efe-ab04-7bda5a5c68ae',
            'period' => '2020-01-01 00:00:00',
            'amount' => 100.0
        ]);
        $I->seeResponseMatchesJsonType(['data' => [],]);
    }
}
