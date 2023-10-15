<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Budget\Folder;

use App\Application\Budget\FolderService;
use App\Application\Budget\Dto\DeleteFolderV1RequestDto;
use App\UI\Controller\Api\Budget\Folder\Validation\DeleteFolderV1Form;
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

class DeleteFolderV1Controller extends AbstractController
{
    public function __construct(private readonly FolderService $folderService, private readonly ValidatorInterface $validator)
    {
    }

    /**
     * Delete folder
     *
     * @OA\Tag(name="Budget"),
     * @OA\RequestBody(@OA\JsonContent(ref=@Model(type=\App\Application\Budget\Dto\DeleteFolderV1RequestDto::class))),
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
     *                     ref=@Model(type=\App\Application\Budget\Dto\DeleteFolderV1ResultDto::class)
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
    #[Route(path: '/api/v1/budget/delete-folder', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $dto = new DeleteFolderV1RequestDto();
        $this->validator->validate(DeleteFolderV1Form::class, $request->request->all(), $dto);
        /** @var User $user */
        $user = $this->getUser();
        $result = $this->folderService->deleteFolder($dto, $user->getId());

        return ResponseFactory::createOkResponse($request, $result);
    }
}
