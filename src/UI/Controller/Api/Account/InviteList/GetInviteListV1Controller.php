<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Account\InviteList;

use App\Application\Account\InviteListService;
use App\Application\Account\Dto\GetInviteListV1RequestDto;
use App\UI\Controller\Api\Account\InviteList\Validation\GetInviteListV1Form;
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

class GetInviteListV1Controller extends AbstractController
{
    private InviteListService $inviteListService;
    private ValidatorInterface $validator;

    public function __construct(InviteListService $inviteListService, ValidatorInterface $validator)
    {
        $this->inviteListService = $inviteListService;
        $this->validator = $validator;
    }

    /**
     * Get InviteList
     *
     * @SWG\Tag(name="Account"),
     * @SWG\Tag(name="Need automation"),
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
     *                     ref=@Model(type=\App\Application\Account\Dto\GetInviteListV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/account/get-invite-list", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $dto = new GetInviteListV1RequestDto();
        $this->validator->validate(GetInviteListV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->inviteListService->getInviteList($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
