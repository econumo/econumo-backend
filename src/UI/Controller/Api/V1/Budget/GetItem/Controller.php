<?php
declare(strict_types=1);

namespace App\UI\Controller\Api\V1\Budget\GetItem;

use App\Application\Budget\BudgetService;
use App\UI\Service\Response\ResponseFactory;
use App\UI\Service\Validator\ValidatorInterface;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model as SwgModel;
use Swagger\Annotations as SWG;

class Controller extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var BudgetService
     */
    private $budgetService;

    public function __construct(
        ValidatorInterface $validator,
        BudgetService $budgetService
    ) {
        $this->validator = $validator;
        $this->budgetService = $budgetService;
    }

    /**
     * @SWG\Tag(name="budget", description=""),
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="Budget ID",
     * ),
     * @SWG\Parameter(
     *     name="fromDate",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="From date (Y-m-d)",
     * ),
     * @SWG\Parameter(
     *     name="toDate",
     *     in="query",
     *     type="string",
     *     required=true,
     *     description="To date (Y-m-d)",
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
     *                     ref=@SwgModel(type=\App\Application\Budget\Dto\GetItemDisplayDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/budget/get-item", methods={"GET"})
     *
     * @param Request $request
     * @throws \App\Application\Exception\ValidationException
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $model = new Model();
        $this->validator->validate(Form::class, $request->query->all(), $model);
        $result = $this->budgetService->getItem(
            $model->id,
            $model->fromDate,
            $model->toDate,
        );

        return ResponseFactory::createOkResponse($request, $result);
    }
}
