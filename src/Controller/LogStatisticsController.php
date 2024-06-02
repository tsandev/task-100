<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\LogCountItem;
use App\DTO\LogStatsApiRequest;
use App\Repository\LogRecordRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class LogStatisticsController extends AbstractController
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    #[Route('/api/count', name: 'count', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'count of matching results',
        content: new OA\JsonContent(
            ref: new Model(type: LogCountItem::class),
        )
    )]
    public function index(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)] ?LogStatsApiRequest $query,
        LogRecordRepository $logRecordRepository,
    ): JsonResponse|Response
    {
        try {
            // Ask the repository for the result count. If no query params are provided, send an empty DTO.
            $count = $logRecordRepository->getLogsCount($query ?? new LogStatsApiRequest());
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());

            return new Response('Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['count' => $count]);
    }
}
