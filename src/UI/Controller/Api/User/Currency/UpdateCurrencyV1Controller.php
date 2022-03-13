<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\User\Currency;

use App\Application\User\CurrencyService;
use App\Application\User\Dto\UpdateCurrencyV1RequestDto;
use App\UI\Controller\Api\User\Currency\Validation\UpdateCurrencyV1Form;
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

class UpdateCurrencyV1Controller extends AbstractController
{
    private CurrencyService $currencyService;
    private ValidatorInterface $validator;

    public function __construct(CurrencyService $currencyService, ValidatorInterface $validator)
    {
        $this->currencyService = $currencyService;
        $this->validator = $validator;
    }

    /**
     * Update currency
     *
     * @SWG\Tag(name="User"),
     * @SWG\Tag(name="Need automation"),
     * @SWG\Parameter(
     *     name="payload",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref=@Model(type=\App\Application\User\Dto\UpdateCurrencyV1RequestDto::class)),
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
     *                     ref=@Model(type=\App\Application\User\Dto\UpdateCurrencyV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/user/update-currency", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new UpdateCurrencyV1RequestDto();
        $this->validator->validate(UpdateCurrencyV1Form::class, $request->request->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->currencyService->updateCurrency($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
