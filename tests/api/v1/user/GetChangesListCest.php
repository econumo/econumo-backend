<?php

declare(strict_types=1);

namespace App\Tests\api\v1\user;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class GetChangesListCest
{
    private string $url = '/api/v1/user/get-changes-list';

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendGET($this->url, [
            'foldersUpdatedAt' => '2020-01-01 00:00:00',
            'accountsUpdatedAt' => '2020-01-01 00:00:00',
            'categoriesUpdatedAt' => '2020-01-01 00:00:00',
            'tagsUpdatedAt' => '2020-01-01 00:00:00',
            'payeesUpdatedAt' => '2020-01-01 00:00:00',
            'currenciesUpdatedAt' => '2020-01-01 00:00:00',
            'currencyRatesUpdatedAt' => '2020-01-01 00:00:00',
            'transactionsUpdatedAt' => '2020-01-01 00:00:00',
            'connectionsUpdatedAt' => '2020-01-01 00:00:00',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendGET($this->url, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendGET($this->url, [
            'foldersUpdatedAt' => '2020-01-01 00:00:00',
            'accountsUpdatedAt' => '2020-01-01 00:00:00',
            'categoriesUpdatedAt' => '2020-01-01 00:00:00',
            'tagsUpdatedAt' => '2020-01-01 00:00:00',
            'payeesUpdatedAt' => '2020-01-01 00:00:00',
            'currenciesUpdatedAt' => '2020-01-01 00:00:00',
            'currencyRatesUpdatedAt' => '2020-01-01 00:00:00',
            'transactionsUpdatedAt' => '2020-01-01 00:00:00',
            'connectionsUpdatedAt' => '2020-01-01 00:00:00',
        ]);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendGET($this->url, [
            'foldersUpdatedAt' => '2020-01-01 00:00:00',
            'accountsUpdatedAt' => '2020-01-01 00:00:00',
            'categoriesUpdatedAt' => '2020-01-01 00:00:00',
            'tagsUpdatedAt' => '2020-01-01 00:00:00',
            'payeesUpdatedAt' => '2020-01-01 00:00:00',
            'currenciesUpdatedAt' => '2020-01-01 00:00:00',
            'currencyRatesUpdatedAt' => '2020-01-01 00:00:00',
            'transactionsUpdatedAt' => '2020-01-01 00:00:00',
            'connectionsUpdatedAt' => '2020-01-01 00:00:00',
        ]);
        $I->seeResponseMatchesJsonType($I->getAccountFolderDtoJsonType(), '$.data.folders[0]');
        $I->seeResponseMatchesJsonType($I->getAccountDtoJsonType(), '$.data.accounts[0]');
        $I->seeResponseMatchesJsonType($I->getCategoryDtoJsonType(), '$.data.categories[0]');
        $I->seeResponseMatchesJsonType($I->getTagDtoJsonType(), '$.data.tags[0]');
        $I->seeResponseMatchesJsonType($I->getPayeeDtoJsonType(), '$.data.payees[0]');
        $I->seeResponseMatchesJsonType($I->getCurrencyDtoJsonType(), '$.data.currencies[0]');
        $I->seeResponseMatchesJsonType($I->getCurrencyRateDtoJsonType(), '$.data.currencyRates[0]');
        $I->seeResponseMatchesJsonType($I->getTransactionDtoJsonType(), '$.data.transactions[0]');
//        $I->seeResponseMatchesJsonType($I->getConnectionDtoJsonType(), '$.data.connections[0]');
    }
}
