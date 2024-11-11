<?php

declare(strict_types=1);

namespace App\EconumoBundle\UI\Controller\Api\User\Password;

use App\EconumoBundle\Application\User\PasswordService;
use App\EconumoBundle\Application\User\Dto\RemindPasswordV1RequestDto;
use App\EconumoBundle\UI\Controller\Api\User\Password\Validation\RemindPasswordV1Form;
use App\EconumoBundle\Application\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\EconumoBundle\UI\Service\Validator\ValidatorInterface;
use App\EconumoBundle\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class RemindPasswordV1Controller extends AbstractController
{
    public function __construct(private readonly PasswordService $passwordService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Remind password
     *
     * @OA\Tag(name="User"),
     * @OA\Post(
     *     security={},
     *     @OA\RequestBody(@OA\JsonContent(ref=@Model(type=\App\EconumoBundle\Application\User\Dto\RemindPasswordV1RequestDto::class))),
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
     *                         ref=@Model(type=\App\EconumoBundle\Application\User\Dto\RemindPasswordV1ResultDto::class)
     *                     )
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/JsonResponseError")),
     *     @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/JsonResponseException"))
     * )
     *
     *
     * @return Response
     * @throws ValidationException
     */
    #[Route(path: '/api/v1/user/remind-password', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $dto = new RemindPasswordV1RequestDto();
        $this->validator->validate(RemindPasswordV1Form::class, $request->request->all(), $dto);
        $result = $this->passwordService->remindPassword($dto);

        return ResponseFactory::createOkResponse($request, $result);
    }
}
