<?php
declare(strict_types=1);

namespace App\UI\Controller\Api\V1\User\Login;

use App\Application\User\UserService;
use App\Domain\Entity\User\User;
use App\UI\Service\Response\ResponseFactory;
use Nelmio\ApiDocBundle\Annotation\Model as SwgModel;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Controller extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    /**
     * @SWG\Tag(name="user", description=""),
     * @SWG\Parameter(
     *     name="payload",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref=@SwgModel(type=\App\UI\Controller\Api\V1\User\Login\Model::class)),
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
     *                     ref=@SwgModel(type=\App\Application\User\Dto\LoginDisplayDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/user/login", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->userService->login($user);

        return ResponseFactory::createOkResponse($request, $result);
    }
}
