<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\User\User;

use App\Application\User\UserService;
use App\Application\User\Dto\LoginUserV1RequestDto;
use App\UI\Controller\Api\User\User\Validation\LoginUserV1Form;
use App\Application\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\UI\Service\Validator\ValidatorInterface;
use App\UI\Service\Response\ResponseFactory;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Security;

class LoginUserV1Controller extends AbstractController
{
    private UserService $userService;
    private ValidatorInterface $validator;

    public function __construct(UserService $userService, ValidatorInterface $validator)
    {
        $this->userService = $userService;
        $this->validator = $validator;
    }

    /**
     * User User
     *
     * @SWG\Tag(name="User"),
     * @SWG\Post(
     *     security={},
     *     @SWG\Parameter(
     *         name="payload",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref=@Model(type=\App\Application\User\Dto\LoginUserV1RequestDto::class)),
     *         @SWG\Swagger(security={})
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="OK",
     *         @SWG\Schema(
     *             type="object",
     *             allOf={
     *                 @SWG\Schema(ref="#/definitions/JsonResponseOk"),
     *                 @SWG\Schema(
     *                     @SWG\Property(
     *                         property="data",
     *                         ref=@Model(type=\App\Application\User\Dto\LoginUserV1ResultDto::class)
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *     @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     *     @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     * )
     *
     * @Route("/api/v1/user/login-user", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('Auth failed');
        }
        $result = $this->userService->loginUser($user);

        return ResponseFactory::createOkResponse($request, $result);
    }
}
