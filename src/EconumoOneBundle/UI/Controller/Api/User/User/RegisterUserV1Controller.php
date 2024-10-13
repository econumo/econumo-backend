<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\UI\Controller\Api\User\User;

use App\EconumoOneBundle\Application\User\UserService;
use App\EconumoOneBundle\Application\User\Dto\RegisterUserV1RequestDto;
use App\EconumoOneBundle\UI\Controller\Api\User\User\Validation\RegisterUserV1Form;
use App\EconumoOneBundle\Application\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\EconumoOneBundle\UI\Service\Validator\ValidatorInterface;
use App\EconumoOneBundle\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class RegisterUserV1Controller extends AbstractController
{
    public function __construct(private readonly UserService $userService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Register User
     *
     * @OA\Tag(name="User"),
     * @OA\Post(
     *     security={},
     *     @OA\RequestBody(@OA\JsonContent(ref=@Model(type=\App\EconumoOneBundle\Application\User\Dto\RegisterUserV1RequestDto::class))),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             allOf={
     *                 @OA\Schema(ref="#/components/schemas/JsonResponseOk"),
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         ref=@Model(type=\App\EconumoOneBundle\Application\User\Dto\RegisterUserV1ResultDto::class)
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/JsonResponseError")),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/JsonResponseException")),
     * )
     *
     *
     * @return Response
     * @throws ValidationException
     */
    #[Route(path: '/api/v1/user/register-user', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $dto = new RegisterUserV1RequestDto();
        $this->validator->validate(RegisterUserV1Form::class, $request->request->all(), $dto);
        $result = $this->userService->registerUser($dto);

        return ResponseFactory::createOkResponse($request, $result);
    }
}
