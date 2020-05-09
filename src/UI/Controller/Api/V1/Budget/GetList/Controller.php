<?php
declare(strict_types=1);

namespace App\UI\Controller\Api\V1\Budget\GetList;

use App\Application\Budget\BudgetService;
use App\Domain\Entity\User;
use App\UI\Service\Response\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model as SwgModel;
use Swagger\Annotations as SWG;

class Controller extends AbstractController
{
    /**
     * @var BudgetService
     */
    private $budgetService;

    public function __construct(
        BudgetService $budgetService
    ) {
        $this->budgetService = $budgetService;
    }

    /**
     * @SWG\Tag(name="budget", description=""),
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
     *                     ref=@SwgModel(type=\App\Application\Budget\Dto\GetListDisplayDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/budget/get-list", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->budgetService->getList($user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
