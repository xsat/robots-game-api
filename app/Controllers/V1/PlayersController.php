<?php

namespace App\Controllers\V1;

use App\Controllers\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PlayersController
 */
class PlayersController extends AbstractController
{
    /**
     * @return Response
     */
    public function create(): Response
    {
        return $this->json();
    }

    /**
     * @param string $playerId
     *
     * @return Response
     */
    public function show(string $playerId): Response
    {
        return $this->json(
            [
                'playerId' => $playerId,
            ]
        );
    }

    /**
     * @param int $playerId
     *
     * @return Response
     */
    public function update(string $playerId): Response
    {
        return $this->json(
            [
                'playerId' => $playerId,
            ]
        );
    }
}
