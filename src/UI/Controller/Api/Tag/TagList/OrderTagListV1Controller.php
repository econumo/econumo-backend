<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Tag\TagList;

use App\Application\Tag\TagListService;
use App\Application\Tag\Dto\OrderTagListV1RequestDto;
use App\UI\Controller\Api\Tag\TagList\Validation\OrderTagListV1Form;
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

class OrderTagListV1Controller extends AbstractController
{
    public function __construct(private readonly TagListService $tagListService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Order tagList
     *
     * @OA\Tag(name="Tag"),
     * @OA\RequestBody(@OA\JsonContent(ref=@Model(type=\App\Application\Tag\Dto\OrderTagListV1RequestDto::class))),
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
     *                     ref=@Model(type=\App\Application\Tag\Dto\OrderTagListV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/JsonResponseError")),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/JsonResponseUnauthorized")),
     * @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/JsonResponseException")),
     *
     *
     * @return Response
     * @throws ValidationException
     */
    #[Route(path: '/api/v1/tag/order-tag-list', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $dto = new OrderTagListV1RequestDto();
        $this->validator->validate(OrderTagListV1Form::class, $request->request->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->tagListService->orderTagList($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
