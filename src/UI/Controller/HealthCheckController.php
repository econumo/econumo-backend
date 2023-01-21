<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Infrastructure\OpenExchangeRates\CurrencyExchangeUsageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthCheckController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CurrencyExchangeUsageService $currencyExchangeStatusService;

    public function __construct(
        EntityManagerInterface $entityManager,
        CurrencyExchangeUsageService $currencyExchangeStatusService
    ) {
        $this->entityManager = $entityManager;
        $this->currencyExchangeStatusService = $currencyExchangeStatusService;
    }

    /**
     * Check controller
     *
     * @Route("/_/health-check", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $response = [
            'database' => null,
            'messenger' => null,
            'exchange_rates' => null,
        ];
        $status = true;

        try {
            $response['database'] = $this->entityManager->getConnection()->connect();
        } catch (\Exception $e) {
            $response['database'] = false;
            $status = false;
        }

        try {
            $response['exchange_rates'] = (bool)$this->currencyExchangeStatusService->getUsage();
        } catch (\Exception $e) {
            $response['exchange_rates'] = false;
        }

        return new JsonResponse($response, $status ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
