<?php

declare(strict_types=1);

namespace App\Tests\api\v1\tag;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class OrderTagListCest
{
    private string $url = '/api/v1/tag/order-tag-list';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendPOST($this->url, ['ids' => ['4b53d029-c1ed-46ad-8d86-1049542f4a7e', 'e93abce1-0522-4a34-8dcf-5143769a3a8e']]);
        $I->seeResponseCodeIs(HttpCode::OK);
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
        $I->sendPOST($this->url, ['ids' => ['4b53d029-c1ed-46ad-8d86-1049542f4a7e', 'e93abce1-0522-4a34-8dcf-5143769a3a8e']]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendPOST($this->url, ['ids' => ['4b53d029-c1ed-46ad-8d86-1049542f4a7e', 'e93abce1-0522-4a34-8dcf-5143769a3a8e']]);
        $I->seeResponseMatchesJsonType($I->getRootResponseWithItemsJsonType());
        $I->seeResponseMatchesJsonType($I->getTagDtoJsonType(), '$.data.items[0]');
    }
}
