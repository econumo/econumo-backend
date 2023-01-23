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
use OpenApi\Annotations as OA;

class CreatePayeeV1Controller extends AbstractController
{
    public function __construct(private readonly PayeeService $payeeService, private readonly ValidatorInterface $validator, private readonly OperationServiceInterface $operationService)
    {
    }

    /**
     * Create Payee
     *
     * @OA\Tag(name="Payee"),
     * @OA\RequestBody(@OA\JsonContent(ref=@Model(type=\App\Application\Payee\Dto\CreatePayeeV1RequestDto::class))),
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
     *                     ref=@Model(type=\App\Application\Payee\Dto\CreatePayeeV1ResultDto::class)
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
    #[Route(path: '/api/v1/payee/create-payee', methods: ['POST'])]
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
