<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class CreateEnvelopeCest
{
    private string $url = '/api/v1/budget/create-envelope';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendPOST($this->url, [
            'planId' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'folderId' => '62ccc225-b141-42a4-8063-825c8b72d135',
            'type' => 'expense',
            'name' => 'New envelope',
            'icon' => 'factory',
            'currencyId' => 'fe5d9269-b69c-4841-9c04-136225447eca',
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
            'planId' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'folderId' => '62ccc225-b141-42a4-8063-825c8b72d135',
            'type' => 'expense',
            'name' => 'New envelope',
            'icon' => 'factory',
            'currencyId' => 'fe5d9269-b69c-4841-9c04-136225447eca',
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
            'planId' => 'bceed17e-d492-40be-921a-e7fa6f663fa6',
            'folderId' => '62ccc225-b141-42a4-8063-825c8b72d135',
            'type' => 'expense',
            'name' => 'New envelope',
            'icon' => 'factory',
            'currencyId' => 'fe5d9269-b69c-4841-9c04-136225447eca',
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
