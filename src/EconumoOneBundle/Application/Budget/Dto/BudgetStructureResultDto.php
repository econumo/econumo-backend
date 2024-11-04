<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\Application\Budget\Dto;

use App\EconumoOneBundle\Application\Budget\Dto\BudgetFolderResultDto;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     required={}
 * )
 */
class BudgetStructureResultDto
{
    /**
     * Account access
     * @var BudgetFolderResultDto[]
     * @OA\Property()
     */
    public array $folders = [];

    /**
     * Account access
     * @var BudgetFolderResultDto[]
     * @OA\Property()
     */
    public array $elements = [];
}