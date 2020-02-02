<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Game\Player;
use App\Models\Game\Round;
use JsonSerializable;

/**
 * Class Game
 */
class Game implements JsonSerializable
{
    /**
     * @var string|null
     */
    private ?string $gameId = null;

    /**
     * @var Player[]
     */
    private array $players = [];

    /**
     * @var Round[]
     */
    private array $rounds = [];

    /**
     * @var bool
     */
    private bool $ended = false;

    /**
     * @var string|null
     */
    private ?string $dateStarted = null;

    /**
     * @var string|null
     */
    private ?string $dateEnded = null;

    /**
     * @return string|null
     */
    public function getGameId(): ?string
    {
        return $this->gameId;
    }

    /**
     * @param string|null $gameId
     */
    public function setGameId(?string $gameId): void
    {
        $this->gameId = $gameId;
    }

    /**
     * @return Player[]
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * @param Player[] $players
     */
    public function setPlayers(array $players): void
    {
        $this->players = $players;
    }

    /**
     * @return Round[]
     */
    public function getRounds(): array
    {
        return $this->rounds;
    }

    /**
     * @param Round[] $rounds
     */
    public function setRounds(array $rounds): void
    {
        $this->rounds = $rounds;
    }

    /**
     * @return string|null
     */
    public function getDateStarted(): ?string
    {
        return $this->dateStarted;
    }

    /**
     * @param string|null $dateStarted
     */
    public function setDateStarted(?string $dateStarted): void
    {
        $this->dateStarted = $dateStarted;
    }

    /**
     * @return bool
     */
    public function isEnded(): bool
    {
        return $this->ended;
    }

    /**
     * @param bool $ended
     */
    public function setEnded(bool $ended): void
    {
        $this->ended = $ended;
    }

    /**
     * @return string|null
     */
    public function getDateEnded(): ?string
    {
        return $this->dateEnded;
    }

    /**
     * @param string|null $dateEnded
     */
    public function setDateEnded(?string $dateEnded): void
    {
        $this->dateEnded = $dateEnded;
    }

    /**
     * @return array|null
     */
    public function jsonSerialize(): ?array
    {
        return [
            'gameId' => $this->getGameId(),
            'players' => (new Items($this->players))->jsonSerialize(),
            'rounds' => (new Items($this->rounds))->jsonSerialize(),
            'isEnded' => $this->isEnded(),
            'dateStarted' => $this->getDateStarted(),
            'dateEnded' => $this->getDateEnded(),
        ];
    }
}
