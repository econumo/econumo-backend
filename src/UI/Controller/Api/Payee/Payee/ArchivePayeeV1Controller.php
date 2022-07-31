<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Payee\Payee;

use App\Application\Payee\PayeeService;
use App\Application\Payee\Dto\ArchivePayeeV1RequestDto;
use App\UI\Controller\Api\Payee\Payee\Validation\ArchivePayeeV1Form;
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

class ArchivePayeeV1Controller extends AbstractController
{
    private PayeeService $payeeService;
    private ValidatorInterface $validator;

    public function __construct(PayeeService $payeeService, ValidatorInterface $validator)
    {
        $this->payeeService = $payeeService;
        $this->validator = $validator;
    }

    /**
     * Archive payee
     *
     * @OA\Tag(name="Payee"),
     * @OA\RequestBody(@OA\JsonContent(ref=@Model(type=\App\Application\Payee\Dto\ArchivePayeeV1RequestDto::class))),
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
     *                     ref=@Model(type=\App\Application\Payee\Dto\ArchivePayeeV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @OA\Response(response=400, description="Bad Request", @OA\JsonContent(ref="#/components/schemas/JsonResponseError")),
     * @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/JsonResponseUnauthorized")),
     * @OA\Response(response=500, description="Internal Server Error", @OA\JsonContent(ref="#/components/schemas/JsonResponseException")),
     *
     * @Route("/api/v1/payee/archive-payee", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new ArchivePayeeV1RequestDto();
        $this->validator->validate(ArchivePayeeV1Form::class, $request->request->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->payeeService->archivePayee($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
