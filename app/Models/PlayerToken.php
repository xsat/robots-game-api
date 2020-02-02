<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Class PlayerToken
 */
class PlayerToken
{
    /**
     * @var string|null
     */
    private ?string $playerTokenId = null;

    /**
     * @var string|null
     */
    private ?string $playerId = null;

    /**
     * @var string|null
     */
    private ?string $token = null;

    /**
     * @var string|null
     */
    private ?string $dateGenerated = null;

    /**
     * @var string|null
     */
    private ?string $dateExpired = null;

    /**
     * @return string|null
     */
    public function getPlayerTokenId(): ?string
    {
        return $this->playerTokenId;
    }

    /**
     * @param string|null $playerTokenId
     */
    public function setPlayerTokenId(?string $playerTokenId): void
    {
        $this->playerTokenId = $playerTokenId;
    }

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
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return string|null
     */
    public function getDateGenerated(): ?string
    {
        return $this->dateGenerated;
    }

    /**
     * @param string|null $dateGenerated
     */
    public function setDateGenerated(?string $dateGenerated): void
    {
        $this->dateGenerated = $dateGenerated;
    }

    /**
     * @return string|null
     */
    public function getDateExpired(): ?string
    {
        return $this->dateExpired;
    }

    /**
     * @param string|null $dateExpired
     */
    public function setDateExpired(?string $dateExpired): void
    {
        $this->dateExpired = $dateExpired;
    }
}
