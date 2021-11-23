<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Category\CategoryList;

use App\Application\Category\CategoryListService;
use App\Application\Category\Dto\GetCategoryListV1RequestDto;
use App\UI\Controller\Api\Category\CategoryList\Validation\GetCategoryListV1Form;
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

class GetCategoryListV1Controller extends AbstractController
{
    private CategoryListService $categoryListService;
    private ValidatorInterface $validator;

    public function __construct(CategoryListService $categoryListService, ValidatorInterface $validator)
    {
        $this->categoryListService = $categoryListService;
        $this->validator = $validator;
    }

    /**
     * Get CategoryList
     *
     * @SWG\Tag(name="Category"),
     * @SWG\Tag(name="Need automation"),
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
     *                     ref=@Model(type=\App\Application\Category\Dto\GetCategoryListV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/category/get-category-list", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new GetCategoryListV1RequestDto();
        $this->validator->validate(GetCategoryListV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->categoryListService->getCategoryList($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
