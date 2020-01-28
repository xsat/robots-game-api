<?php

namespace App\Controllers\V1\Players;

use Symfony\Component\HttpFoundation\Response;
use App\Controllers\AbstractController;

/**
 * Class GamesController
 */
class GamesController extends AbstractController
{
    /**
     * @param string $playerId
     *
     * @return Response
     */
    public function create(string $playerId): Response
    {
        return $this->json(
            [
                'playerId' => $playerId,
            ]
        );
    }

    /**
     * @param string $playerId
     * @param string $gameId
     *
     * @return Response
     */
    public function show(string $playerId, string $gameId): Response
    {
        return $this->json(
            [
                'playerId' => $playerId,
                'gameId' => $gameId,
            ]
        );
    }

    /**
     * @param string $playerId
     * @param string $gameId
     *
     * @return Response
     */
    public function update(string $playerId, string $gameId): Response
    {
        return $this->json(
            [
                'playerId' => $playerId,
                'gameId' => $gameId,
            ]
        );
    }
}
