<?php

declare(strict_types=1);

namespace App\Models\Game;

use JsonSerializable;

/**
 * Class Player
 */
class Player implements JsonSerializable
{
    /**
     * Positions
     */
    public const POSITION_LEFT = 'left';
    public const POSITION_RIGHT = 'right';

    /**
     * Colors
     */
    public const COLOR_BLUE = 'blue';
    public const COLOR_RED = 'red';

    /**
     * @var string|null
     */
    private ?string $playerId = null;

    /**
     * @var int
     */
    private int $health = 0;

    /**
     * @var int
     */
    private int $condition = 0;

    /**
     * @var string|null
     */
    private ?string $position = null;

    /**
     * @var string|null
     */
    private ?string $color = null;

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
     * @return int
     */
    public function getCondition(): int
    {
        return $this->condition;
    }

    /**
     * @param int $condition
     */
    public function setCondition(int $condition): void
    {
        $this->condition = $condition;
    }

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @param string|null $position
     */
    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     */
    public function setColor(?string $color): void
    {
        $this->color = $color;
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
        return [
            'playerId' => $this->getPlayerId(),
            'health' => $this->getHealth(),
            'condition' => $this->getCondition(),
            'color' => $this->getColor(),
            'position' => $this->getPosition(),
            'isWinner' => $this->isWinner(),
        ];
    }
}
