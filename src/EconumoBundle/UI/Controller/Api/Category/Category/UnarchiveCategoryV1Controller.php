<?php

declare(strict_types=1);

namespace App\EconumoBundle\UI\Controller\Api\Category\Category;

use App\EconumoBundle\Application\Category\CategoryService;
use App\EconumoBundle\Application\Category\Dto\UnarchiveCategoryV1RequestDto;
use App\EconumoBundle\UI\Controller\Api\Category\Category\Validation\UnarchiveCategoryV1Form;
use App\EconumoBundle\Application\Exception\ValidationException;
use App\EconumoBundle\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\EconumoBundle\UI\Service\Validator\ValidatorInterface;
use App\EconumoBundle\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class UnarchiveCategoryV1Controller extends AbstractController
{
    public function __construct(private readonly CategoryService $categoryService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Unarchive a category
     *
     * @OA\Tag(name="Category"),
     * @OA\RequestBody(@OA\JsonContent(ref=@Model(type=\App\EconumoBundle\Application\Category\Dto\UnarchiveCategoryV1RequestDto::class))),
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
     *                     ref=@Model(type=\App\EconumoBundle\Application\Category\Dto\UnarchiveCategoryV1ResultDto::class)
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
    #[Route(path: '/api/v1/category/unarchive-category', name: 'api_category_unarchive_category', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $dto = new UnarchiveCategoryV1RequestDto();
        $this->validator->validate(UnarchiveCategoryV1Form::class, $request->request->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->categoryService->unarchiveCategory($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
