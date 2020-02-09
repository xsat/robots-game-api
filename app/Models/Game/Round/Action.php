<?php


declare(strict_types=1);

namespace App\Models\Game\Round;

use JsonSerializable;

/**
 * Class Action
 */
class Action implements JsonSerializable
{
    /**
     * Types
     */
    public const TYPE_ATTACK = 'attack';
    public const TYPE_MISS = 'miss';
    public const TYPE_WON = 'won';
    public const TYPE_DRAW = 'draw';

    /**
     * @var string|null
     */
    private ?string $playerId = null;

    /**
     * @var string|null
     */
    private ?string $targetId = null;

    /**
     * @var string|null
     */
    private ?string $type = null;

    /**
     * @var int|null
     */
    private ?int $damage = null;

    /**
     * @var int|null
     */
    private ?int $speed = null;

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
    public function getTargetId(): ?string
    {
        return $this->targetId;
    }

    /**
     * @param string|null $targetId
     */
    public function setTargetId(?string $targetId): void
    {
        $this->targetId = $targetId;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int|null
     */
    public function getDamage(): ?int
    {
        return $this->damage;
    }

    /**
     * @param int|null $damage
     */
    public function setDamage(?int $damage): void
    {
        $this->damage = $damage;
    }

    /**
     * @return int|null
     */
    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    /**
     * @param int|null $speed
     */
    public function setSpeed(?int $speed): void
    {
        $this->speed = $speed;
    }

    /**
     * @return array|null
     */
    public function jsonSerialize(): ?array
    {
        return [
            'playerId' => $this->getPlayerId(),
            'targetId' => $this->getTargetId(),
            'type' => $this->getType(),
            'damage' => $this->getDamage(),
            'speed' => $this->getSpeed(),
        ];
    }
}
