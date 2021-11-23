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
use Swagger\Annotations as SWG;

class GetTransactionListV1Controller extends AbstractController
{
    private TransactionListService $transactionListService;
    private ValidatorInterface $validator;

    public function __construct(TransactionListService $transactionListService, ValidatorInterface $validator)
    {
        $this->transactionListService = $transactionListService;
        $this->validator = $validator;
    }

    /**
     * Get TransactionList
     *
     * @SWG\Tag(name="Transaction"),
     * @SWG\Tag(name="Need automation"),
     * @SWG\Parameter(
     *     name="accountId",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Account id",
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
     *                     ref=@Model(type=\App\Application\Transaction\Dto\GetTransactionListV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/transaction/get-transaction-list", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
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
