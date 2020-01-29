<?php

declare(strict_types=1);

namespace App\Models\Player\Game;

use JsonSerializable;

/**
 * Class Player
 */
class Player implements JsonSerializable
{
    /**
     * @var string|null
     */
    private ?string $playerId = null;

    /**
     * @var int
     */
    private int $health = 0;

    /**
     * @var bool
     */
    private bool $winner = false;

    /**
     * @return string|null
     */
    public function getPlayerId(): ?string
    {
        return $this->playerId;
    }

    /**
     * @param string|null $playerId
     */
    public function setPlayerId(?string $playerId): void
    {
        $this->playerId = $playerId;
    }

    /**
     * @return int
     */
    public function getHealth(): int
    {
        return $this->health;
    }

    /**
     * @param int $health
     */
    public function setHealth(int $health): void
    {
        $this->health = $health;
    }

    /**
     * @return bool
     */
    public function isWinner(): bool
    {
        return $this->winner;
    }

    /**
     * @param bool $winner
     */
    public function setWinner(bool $winner): void
    {
        $this->winner = $winner;
    }

    /**
     * @return array|null
     */
    public function jsonSerialize(): ?array
    {
        return null;
    }
}
