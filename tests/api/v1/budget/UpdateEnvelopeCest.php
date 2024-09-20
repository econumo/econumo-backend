<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class UpdateEnvelopeCest
{
    private string $url = '/api/v1/budget/update-envelope';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'id' => 'acb421b0-ea48-4efe-ab04-7bda5a5c68ae',
            'name' => 'Empty envelope 2',
            'icon' => 'airplane',
            'currencyId' => 'e54f14e4-cdd3-4095-a892-ae7f532aaf7c',
            'categories' => [],
            'tags' => [],
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
            'id' => 'acb421b0-ea48-4efe-ab04-7bda5a5c68ae',
            'name' => 'Empty envelope 2',
            'icon' => 'airplane',
            'currencyId' => 'e54f14e4-cdd3-4095-a892-ae7f532aaf7c',
            'categories' => [],
            'tags' => [],
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
            'id' => 'acb421b0-ea48-4efe-ab04-7bda5a5c68ae',
            'name' => 'Empty envelope 2',
            'icon' => 'airplane',
            'currencyId' => 'e54f14e4-cdd3-4095-a892-ae7f532aaf7c',
            'categories' => [],
            'tags' => [],
        ]);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'item' => $I->getPlanEnvelopeDtoJsonType(),
            ],
        ]);
    }
}
