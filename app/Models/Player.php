<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Player\Achievement;
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
     * @var string|null
     */
    private ?string $username = null;

    /**
     * @var string|null
     */
    private ?string $password = null;

    /**
     * @var Achievement[]
     */
    private array $achievements = [];

    /**
     * @var string|null
     */
    private ?string $dateRegistered = null;

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
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return Achievement[]
     */
    public function getAchievements(): array
    {
        return $this->achievements;
    }

    /**
     * @param Achievement[] $achievements
     */
    public function setAchievements(array $achievements): void
    {
        $this->achievements = $achievements;
    }

    /**
     * @return string|null
     */
    public function getDateRegistered(): ?string
    {
        return $this->dateRegistered;
    }

    /**
     * @param string|null $dateRegistered
     */
    public function setDateRegistered(?string $dateRegistered): void
    {
        $this->dateRegistered = $dateRegistered;
    }

    /**
     * @return array|null
     */
    public function jsonSerialize(): ?array
    {
        return [
            'playerId' => $this->getPlayerId(),
            'username' => $this->getUsername(),
        ];
    }
}
