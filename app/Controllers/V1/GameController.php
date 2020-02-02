<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractTokenController;
use App\Exceptions\HttpNotFoundException;
use App\Mappers\GameMapper;
use App\Models\Game;
use App\Models\Game\Player;
use App\Models\Game\Round;
use App\Models\Game\Round\Action;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GameController
 */
class GameController extends AbstractTokenController
{
    /**
     * @return Response
     */
    public function create(): Response
    {
        $player = new Player();
        $player->setPlayerId('5e31e0fd111c00006c001fdc');
        $player->setHealth(100);

        $game = new Game();
        $game->setPlayers([$player]);

        $action = new Action();
        $action->setPlayerId('5e31e0fd111c00006c001fdc');
        $action->setType('attack');
        $action->setDamage(10);
        $action->setSpeed(10);

        $round = new Round();
        $round->setNumber(1);
        $round->setActions([$action]);

        $game->setRounds([$round]);

        if (!(new GameMapper($this->client()))->create($game)) {
            throw new RuntimeException('Game was not created.');
        }

        return $this->json($game);
    }

    /**
     * @return Response
     *
     * @throws HttpNotFoundException
     */
    public function show(string $gameId): Response
    {
        $game = (new GameMapper($this->client()))->findById($gameId);
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
        $gameMapper = new GameMapper($this->client());
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
