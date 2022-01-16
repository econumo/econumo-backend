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
use Swagger\Annotations as SWG;

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
     * @SWG\Tag(name="Connection"),
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
     *                     ref=@Model(type=\App\Application\Connection\Dto\GetConnectionListV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
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
