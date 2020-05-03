<?php
declare(strict_types=1);

namespace App\UI\Controller\__CG_URL_TYPE_CC__\__CG_API_VERSION_CC__\__CG_API_SUBJECT_CC__\__CG_API_ACTION_CC__;

use App\Application\__CG_API_SUBJECT_CC__\__CG_API_SUBJECT_CC__Service;
use App\UI\Service\Response\ResponseFactory;
use App\UI\Service\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model as SwgModel;
use Swagger\Annotations as SWG;

class Controller extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var __CG_API_SUBJECT_CC__Service
     */
    private $__CG_API_SUBJECT_CCL__Service;

    public function __construct(
        ValidatorInterface $validator,
        __CG_API_SUBJECT_CC__Service $__CG_API_SUBJECT_CCL__Service
    ) {
        $this->validator = $validator;
        $this->__CG_API_SUBJECT_CCL__Service = $__CG_API_SUBJECT_CCL__Service;
    }

    /**
     * @SWG\Tag(name="__CG_API_SUBJECT__", description=""),
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="ID",
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
     *                     ref=@SwgModel(type=\App\Application\__CG_API_SUBJECT_CC__\Dto\__CG_API_ACTION_CC__DisplayDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("__CG_URL__", methods={"__CG_HTTP_METHOD__"})
     *
     * @param Request $request
     * @throws \App\Application\Exception\ValidationException
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $model = new Model();
        $this->validator->validate(Form::class, $request->query->all(), $model);
        $result = $this->__CG_API_SUBJECT_CCL__Service->__CG_API_ACTION_CCL__($model->id);

        return ResponseFactory::createOkResponse($request, $result);
    }
}
