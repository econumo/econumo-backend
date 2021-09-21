<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\User\Updates;

use App\Application\User\Updates\UpdatesService;
use App\Application\User\Updates\Dto\CheckUpdatesV1RequestDto;
use App\Domain\Entity\User;
use App\UI\Controller\Api\User\Updates\Validation\CheckUpdatesV1Form;
use App\Application\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\UI\Service\Validator\ValidatorInterface;
use App\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class CheckUpdatesV1Controller extends AbstractController
{
    private UpdatesService $updatesService;
    private ValidatorInterface $validator;

    public function __construct(UpdatesService $updatesService, ValidatorInterface $validator)
    {
        $this->updatesService = $updatesService;
        $this->validator = $validator;
    }

    /**
     * User Updates
     *
     * @SWG\Tag(name="User"),
     * @SWG\Parameter(
     *     name="lastUpdate",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="Дата последнего обновления",
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
     *                     ref=@Model(type=\App\Application\User\Updates\Dto\CheckUpdatesV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/user/check-updates", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new CheckUpdatesV1RequestDto();
        $this->validator->validate(CheckUpdatesV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->updatesService->checkUpdates($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
