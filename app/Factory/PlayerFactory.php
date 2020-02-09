<?php

declare(strict_types=1);

namespace App\Factory;

use App\Models\Game;
use App\Models\Game\Player;

/**
 * Class PlayerFactory
 */
class PlayerFactory
{
    /**
     * @var Game
     */
    private Game $game;

    /**
     * PlayerFactory constructor.
     *
     * @param Game $game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * @param string $playerId
     */
    public function join(string $playerId): void
    {
        $player = new Player();
        $player->setPlayerId($playerId);
        $player->setHealth(100);
        $player->setCondition(100);
        $this->rand($player);

        $this->game->addPlayer($player);
    }

    /**
     * @param Player $player
     */
    private function rand(Player $player): void
    {
        $positions = [
            Player::POSITION_LEFT => 1,
            Player::POSITION_RIGHT => 1,
        ];

        $colors = [
            Player::COLOR_BLUE => 1,
            Player::COLOR_RED => 1,
        ];

        foreach ($this->game->getPlayers() as $other) {
            if ($positions[$other->getPosition()]--) {
                unset($positions[$other->getPosition()]);
            }

            if ($colors[$other->getColor()]--) {
                unset($colors[$other->getColor()]);
            }
        }

        $player->setPosition($this->randValue($positions));
        $player->setColor($this->randValue($colors));
    }

    /**
     * @param array $values
     *
     * @return string|null
     */
    private function randValue(array $values): ?string
    {
        return array_rand($values) ?: null;
    }
}
