<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Currency\Collection;

use App\Application\Currency\CollectionService;
use App\Application\Currency\Dto\GetCollectionV1RequestDto;
use App\Domain\Entity\User;
use App\UI\Controller\Api\Currency\Collection\Validation\GetCollectionV1Form;
use App\Application\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\UI\Service\Validator\ValidatorInterface;
use App\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class GetCollectionV1Controller extends AbstractController
{
    private CollectionService $collectionService;
    private ValidatorInterface $validator;

    public function __construct(CollectionService $collectionService, ValidatorInterface $validator)
    {
        $this->collectionService = $collectionService;
        $this->validator = $validator;
    }

    /**
     * Get Currency Collection
     *
     * @SWG\Tag(name="Currency"),
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
     *                     ref=@Model(type=\App\Application\Currency\Dto\GetCollectionV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/currency/get-collection", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new GetCollectionV1RequestDto();
        $this->validator->validate(GetCollectionV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->collectionService->getCollection($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
