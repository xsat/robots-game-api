<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractTokenController;
use App\Exceptions\ForbiddenException;
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
    public function start(): Response
    {
        $playerId = $this->player()->getPlayerId();
        $gameMapper = new GameMapper($this->database());
        $game = $gameMapper->findNotStarted($playerId);

        if (!$game) {
            $player = new Player();
            $player->setPlayerId($playerId);
            $player->setHealth(100);

            $game = new Game();
            $game->addPlayer($player);

            if (!(new GameMapper($this->database()))->create($game)) {
                throw new RuntimeException('Game was not created.');
            }
        } elseif (!$game->isStarted()) {
            $player = $game->findPlayer($playerId);

            if (!$player) {
                $player = new Player();
                $player->setPlayerId($playerId);
                $player->setHealth(100);
                $game->addPlayer($player);
            }

            $game->setStarted(count($game->getPlayers()) === 2);
            $gameMapper->update($game);
        }

        return $this->json($game);
    }

    /**
     * @param string $gameId
     *
     * @return Response
     *
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function play(string $gameId): Response
    {
        $playerId = $this->player()->getPlayerId();
        $gameMapper = new GameMapper($this->database());
        $game = $gameMapper->findByGameIdAndPlayerId($gameId, $playerId);

        if (!$game) {
            throw new NotFoundException('Game was not found.');
        }

        if (!$game->isStarted()) {
            throw new ForbiddenException('Game was not started.');
        }

        if (!$game->isEnded()) {
            $round = $game->getLastRound();

            if (!$round || $round->isEnded()) {
                $round = new Round();
                $game->addRound($round);
            }

            $action = $round->findAction($playerId);
            $target = $game->findTarget($playerId);

            if (!$action && $target) {
                $action = new Action();
                $action->setPlayerId($playerId);
                $action->setTargetId($target->getPlayerId());
                $action->setType('attack');
                $action->setDamage(rand(1, 10));
                $action->setSpeed(rand(1, 10));
                $round->addAction($action);

                $player = $game->findPlayer($target->getPlayerId());

                if ($player) {
                    $player->setHealth(
                        $player->getHealth() - $action->getDamage()
                    );

                    if ($player->getHealth() <= 0) {
                        $player->setHealth(0);
                        $game->setEnded(true);
                    }
                }

                $round->setEnded(
                    count($game->getPlayers()) === count($round->getActions())
                );
            }

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
    public function end(string $gameId): Response
    {
        $playerId = $this->player()->getPlayerId();
        $gameMapper = new GameMapper($this->database());
        $game = $gameMapper->findByGameIdAndPlayerId($gameId, $playerId);

        if (!$game) {
            throw new NotFoundException('Game was not found.');
        }

        $game->setEnded(true);
        $gameMapper->update($game);

        return $this->json($game);
    }
}
