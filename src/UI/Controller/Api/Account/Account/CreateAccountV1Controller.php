<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Account\Account;

use App\Application\Account\AccountService;
use App\Application\Account\Dto\CreateAccountV1RequestDto;
use App\Domain\Entity\ValueObject\Id;
use App\UI\Controller\Api\Account\Account\Validation\CreateAccountV1Form;
use App\Application\Exception\ValidationException;
use App\Domain\Entity\User;
use App\UI\Service\OperationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\UI\Service\Validator\ValidatorInterface;
use App\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class CreateAccountV1Controller extends AbstractController
{
    private AccountService $accountService;
    private ValidatorInterface $validator;
    private OperationServiceInterface $operationService;

    public function __construct(
        AccountService $accountService,
        ValidatorInterface $validator,
        OperationServiceInterface $operationService
    ) {
        $this->accountService = $accountService;
        $this->validator = $validator;
        $this->operationService = $operationService;
    }

    /**
     * Create account
     *
     * @SWG\Tag(name="Account"),
     * @SWG\Tag(name="Need automation"),
     * @SWG\Parameter(
     *     name="payload",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref=@Model(type=\App\Application\Account\Dto\CreateAccountV1RequestDto::class)),
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
     *                     ref=@Model(type=\App\Application\Account\Dto\CreateAccountV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/account/create-account", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new CreateAccountV1RequestDto();
        $this->validator->validate(CreateAccountV1Form::class, $request->request->all(), $dto);
        $operation = $this->operationService->lock(new Id($dto->id));
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->accountService->createAccount($dto, $user->getId());
        $this->operationService->release($operation);

        return ResponseFactory::createOkResponse($request, $result);
    }
}
