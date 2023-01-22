<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Category\Category;

use App\Application\Category\CategoryService;
use App\Application\Category\Dto\UnarchiveCategoryV1RequestDto;
use App\UI\Controller\Api\Category\Category\Validation\UnarchiveCategoryV1Form;
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

class UnarchiveCategoryV1Controller extends AbstractController
{
    private CategoryService $categoryService;

    private ValidatorInterface $validator;

    public function __construct(CategoryService $categoryService, ValidatorInterface $validator)
    {
        $this->categoryService = $categoryService;
        $this->validator = $validator;
    }

    /**
     * Unarchive category
     *
     * @OA\Tag(name="Category"),
     * @OA\RequestBody(@OA\JsonContent(ref=@Model(type=\App\Application\Category\Dto\UnarchiveCategoryV1RequestDto::class))),
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
     *                     ref=@Model(type=\App\Application\Category\Dto\UnarchiveCategoryV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/JsonResponseError")),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/JsonResponseUnauthorized")),
     * @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/JsonResponseException")),
     *
     * @Route("/api/v1/category/unarchive-category", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
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
