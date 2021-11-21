<?php

declare(strict_types=1);

namespace App\UI\Controller\Api\Account\FolderList;

use App\Application\Account\FolderListService;
use App\Application\Account\Dto\GetFolderListV1RequestDto;
use App\UI\Controller\Api\Account\FolderList\Validation\GetFolderListV1Form;
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

class GetFolderListV1Controller extends AbstractController
{
    private FolderListService $folderListService;
    private ValidatorInterface $validator;

    public function __construct(FolderListService $folderListService, ValidatorInterface $validator)
    {
        $this->folderListService = $folderListService;
        $this->validator = $validator;
    }

    /**
     * Get folder list
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
     *                     ref=@Model(type=\App\Application\Account\Dto\GetFolderListV1ResultDto::class)
     *                 )
     *             )
     *         }
     *     )
     * ),
     * @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/JsonResponseError")),
     * @SWG\Response(response=500, description="Internal Server Error", @SWG\Schema(ref="#/definitions/JsonResponseException")),
     *
     * @Route("/api/v1/account/get-folder-list", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
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
