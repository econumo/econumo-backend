<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Account\Invite;

use App\Application\Account\InviteService;
use App\Application\Account\Dto\GenerateInviteV1RequestDto;
use App\Domain\Entity\User;
use App\UI\Controller\Api\Account\Invite\Validation\GenerateInviteV1Form;
use App\Application\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\UI\Service\Validator\ValidatorInterface;
use App\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

class GenerateInviteV1Controller extends AbstractController
{
    private InviteService $inviteService;
    private ValidatorInterface $validator;

    public function __construct(InviteService $inviteService, ValidatorInterface $validator)
    {
        $this->inviteService = $inviteService;
        $this->validator = $validator;
    }

    /**
     * Account Invite
     *
     * @SWG\Tag(name="Account"),
     * @SWG\Parameter(
     *     name="payload",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(ref=@Model(type=\App\Application\Account\Invite\Dto\GenerateInviteV1RequestDto::class)),
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
     *                     ref=@Model(type=\App\Application\Account\Dto\GenerateInviteV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/account/generate-invite", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new GenerateInviteV1RequestDto();
        $this->validator->validate(GenerateInviteV1Form::class, $request->request->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->inviteService->generateInvite($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
