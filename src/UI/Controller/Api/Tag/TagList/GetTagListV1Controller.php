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
use OpenApi\Annotations as OA;

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
     * @OA\Tag(name="Tag"),
     * @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *         type="object",
     *         allOf={
     *             @OA\Schema(ref="#/components/schemas/JsonResponseOk"),
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     ref=@Model(type=\App\Application\Tag\Dto\GetTagListV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/JsonResponseError")),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/JsonResponseUnauthorized")),
     * @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/JsonResponseException")),
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
