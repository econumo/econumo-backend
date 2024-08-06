<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Transaction\TransactionList;

use App\Application\Transaction\TransactionListService;
use App\Application\Transaction\Dto\GetTransactionListV1RequestDto;
use App\UI\Controller\Api\Transaction\TransactionList\Validation\GetTransactionListV1Form;
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

class GetTransactionListV1Controller extends AbstractController
{
    public function __construct(private readonly TransactionListService $transactionListService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Get TransactionList
     *
     * @OA\Tag(name="Transaction"),
     * @OA\Parameter(
     *     name="accountId",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string"),
     *     description="Account id",
     * ),
     * @OA\Parameter(
     *       name="periodStart",
     *       in="query",
     *       required=true,
     *       @OA\Schema(type="string"),
     *       description="Period start",
     *   ),
     * @OA\Parameter(
     *       name="periodEnd",
     *       in="query",
     *       required=true,
     *       @OA\Schema(type="string"),
     *       description="Period end",
     *   ),
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
     *                     ref=@Model(type=\App\Application\Transaction\Dto\GetTransactionListV1ResultDto::class)
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
    #[Route(path: '/api/v1/transaction/get-transaction-list', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $dto = new GetTransactionListV1RequestDto();
        $this->validator->validate(GetTransactionListV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->transactionListService->getTransactionList($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
