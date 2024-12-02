<?php

declare(strict_types=1);

namespace App\EconumoFamilyBundle\UI\Controller\Api\Connection\AccountAccess;

use App\EconumoBundle\Application\Connection\AccountAccessService;
use App\EconumoBundle\Application\Connection\Dto\SetAccountAccessV1RequestDto;
use App\EconumoBundle\UI\Controller\Api\Connection\AccountAccess\Validation\SetAccountAccessV1Form;
use App\EconumoBundle\Application\Exception\ValidationException;
use App\EconumoBundle\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\EconumoBundle\UI\Service\Validator\ValidatorInterface;
use App\EconumoBundle\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class SetAccountAccessV1Controller extends AbstractController
{
    public function __construct(private readonly AccountAccessService $accountAccessService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Set an account access
     *
     * @OA\Tag(name="Connection"),
     * @OA\RequestBody(@OA\JsonContent(ref=@Model(type=\App\EconumoBundle\Application\Connection\Dto\SetAccountAccessV1RequestDto::class))),
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
     *                     ref=@Model(type=\App\EconumoBundle\Application\Connection\Dto\SetAccountAccessV1ResultDto::class)
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
    #[Route(path: '/api/v1/connection/set-account-access', name: 'api_connection_set_account_access', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $dto = new SetAccountAccessV1RequestDto();
        $this->validator->validate(SetAccountAccessV1Form::class, $request->request->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->accountAccessService->setAccountAccess($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
