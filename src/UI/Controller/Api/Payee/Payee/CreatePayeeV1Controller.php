<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Payee\Payee;

use App\Application\Payee\PayeeService;
use App\Application\Payee\Dto\CreatePayeeV1RequestDto;
use App\Domain\Entity\ValueObject\Id;
use App\UI\Controller\Api\Payee\Payee\Validation\CreatePayeeV1Form;
use App\Application\Exception\ValidationException;
use App\UI\Service\OperationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\UI\Service\Validator\ValidatorInterface;
use App\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class CreatePayeeV1Controller extends AbstractController
{
    private PayeeService $payeeService;
    private ValidatorInterface $validator;
    private OperationServiceInterface $operationService;

    public function __construct(
        PayeeService $payeeService,
        ValidatorInterface $validator,
        OperationServiceInterface $operationService
    ) {
        $this->payeeService = $payeeService;
        $this->validator = $validator;
        $this->operationService = $operationService;
    }

    /**
     * Create Payee
     *
     * @SWG\Tag(name="Payee"),
     * @SWG\Tag(name="Need automation"),
     * @SWG\Parameter(
     *     name="payload",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref=@Model(type=\App\Application\Payee\Dto\CreatePayeeV1RequestDto::class)),
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
     *                     ref=@Model(type=\App\Application\Payee\Dto\CreatePayeeV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/payee/create-payee", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new CreatePayeeV1RequestDto();
        $this->validator->validate(CreatePayeeV1Form::class, $request->request->all(), $dto);
        $operation = $this->operationService->lock(new Id($dto->id));
        $user = $this->getUser();
        $result = $this->payeeService->createPayee($dto, $user->getId());
        $this->operationService->release($operation);

        return ResponseFactory::createOkResponse($request, $result);
    }
}
