<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Account\Collection;

use App\Application\Account\CollectionService;
use App\Application\Account\Dto\ReorderCollectionV1RequestDto;
use App\Domain\Entity\User;
use App\UI\Controller\Api\Account\Collection\Validation\ReorderCollectionV1Form;
use App\Application\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\UI\Service\Validator\ValidatorInterface;
use App\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class ReorderCollectionV1Controller extends AbstractController
{
    private CollectionService $collectionService;
    private ValidatorInterface $validator;

    public function __construct(CollectionService $collectionService, ValidatorInterface $validator)
    {
        $this->collectionService = $collectionService;
        $this->validator = $validator;
    }

    /**
     * Account Collection
     *
     * @SWG\Tag(name="Account"),
     * @SWG\Parameter(
     *     name="payload",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref=@Model(type=\App\Application\Account\Dto\ReorderCollectionV1RequestDto::class)),
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
     *                     ref=@Model(type=\App\Application\Account\Dto\ReorderCollectionV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/account/reorder-collection", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new ReorderCollectionV1RequestDto();
        $this->validator->validate(ReorderCollectionV1Form::class, $request->request->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->collectionService->reorderCollection($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
