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
    private bool $started = false;

    /**
     * @var bool
     */
    private bool $ended = false;

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
        usort(
            $this->players,
            function (Player $player): bool {
                return $player->getPosition() === Player::POSITION_RIGHT;
            }
        );

        return $this->players;
    }

    /**
     * @param Player $player
     */
    public function addPlayer(Player $player): void
    {
        $this->players[] = $player;
    }

    /**
     * @return Player[]
     */
    public function getWinners(): array
    {
        $winners = [];

        foreach ($this->players as $player) {
            if ($player->isWinner()) {
                $winners[] = $player;
            }
        }

        return $winners;
    }

    /**
     * @param string $playerId
     *
     * @return Player|null
     */
    public function findPlayer(string $playerId): ?Player
    {
        foreach ($this->players as $player) {
            if ($player->getPlayerId() === $playerId) {
                return $player;
            }
        }

        return null;
    }

    /**
     * @param string $playerId
     *
     * @return Player|null
     */
    public function findTarget(string $playerId): ?Player
    {
        $randomPlayers = $this->getPlayers();
        array_rand($randomPlayers);

        foreach ($randomPlayers as $player) {
            if ($player->getPlayerId() !== $playerId) {
                return $player;
            }
        }

        return null;
    }

    /**
     * @return Round[]
     */
    public function getRounds(): array
    {
        return $this->rounds;
    }

    /**
     * @param Round $round
     */
    public function addRound(Round $round): void
    {
        $this->rounds[] = $round;
    }

    /**
     * @return Round|null
     */
    public function getLastRound(): ?Round
    {
        $index = array_key_last($this->rounds);

        return $this->rounds[$index] ?? null;
    }

    /**
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->started;
    }

    /**
     * @param bool $started
     */
    public function setStarted(bool $started): void
    {
        $this->started = $started;
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
     * @return array|null
     */
    public function jsonSerialize(): ?array
    {
        return [
            'gameId' => $this->getGameId(),
            'players' => $this->players,
            'rounds' => $this->rounds,
            'isStarted' => $this->isStarted(),
            'isEnded' => $this->isEnded(),
        ];
    }
}
