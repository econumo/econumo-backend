<?php

declare(strict_types=1);

namespace App\Tests\api\v1\transaction;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class GetTransactionListCest
{
    private string $url = '/api/v1/transaction/get-transaction-list';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendGET($this->url);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestWithAccountIdShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendGET($this->url, ['accountId' => '0aaa0450-564e-411e-8018-7003f6dbeb92']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendGET($this->url, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendGET($this->url);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendGET($this->url);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'items' => 'array',
            ],
        ]);
        $I->seeResponseMatchesJsonType([
            'id' => 'string',
            'authorId' => 'string',
            'authorAvatar' => 'string',
            'authorName' => 'string',
            'type' => 'string',
            'accountId' => 'string',
            'accountRecipientId' => 'string|null',
            'amount' => 'float',
            'amountRecipient' => 'float|null',
            'categoryId' => 'string|null',
            'categoryName' => 'string',
            'description' => 'string',
            'payeeId' => 'string|null',
            'payeeName' => 'string',
            'tagId' => 'string|null',
            'tagName' => 'string',
            'date' => 'string',
            'day' => 'string',
            'time' => 'string',
        ], '$.data.items[0]');
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestWithAccountIdShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendGET($this->url, ['accountId' => '0aaa0450-564e-411e-8018-7003f6dbeb92']);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'items' => 'array',
            ],
        ]);
        $I->seeResponseMatchesJsonType([
            'id' => 'string',
            'authorId' => 'string',
            'authorAvatar' => 'string',
            'authorName' => 'string',
            'type' => 'string',
            'accountId' => 'string',
            'accountRecipientId' => 'string|null',
            'amount' => 'float',
            'amountRecipient' => 'float|null',
            'categoryId' => 'string|null',
            'categoryName' => 'string',
            'description' => 'string',
            'payeeId' => 'string|null',
            'payeeName' => 'string',
            'tagId' => 'string|null',
            'tagName' => 'string',
            'date' => 'string',
            'day' => 'string',
            'time' => 'string',
        ], '$.data.items[0]');
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestWithAccountIdShouldReturnResponseWithEmptyList(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendGET($this->url, ['accountId' => '4eec1ee6-1992-4222-b9ab-31ece5eaad5d']);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'items' => 'array',
            ],
        ]);
        $data = $I->grabDataFromResponseByJsonPath('$.data.items[0]');
        $I->assertEmpty($data);
    }
}
