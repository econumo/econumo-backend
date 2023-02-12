<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\User\UserData;

use App\Application\User\UserDataService;
use App\Application\User\Dto\GetUserDataV1RequestDto;
use App\UI\Controller\Api\User\UserData\Validation\GetUserDataV1Form;
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

class GetUserDataV1Controller extends AbstractController
{
    public function __construct(private readonly UserDataService $userDataService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Get UserData
     * @OA\Tag(name="User"),
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
     *                     ref=@Model(type=\App\Application\User\Dto\GetUserDataV1ResultDto::class)
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
    #[Route(path: '/api/v1/user/get-user-data', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $dto = new GetUserDataV1RequestDto();
        $this->validator->validate(GetUserDataV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->userDataService->getUserData($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
