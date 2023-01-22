<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Currency\CurrencyRateList;

use App\Application\Currency\CurrencyRateListService;
use App\Application\Currency\Dto\GetCurrencyRateListV1RequestDto;
use App\UI\Controller\Api\Currency\CurrencyRateList\Validation\GetCurrencyRateListV1Form;
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

class GetCurrencyRateListV1Controller extends AbstractController
{
    private CurrencyRateListService $currencyRateListService;

    private ValidatorInterface $validator;

    public function __construct(CurrencyRateListService $currencyRateListService, ValidatorInterface $validator)
    {
        $this->currencyRateListService = $currencyRateListService;
        $this->validator = $validator;
    }

    /**
     * Get CurrencyRateList
     *
     * @OA\Tag(name="Currency"),
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string"),
     *     description="ID чего-либо",
     * ),
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
     *                     ref=@Model(type=\App\Application\Currency\Dto\GetCurrencyRateListV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/JsonResponseError")),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/JsonResponseUnauthorized")),
     * @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/JsonResponseException")),
     *
     * @Route("/api/v1/currency/get-currency-rate-list", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new GetCurrencyRateListV1RequestDto();
        $this->validator->validate(GetCurrencyRateListV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->currencyRateListService->getCurrencyRateList($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
