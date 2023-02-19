<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\User\OptionList;

use App\Application\User\OptionListService;
use App\Application\User\Dto\GetOptionListV1RequestDto;
use App\UI\Controller\Api\User\OptionList\Validation\GetOptionListV1Form;
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

class GetOptionListV1Controller extends AbstractController
{
    public function __construct(private readonly OptionListService $optionListService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Get OptionList
     *
     * @OA\Tag(name="User"),
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
     *                     ref=@Model(type=\App\Application\User\Dto\GetOptionListV1ResultDto::class)
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
    #[Route(path: '/api/v1/user/get-option-list', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $dto = new GetOptionListV1RequestDto();
        $this->validator->validate(GetOptionListV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->optionListService->getOptionList($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
