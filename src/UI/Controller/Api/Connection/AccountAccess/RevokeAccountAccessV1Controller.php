<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Connection\AccountAccess;

use App\Application\Connection\AccountAccessService;
use App\Application\Connection\Dto\RevokeAccountAccessV1RequestDto;
use App\UI\Controller\Api\Connection\AccountAccess\Validation\RevokeAccountAccessV1Form;
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

class RevokeAccountAccessV1Controller extends AbstractController
{
    private AccountAccessService $accountAccessService;
    private ValidatorInterface $validator;

    public function __construct(AccountAccessService $accountAccessService, ValidatorInterface $validator)
    {
        $this->accountAccessService = $accountAccessService;
        $this->validator = $validator;
    }

    /**
     * Revoke accountAccess
     *
     * @SWG\Tag(name="Connection"),
     * @SWG\Tag(name="Need automation"),
     * @SWG\Parameter(
     *     name="payload",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref=@Model(type=\App\Application\Connection\Dto\RevokeAccountAccessV1RequestDto::class)),
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
     *                     ref=@Model(type=\App\Application\Connection\Dto\RevokeAccountAccessV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/connection/revoke-account-access", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new RevokeAccountAccessV1RequestDto();
        $this->validator->validate(RevokeAccountAccessV1Form::class, $request->request->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->accountAccessService->revokeAccountAccess($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
