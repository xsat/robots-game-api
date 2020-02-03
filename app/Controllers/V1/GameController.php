<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractTokenController;
use App\Exceptions\NotFoundException;
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
        $gameMapper = new GameMapper($this->client());
        $game = $gameMapper->findByPlayerId(
            $this->player()->getPlayerId()
        );

        if (!$game) {
            $player = new Player();
            $player->setPlayerId($this->player()->getPlayerId());
            $player->setHealth(100);

            $game = new Game();
            $game->addPlayer($player);

            if (!(new GameMapper($this->client()))->create($game)) {
                throw new RuntimeException('Game was not created.');
            }
        } elseif (!$game->isStarted()) {
            $isNewUser = true;

            foreach ($game->getPlayers() as $player) {
                if ($player->getPlayerId() === $this->player()->getPlayerId()) {
                    $isNewUser = false;
                    break;
                }
            }

            if ($isNewUser) {
                $player = new Player();
                $player->setPlayerId($this->player()->getPlayerId());
                $player->setHealth(100);
                $game->addPlayer($player);
            }

            $game->setStarted(count($game->getPlayers()) === 2);
            $gameMapper->update($game);
        } elseif ($game->isStarted()) {
            $round = end($game->getRounds());

            if (!$round) {
                $round = new Round();
                $game->addRound($round);
            }

            $action = new Action();
            $action->setPlayerId($this->player()->getPlayerId());
            $action->setType('attack');
            $action->setDamage(rand(1, 10));
            $action->setSpeed(rand(1, 10));

            foreach ($game->getPlayers() as $player) {
                if ($player->getPlayerId() !== $this->player()->getPlayerId()) {
                    $player->setHealth(
                        $player->getHealth() - $action->getDamage()
                    );

                    if ($player->getHealth() <= 0) {
                        $player->setHealth(0);
                    }
                }
            }

            $round->addAction($action);

            $gameMapper->update($game);
        }

        return $this->json($game);
    }

    /**
     * @param string $gameId
     *
     * @return Response
     *
     * @throws NotFoundException
     */
    public function show(string $gameId): Response
    {
        $game = (new GameMapper($this->client()))->findById($gameId);
        if ($game === null) {
            throw new NotFoundException('Game was not found.');
        }

        return $this->json($game);
    }

    /**
     * @param string $gameId
     *
     * @return Response
     *
     * @throws NotFoundException
     * @throws RuntimeException
     */
    public function update(string $gameId): Response
    {
        $gameMapper = new GameMapper($this->client());
        $game = $gameMapper->findById($gameId);
        if ($game === null) {
            throw new NotFoundException('Game was not found.');
        }

        $game->setEnded(true);
        if (!$gameMapper->update($game)) {
            throw new RuntimeException('Game was not updated.');
        }

        return $this->json($game);
    }
}
