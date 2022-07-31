<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Connection\ConnectionList;

use App\Application\Connection\ConnectionListService;
use App\Application\Connection\Dto\GetConnectionListV1RequestDto;
use App\UI\Controller\Api\Connection\ConnectionList\Validation\GetConnectionListV1Form;
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

class GetConnectionListV1Controller extends AbstractController
{
    private ConnectionListService $connectionListService;
    private ValidatorInterface $validator;

    public function __construct(ConnectionListService $connectionListService, ValidatorInterface $validator)
    {
        $this->connectionListService = $connectionListService;
        $this->validator = $validator;
    }

    /**
     * Get ConnectionList
     *
     * @OA\Tag(name="Connection"),
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string"),
     *     description="ID чего-либо",
     * ),
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
     *                     ref=@Model(type=\App\Application\Connection\Dto\GetConnectionListV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/JsonResponseError")),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/JsonResponseUnauthorized")),
     * @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/JsonResponseException")),
     *
     * @Route("/api/v1/connection/get-connection-list", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new GetConnectionListV1RequestDto();
        $this->validator->validate(GetConnectionListV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->connectionListService->getConnectionList($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
