<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractController;
use App\Mappers\PlayerMapper;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LeaderboardsController
 */
class LeaderboardsController extends AbstractController
{
    /**
     * @return Response
     */
    public function list(): Response
    {
        $playerMapper = new PlayerMapper($this->database());
        $limit = (int)($this->get('limit') ?? 10);
        $offset = (int)($this->get('offset') ?? 0);

        return $this->json(
            [
                'limit' => $limit,
                'offset' => $offset,
                'total' => $playerMapper->total(),
                'items' => $playerMapper->list($limit, $offset),
            ]
        );
    }
}
