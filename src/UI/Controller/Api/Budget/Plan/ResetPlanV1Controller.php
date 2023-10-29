<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Budget\Plan;

use App\Application\Budget\PlanService;
use App\Application\Budget\Dto\ResetPlanV1RequestDto;
use App\UI\Controller\Api\Budget\Plan\Validation\ResetPlanV1Form;
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

class ResetPlanV1Controller extends AbstractController
{
    public function __construct(private readonly PlanService $planService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Reset plan
     *
     * @OA\Tag(name="Budget"),
     * @OA\RequestBody(@OA\JsonContent(ref=@Model(type=\App\Application\Budget\Dto\ResetPlanV1RequestDto::class))),
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
     *                     ref=@Model(type=\App\Application\Budget\Dto\ResetPlanV1ResultDto::class)
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
    #[Route(path: '/api/v1/budget/reset-plan', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $dto = new ResetPlanV1RequestDto();
        $this->validator->validate(ResetPlanV1Form::class, $request->request->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->planService->resetPlan($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
