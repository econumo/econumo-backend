<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\UI\Controller\Api\Payee\Payee;

use App\EconumoOneBundle\Application\Payee\PayeeService;
use App\EconumoOneBundle\Application\Payee\Dto\UpdatePayeeV1RequestDto;
use App\EconumoOneBundle\UI\Controller\Api\Payee\Payee\Validation\UpdatePayeeV1Form;
use App\EconumoOneBundle\Application\Exception\ValidationException;
use App\EconumoOneBundle\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\EconumoOneBundle\UI\Service\Validator\ValidatorInterface;
use App\EconumoOneBundle\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class UpdatePayeeV1Controller extends AbstractController
{
    public function __construct(private readonly PayeeService $payeeService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Update payee
     *
     * @OA\Tag(name="Payee"),
     * @OA\RequestBody(@OA\JsonContent(ref=@Model(type=\App\EconumoOneBundle\Application\Payee\Dto\UpdatePayeeV1RequestDto::class))),
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
     *                     ref=@Model(type=\App\EconumoOneBundle\Application\Payee\Dto\UpdatePayeeV1ResultDto::class)
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
    #[Route(path: '/api/v1/payee/update-payee', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $dto = new UpdatePayeeV1RequestDto();
        $this->validator->validate(UpdatePayeeV1Form::class, $request->request->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->payeeService->updatePayee($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
