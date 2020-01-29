<?php

declare(strict_types=1);

namespace App\Models\Player;

use App\Models\Player\Achievement\Item;
use JsonSerializable;

/**
 * Class Achievement
 */
class Achievement implements JsonSerializable
{
    /**
     * @var string|null
     */
    private ?string $achievementId = null;

    /**
     * @var string|null
     */
    private ?string $playerId = null;

    /**
     * @var Item[]
     */
    private array $items = [];

    /**
     * @return string|null
     */
    public function getAchievementId(): ?string
    {
        return $this->achievementId;
    }

    /**
     * @param string|null $achievementId
     */
    public function setAchievementId(?string $achievementId): void
    {
        $this->achievementId = $achievementId;
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
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return array|null
     */
    public function jsonSerialize(): ?array
    {
        return array_map('jsonSerialize', $this->getItems());
    }
}