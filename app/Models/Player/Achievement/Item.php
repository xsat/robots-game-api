<?php

declare(strict_types=1);

namespace App\Models\Player\Achievement;

use App\Models\Player\Achievement\Item\Condition;
use JsonSerializable;

/**
 * Class Item
 */
class Item implements JsonSerializable
{
    /**
     * @var string|null
     */
    private ?string $title = null;

    /**
     * @var Condition[]
     */
    private array $conditions = [];

    /**
     * @var bool
     */
    private bool $reached = false;

    /**
     * @var string|null
     */
    private ?string $dateReached = null;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return Condition[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @param Condition[] $conditions
     */
    public function setConditions(array $conditions): void
    {
        $this->conditions = $conditions;
    }

    /**
     * @return bool
     */
    public function isReached(): bool
    {
        return $this->reached;
    }

    /**
     * @param bool $reached
     */
    public function setReached(bool $reached): void
    {
        $this->reached = $reached;
    }

    /**
     * @return string|null
     */
    public function getDateReached(): ?string
    {
        return $this->dateReached;
    }

    /**
     * @param string|null $dateReached
     */
    public function setDateReached(?string $dateReached): void
    {
        $this->dateReached = $dateReached;
    }

    /**
     * @return array|null
     */
    public function jsonSerialize(): ?array
    {
        return [
            'title' => $this->getTitle(),
            'conditions' => array_map('jsonSerialize', $this->getConditions()),
            'isReached' => $this->isReached(),
            'dateReached' => $this->getDateReached(),
        ];
    }
}
