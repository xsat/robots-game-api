<?php

declare(strict_types=1);

namespace App\Factory;

use App\Models\Game\Round;
use App\Models\Game\Round\Action;

/**
 * Class ActionFactory
 */
class ActionFactory
{
    /**
     * @var Round
     */
    private Round $round;

    /**
     * ActionFactory constructor.
     *
     * @param Round $round
     */
    public function __construct(Round $round)
    {
        $this->round = $round;
    }

    /**
     * @param string $playerId
     * @param string $targetId
     *
     * @return int
     */
    public function attack(string $playerId, string $targetId): int
    {
        $action = new Action();
        $action->setPlayerId($playerId);
        $action->setTargetId($targetId);
        $damage = rand(0, 50);

        if ($damage) {
            $action->setType(Action::TYPE_ATTACK);
        } else {
            $action->setType(Action::TYPE_MISS);
        }

        $action->setDamage($damage);
        $action->setSpeed(rand(1, 10));
        $this->round->addAction($action);

        return $damage;
    }

    /**
     * @param string $playerId
     */
    public function victory(string $playerId): void
    {
        $action = new Action();
        $action->setPlayerId($playerId);
        $action->setType(Action::TYPE_WON);
        $this->round->addAction($action);
    }

    public function draw(): void
    {
        $action = new Action();
        $action->setType(Action::TYPE_DRAW);
        $this->round->addAction($action);
    }
}
