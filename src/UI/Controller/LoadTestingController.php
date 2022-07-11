<?php

declare(strict_types=1);

namespace App\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoadTestingController extends AbstractController
{
    /**
     * Check controller
     *
     * @Route("/_/load-testing", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        sleep(1);
        return new JsonResponse(['success' => true]);
    }
}
