<?php

declare(strict_types=1);

namespace App\Tests\api\v1\budget;

use Codeception\Exception\ModuleException;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class GetPlanCest
{
    private string $url = '/api/v1/budget/get-plan';

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn200ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendGET($this->url, ['id' => 'bceed17e-d492-40be-921a-e7fa6f663fa6']);
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn400ResponseCode(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendGET($this->url, ['unexpected_param' => 'test']);
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturn401ResponseCode(ApiTester $I): void
    {
        $I->sendGET($this->url, ['id' => 'bceed17e-d492-40be-921a-e7fa6f663fa6']);
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    /**
     * @throws ModuleException
     */
    public function requestShouldReturnResponseWithCorrectStructure(ApiTester $I): void
    {
        $I->amAuthenticatedAsJohn();
        $I->sendGET($this->url, ['id' => 'bceed17e-d492-40be-921a-e7fa6f663fa6']);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'item' => $I->geDetailedPlanDtoJsonType(),
            ],
        ]);
        $I->seeResponseMatchesJsonType($I->getCurrencyDtoJsonType(), '$.data.item.currencies[0]');
        $I->seeResponseMatchesJsonType($I->getPlanFolderDtoJsonType(), '$.data.item.folders[0]');
        $I->seeResponseMatchesJsonType($I->getPlanEnvelopeDtoJsonType(), '$.data.item.envelopes[0]');
        $I->seeResponseMatchesJsonType($I->getPlanEnvelopeCategoryDtoJsonType(), '$.data.item.categories[0]');
        $I->seeResponseMatchesJsonType($I->getPlanEnvelopeTagDtoJsonType(), '$.data.item.tags[0]');
        $I->seeResponseMatchesJsonType($I->getPlanSharedAccessDtoJsonType(), '$.data.item.sharedAccess[0]');
    }
}
