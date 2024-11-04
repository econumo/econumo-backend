<?php

declare(strict_types=1);

namespace App\EconumoOneBundle\UI\Controller\Api\Account\FolderList;

use App\EconumoOneBundle\Application\Account\FolderListService;
use App\EconumoOneBundle\Application\Account\Dto\GetFolderListV1RequestDto;
use App\EconumoOneBundle\UI\Controller\Api\Account\FolderList\Validation\GetFolderListV1Form;
use App\EconumoOneBundle\Application\Exception\ValidationException;
use App\EconumoOneBundle\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\EconumoOneBundle\UI\Service\Validator\ValidatorInterface;
use App\EconumoOneBundle\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class GetFolderListV1Controller extends AbstractController
{
    public function __construct(private readonly FolderListService $folderListService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Get folder list
     *
     * @OA\Tag(name="Account"),
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
     *                     ref=@Model(type=\App\EconumoOneBundle\Application\Account\Dto\GetFolderListV1ResultDto::class)
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
    #[Route(path: '/api/v1/account/get-folder-list', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $dto = new GetFolderListV1RequestDto();
        $this->validator->validate(GetFolderListV1Form::class, $request->query->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->folderListService->getFolderList($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
