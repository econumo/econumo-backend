<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Account\AccountList;

use App\Application\Account\AccountListService;
use App\Application\Account\Dto\GetAccountListV1RequestDto;
use App\UI\Controller\Api\Account\AccountList\Validation\GetAccountListV1Form;
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

class GetAccountListV1Controller extends AbstractController
{
    private AccountListService $accountListService;
    private ValidatorInterface $validator;

    public function __construct(AccountListService $accountListService, ValidatorInterface $validator)
    {
        $this->accountListService = $accountListService;
        $this->validator = $validator;
    }

    /**
     * Get AccountList
     *
     * @SWG\Tag(name="Account"),
     * @SWG\Tag(name="Need automation"),
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
     *                     ref=@Model(type=\App\Application\Account\Dto\GetAccountListV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/account/get-account-list", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new GetAccountListV1RequestDto();
        $this->validator->validate(GetAccountListV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->accountListService->getAccountList($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
