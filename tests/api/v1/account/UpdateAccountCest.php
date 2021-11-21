<?php

declare(strict_types=1);

namespace App\Tests\api\v1\account;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class UpdateAccountCest
{
    private string $url = '/api/v1/account/update-account';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendPOST($this->url);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendPOST($this->url, ['id' => '5f3834d1-34e8-4f60-a697-004e63854513', 'name' => 'Cash RUB', 'balance' => 1000, 'updatedAt' => '2021-08-19 10:00:00']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendPOST($this->url, ['id' => '', 'name' => '', 'balance' => 1000, 'updatedAt' => '2021-08-19 10:00:00']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn($I);
        $I->sendPOST($this->url, ['id' => '5f3834d1-34e8-4f60-a697-004e63854513', 'name' => 'Cash RUB', 'balance' => 1000, 'updatedAt' => '2021-08-19 10:00:00']);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'item' => [
                    'id' => 'string',
                    'ownerId' => 'string',
                    'name' => 'string',
                    'position' => 'integer',
                    'currencyId' => 'string',
                    'currencyAlias' => 'string',
                    'currencySign' => 'string',
                    'balance' => 'float|integer',
                    'type' => 'integer',
                    'icon' => 'string',
                    'sharedAccess' => 'array',
                ],
                'transaction' => [
                    'id' => 'string',
                    'authorId' => 'string',
                    'authorName' => 'string',
                    'type' => 'string',
                    'accountId' => 'string',
                    'accountRecipientId' => 'string|null',
                    'amount' => 'float|integer',
                    'amountRecipient' => 'float|integer|null',
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
                ],
            ],
        ]);
    }
}
