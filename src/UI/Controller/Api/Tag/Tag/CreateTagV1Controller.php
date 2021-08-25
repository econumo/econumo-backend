<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Tag\Tag;

use App\Application\Tag\Tag\TagService;
use App\Application\Tag\Tag\Dto\CreateTagV1RequestDto;
use App\UI\Controller\Api\Tag\Tag\Validation\CreateTagV1Form;
use App\Application\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\UI\Service\Validator\ValidatorInterface;
use App\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class CreateTagV1Controller extends AbstractController
{
    private TagService $tagService;
    private ValidatorInterface $validator;

    public function __construct(TagService $tagService, ValidatorInterface $validator)
    {
        $this->tagService = $tagService;
        $this->validator = $validator;
    }

    /**
     * Tag Tag
     *
     * @SWG\Tag(name="Tag"),
     * @SWG\Parameter(
     *     name="payload",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref=@Model(type=\App\Application\Tag\Tag\Dto\CreateTagV1RequestDto::class)),
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
     *                     ref=@Model(type=\App\Application\Tag\Tag\Dto\CreateTagV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/tag/create-tag", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new CreateTagV1RequestDto();
        $this->validator->validate(CreateTagV1Form::class, $request->request->all(), $dto);
        $user = $this->getUser();
        $result = $this->tagService->createTag($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
