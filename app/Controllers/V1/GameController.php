<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractTokenController;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use App\Factory\ActionFactory;
use App\Factory\PlayerFactory;
use App\Mappers\GameMapper;
use App\Models\Game;
use App\Models\Game\Round;
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
            $game = new Game();
            (new PlayerFactory($game))->join($playerId);

            if (!(new GameMapper($this->database()))->create($game)) {
                throw new RuntimeException('Game was not created.');
            }
        } elseif (!$game->isStarted()) {
            $player = $game->findPlayer($playerId);

            if (!$player) {
                (new PlayerFactory($game))->join($playerId);
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
     * @throws RuntimeException
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

        $players = count($game->getPlayers());

        if (!$game->isEnded()) {
            $round = $game->getLastRound();

            if (!$round || $round->isEnded()) {
                $round = new Round();
                $round->setNumber(count($game->getRounds()));
                $game->addRound($round);
            }

            $action = $round->findAction($playerId);
            $target = $game->findTarget($playerId);

            if (!$action && $target) {
                $actionFactory = new ActionFactory($round);
                $targetId = $target->getPlayerId();
                $damage = $actionFactory->attack($playerId, $targetId);
                $round->setEnded($players === count($round->getActions()));
                $targetPlayer = $game->findPlayer($targetId);

                if (!$targetPlayer) {
                    throw new RuntimeException(
                        'Target player was not found.'
                    );
                }

                $targetPlayer->setCondition(
                    $targetPlayer->getCondition() - $damage
                );

                if ($targetPlayer->getCondition() <= 0) {
                    $actualPlayer = $game->findPlayer($playerId);

                    if (!$actualPlayer) {
                        throw new RuntimeException(
                            'Actual player was not found.'
                        );
                    }

                    $actualPlayer->setWinner(true);
                }

                if ($round->isEnded()) {
                    $winners = $game->getWinners();

                    if (count($winners) === $players) {
                        $actionFactory->draw();
                    } else {
                        foreach ($winners as $winner) {
                            $actionFactory->victory($winner->getPlayerId());
                        }
                    }

                    $game->setEnded(count($winners) > 0);
                }
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
