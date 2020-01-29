<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GamesController
 */
class GamesController extends AbstractController
{
    /**
     * @return Response
     */
    public function create(): Response
    {
        return $this->json();
    }

    /**
     * @param string $gameId
     *
     * @return Response
     */
    public function show(string $gameId): Response
    {
        return $this->json(
            [
                'gameId' => $gameId,
            ]
        );
    }

    /**
     * @param string $gameId
     *
     * @return Response
     */
    public function update(string $gameId): Response
    {
        return $this->json(
            [
                'gameId' => $gameId,
            ]
        );
    }
}
