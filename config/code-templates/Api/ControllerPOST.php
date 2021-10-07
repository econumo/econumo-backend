<?php

declare(strict_types=1);

namespace _CG_APPROOT_\UI\Controller\Api\_CG_MODULE_\_CG_SUBJECT_;

use _CG_APPROOT_\Application\_CG_MODULE_\_CG_SUBJECT_Service;
use _CG_APPROOT_\Application\_CG_MODULE_\Dto\_CG_ACTION__CG_SUBJECT__CG_VERSION_RequestDto;
use _CG_APPROOT_\UI\Controller\Api\_CG_MODULE_\_CG_SUBJECT_\Validation\_CG_ACTION__CG_SUBJECT__CG_VERSION_Form;
use App\Application\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\UI\Service\Validator\ValidatorInterface;
use App\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class _CG_ACTION__CG_SUBJECT__CG_VERSION_Controller extends AbstractController
{
    private _CG_SUBJECT_Service $_CG_SUBJECT_LCFIRST_Service;
    private ValidatorInterface $validator;

    public function __construct(_CG_SUBJECT_Service $_CG_SUBJECT_LCFIRST_Service, ValidatorInterface $validator)
    {
        $this->_CG_SUBJECT_LCFIRST_Service = $_CG_SUBJECT_LCFIRST_Service;
        $this->validator = $validator;
    }

    /**
     * _CG_MODULE_ _CG_SUBJECT_
     *
     * @SWG\Tag(name="_CG_MODULE_"),
     * @SWG\Parameter(
     *     name="payload",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref=@Model(type=\_CG_APPROOT_\Application\_CG_MODULE_\_CG_SUBJECT_\Dto\_CG_ACTION__CG_SUBJECT__CG_VERSION_RequestDto::class)),
     * ),
     * @SWG\Response(
     *     response=200,
     *     description="OK",
     *     @SWG\Schema(
     *         type="object",
     *         allOf={
     *             @SWG\Schema(ref="#/definitions/JsonResponseOk"),
     *             @SWG\Schema(
     *                 @SWG\Property(
     *                     property="data",
     *                     ref=@Model(type=\_CG_APPROOT_\Application\_CG_MODULE_\_CG_SUBJECT_\Dto\_CG_ACTION__CG_SUBJECT__CG_VERSION_ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("_CG_URL_", methods={"_CG_METHOD_"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new _CG_ACTION__CG_SUBJECT__CG_VERSION_RequestDto();
        $this->validator->validate(_CG_ACTION__CG_SUBJECT__CG_VERSION_Form::class, $request->request->all(), $dto);
        $result = $this->_CG_SUBJECT_LCFIRST_Service->_CG_ACTION_LCFIRST__CG_SUBJECT_($dto);

        return ResponseFactory::createOkResponse($request, $result);
    }
}
