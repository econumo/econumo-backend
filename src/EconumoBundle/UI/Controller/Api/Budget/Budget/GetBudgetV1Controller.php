<?php

declare(strict_types=1);

namespace App\EconumoBundle\UI\Controller\Api\Budget\Budget;

use App\EconumoBundle\Application\Budget\BudgetService;
use App\EconumoBundle\Application\Budget\Dto\GetBudgetV1RequestDto;
use App\EconumoBundle\UI\Controller\Api\Budget\Budget\Validation\GetBudgetV1Form;
use App\EconumoBundle\Application\Exception\ValidationException;
use App\EconumoBundle\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\EconumoBundle\UI\Service\Validator\ValidatorInterface;
use App\EconumoBundle\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class GetBudgetV1Controller extends AbstractController
{
    public function __construct(private readonly BudgetService $budgetService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Get Budget
     *
     * @OA\Tag(name="Budget"),
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string"),
     *     description="Budget ID",
     * ),
     * @OA\Parameter(
     *      name="date",
     *      in="query",
     *      required=true,
     *      @OA\Schema(type="string"),
     *      description="Date (YYYY-MM-DD)",
     *  ),
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
     *                     ref=@Model(type=\App\EconumoBundle\Application\Budget\Dto\GetBudgetV1ResultDto::class)
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
    #[Route(path: '/api/v1/budget/get-budget', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $dto = new GetBudgetV1RequestDto();
        $this->validator->validate(GetBudgetV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->budgetService->getBudget($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}