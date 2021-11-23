<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Tag\TagList;

use App\Application\Tag\TagListService;
use App\Application\Tag\Dto\GetTagListV1RequestDto;
use App\UI\Controller\Api\Tag\TagList\Validation\GetTagListV1Form;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\UI\Service\Validator\ValidatorInterface;
use App\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class GetTagListV1Controller extends AbstractController
{
    private TagListService $tagListService;
    private ValidatorInterface $validator;

    public function __construct(TagListService $tagListService, ValidatorInterface $validator)
    {
        $this->tagListService = $tagListService;
        $this->validator = $validator;
    }

    /**
     * Get TagList
     *
     * @SWG\Tag(name="Tag"),
     * @SWG\Tag(name="Need automation"),
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="ID чего-либо",
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
     *                     ref=@Model(type=\App\Application\Tag\Dto\GetTagListV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/tag/get-tag-list", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new GetTagListV1RequestDto();
        $this->validator->validate(GetTagListV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->tagListService->getTagList($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
