<?php

declare(strict_types=1);

namespace App\EconumoBundle\UI\Controller\Api\Connection\Connection;

use App\EconumoBundle\Application\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\EconumoBundle\UI\Service\Response\ResponseFactory;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class DeleteConnectionV1Controller extends AbstractController
{
    /**
     * Delete a user connection
     *
     * @OA\Tag(name="Connection"),
     * @OA\RequestBody(@OA\JsonContent(ref=@Model(type=\App\EconumoBundle\Application\Connection\Dto\DeleteConnectionV1RequestDto::class))),
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
     *                     ref=@Model(type=\App\EconumoBundle\Application\Connection\Dto\DeleteConnectionV1ResultDto::class)
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
    #[Route(path: '/api/v1/connection/delete-connection', name: 'api_connection_delete_connection', methods: ['POST'])]
    public function __invoke() : Response
    {
        return ResponseFactory::createNotImplementedResponse('Not supported in Econumo One');
    }
}
