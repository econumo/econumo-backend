<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\User\ChangesList;

use App\Application\User\ChangesListService;
use App\Application\User\Dto\GetChangesListV1RequestDto;
use App\UI\Controller\Api\User\ChangesList\Validation\GetChangesListV1Form;
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

class GetChangesListV1Controller extends AbstractController
{
    private ChangesListService $changesListService;
    private ValidatorInterface $validator;

    public function __construct(ChangesListService $changesListService, ValidatorInterface $validator)
    {
        $this->changesListService = $changesListService;
        $this->validator = $validator;
    }

    /**
     * Get ChangesList
     *
     * @SWG\Tag(name="User"),
     * @SWG\Tag(name="Need automation"),
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     required=true,
     *     type="string",
     *     description="ID чего-либо",
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
     *                     ref=@Model(type=\App\Application\User\Dto\GetChangesListV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/user/get-changes-list", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new GetChangesListV1RequestDto();
        $this->validator->validate(GetChangesListV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->changesListService->getChangesList($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
