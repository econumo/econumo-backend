<?php
declare(strict_types=1);

namespace App\Application\__CG_API_SUBJECT_CC__;

use App\Application\__CG_API_SUBJECT_CC__\Dto\__CG_API_ACTION_CC__DisplayDto;
use App\Application\__CG_API_SUBJECT_CC__\Assembler\__CG_API_ACTION_CC__DisplayAssembler;

class __CG_API_SUBJECT_CC__Service
{
    /**
     * @var __CG_API_ACTION_CC__DisplayAssembler
     */
    private $__CG_API_ACTION_CCL__DisplayAssembler;

    public function __construct(__CG_API_ACTION_CC__DisplayAssembler $__CG_API_ACTION_CCL__DisplayAssembler)
    {
        $this->__CG_API_ACTION_CCL__DisplayAssembler = $__CG_API_ACTION_CCL__DisplayAssembler;
    }

    public function __CG_API_ACTION_CCL__(string $id): __CG_API_ACTION_CC__DisplayDto
    {
        return $this->__CG_API_ACTION_CCL__DisplayAssembler->assemble($id);
    }
}
