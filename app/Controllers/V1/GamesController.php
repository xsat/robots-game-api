<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractController;
use App\Exceptions\HttpNotFoundException;
use App\Mappers\GameMapper;
use App\Models\Game;
use MongoDB\Client;
use RuntimeException;
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
        $game = new Game();
        if (!(new GameMapper(new Client()))->create($game)) {
            throw new RuntimeException('Game was not created.');
        }

        return $this->json($game);
    }

    /**
     * @param string $gameId
     *
     * @return Response
     *
     * @throws HttpNotFoundException
     */
    public function show(string $gameId): Response
    {
        $game = (new GameMapper(new Client()))->findById($gameId);
        if ($game === null) {
            throw new HttpNotFoundException('Game was not found.');
        }

        return $this->json($game);
    }

    /**
     * @param string $gameId
     *
     * @return Response
     *
     * @throws HttpNotFoundException
     * @throws RuntimeException
     */
    public function update(string $gameId): Response
    {
        $gameMapper = new GameMapper(new Client());
        $game = $gameMapper->findById($gameId);
        if ($game === null) {
            throw new HttpNotFoundException('Game was not found.');
        }

        $game->setEnded(true);
        if (!$gameMapper->update($game)) {
            throw new RuntimeException('Game was not updated.');
        }

        return $this->json($game);
    }
}
