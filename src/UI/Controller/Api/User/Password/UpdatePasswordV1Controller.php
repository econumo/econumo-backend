<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\User\Password;

use App\Application\User\PasswordService;
use App\Application\User\Dto\UpdatePasswordV1RequestDto;
use App\UI\Controller\Api\User\Password\Validation\UpdatePasswordV1Form;
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

class UpdatePasswordV1Controller extends AbstractController
{
    private PasswordService $passwordService;
    private ValidatorInterface $validator;

    public function __construct(PasswordService $passwordService, ValidatorInterface $validator)
    {
        $this->passwordService = $passwordService;
        $this->validator = $validator;
    }

    /**
     * Update password
     *
     * @OA\Tag(name="User"),
     * @OA\RequestBody(@OA\JsonContent(ref=@Model(type=\App\Application\User\Dto\UpdatePasswordV1RequestDto::class))),
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
     *                     ref=@Model(type=\App\Application\User\Dto\UpdatePasswordV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/JsonResponseError")),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/JsonResponseUnauthorized")),
     * @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/JsonResponseException")),
     *
     * @Route("/api/v1/user/update-password", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new UpdatePasswordV1RequestDto();
        $this->validator->validate(UpdatePasswordV1Form::class, $request->request->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->passwordService->updatePassword($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
