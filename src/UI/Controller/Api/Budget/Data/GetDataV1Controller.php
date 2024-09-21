<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Budget\Data;

use App\Application\Budget\DataService;
use App\Application\Budget\Dto\GetDataV1RequestDto;
use App\UI\Controller\Api\Budget\Data\Validation\GetDataV1Form;
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

class GetDataV1Controller extends AbstractController
{
    public function __construct(private readonly DataService $dataService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Get Data
     *
     * @OA\Tag(name="Budget"),
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string"),
     *     description="Budget ID",
     * ),
     * @OA\Parameter(
     *     name="period",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string"),
     *     description="Budget period (Y-m-d H:i:s)",
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
     *                     ref=@Model(type=\App\Application\Budget\Dto\GetDataV1ResultDto::class)
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
    #[Route(path: '/api/v1/budget/get-data', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $dto = new GetDataV1RequestDto();
        $this->validator->validate(GetDataV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->dataService->getData($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
