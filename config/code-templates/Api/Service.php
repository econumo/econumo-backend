<?php

declare(strict_types=1);

namespace _CG_APPROOT_\Application\_CG_MODULE_;

use _CG_APPROOT_\Application\_CG_MODULE_\Dto\_CG_ACTION__CG_SUBJECT__CG_VERSION_RequestDto;
use _CG_APPROOT_\Application\_CG_MODULE_\Dto\_CG_ACTION__CG_SUBJECT__CG_VERSION_ResultDto;
use _CG_APPROOT_\Application\_CG_MODULE_\Assembler\_CG_ACTION__CG_SUBJECT__CG_VERSION_ResultAssembler;

class _CG_SUBJECT_Service
{
    private _CG_ACTION__CG_SUBJECT__CG_VERSION_ResultAssembler $_CG_ACTION_LCFIRST__CG_SUBJECT__CG_VERSION_ResultAssembler;

    public function __construct(
        _CG_ACTION__CG_SUBJECT__CG_VERSION_ResultAssembler $_CG_ACTION_LCFIRST__CG_SUBJECT__CG_VERSION_ResultAssembler
    ) {
        $this->_CG_ACTION_LCFIRST__CG_SUBJECT__CG_VERSION_ResultAssembler = $_CG_ACTION_LCFIRST__CG_SUBJECT__CG_VERSION_ResultAssembler;
    }

    public function _CG_ACTION_LCFIRST__CG_SUBJECT_(
        _CG_ACTION__CG_SUBJECT__CG_VERSION_RequestDto $dto
    ): _CG_ACTION__CG_SUBJECT__CG_VERSION_ResultDto {
        // some actions ...
        return $this->_CG_ACTION_LCFIRST__CG_SUBJECT__CG_VERSION_ResultAssembler->assemble($dto);
    }
}
