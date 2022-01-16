<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Connection\Invite;

use App\Application\Connection\InviteService;
use App\Application\Connection\Dto\AcceptInviteV1RequestDto;
use App\UI\Controller\Api\Connection\Invite\Validation\AcceptInviteV1Form;
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

class AcceptInviteV1Controller extends AbstractController
{
    private InviteService $inviteService;
    private ValidatorInterface $validator;

    public function __construct(InviteService $inviteService, ValidatorInterface $validator)
    {
        $this->inviteService = $inviteService;
        $this->validator = $validator;
    }

    /**
     * Accept invite
     *
     * @SWG\Tag(name="Connection"),
     * @SWG\Tag(name="Need automation"),
     * @SWG\Parameter(
     *     name="payload",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref=@Model(type=\App\Application\Connection\Dto\AcceptInviteV1RequestDto::class)),
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
     *                     ref=@Model(type=\App\Application\Connection\Dto\AcceptInviteV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/connection/accept-invite", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new AcceptInviteV1RequestDto();
        $this->validator->validate(AcceptInviteV1Form::class, $request->request->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->inviteService->acceptInvite($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
