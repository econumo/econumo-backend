<?php

declare(strict_types=1);

namespace App\Tests\api\v1\transaction;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ImportTransactionListCest
{
    private string $url = '/api/v1/transaction/import-transaction-list';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendPOST($this->url, []);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }
}
