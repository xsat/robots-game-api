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
        $gameMapper = new GameMapper($this->client());
        $game = $gameMapper->findNotStarted(
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
            $player = $game->findPlayer($this->player()->getPlayerId());

            if (!$player) {
                $player = new Player();
                $player->setPlayerId($this->player()->getPlayerId());
                $player->setHealth(100);
                $game->addPlayer($player);
            }

            $game->setStarted(count($game->getPlayers()) === 2);
            $gameMapper->update($game);
        }

        return $this->json($game);
    }

    /**
     * @return Response
     *
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function play(): Response {
        $gameMapper = new GameMapper($this->client());
        $game = $gameMapper->findByPlayerId(
            $this->player()->getPlayerId()
        );

        if (!$game) {
            throw new NotFoundException('Game was not found.');
        }

        if (!$game->isStarted()) {
            throw new ForbiddenException('Game was not started.');
        }

        if ($game->isEnded()) {
            throw new ForbiddenException('Game was ended.');
        }

        $round = $game->getLastRound();

        if (!$round || $round->isEnded()) {
            $round = new Round();
            $game->addRound($round);
        }

        $action = $round->findAction($this->player()->getPlayerId());
        $target = $game->findTarget($this->player()->getPlayerId());

        if (!$action && $target) {
            $action = new Action();
            $action->setPlayerId($this->player()->getPlayerId());
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

        return $this->json($game);
    }

    /**
     * @return Response
     *
     * @throws NotFoundException
     * @throws ForbiddenException
     */
    public function end(): Response
    {
        $gameMapper = new GameMapper($this->client());
        $game = $gameMapper->findByPlayerId(
            $this->player()->getPlayerId()
        );

        if (!$game) {
            throw new NotFoundException('Game was not found.');
        }

        $game->setEnded(true);
        $gameMapper->update($game);

        return $this->json($game);
    }
}
