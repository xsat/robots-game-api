<?php

declare(strict_types=1);

namespace App\Models\Game;

use App\Models\Game\Round\Action;
use JsonSerializable;

/**
 * Class Round
 */
class Round implements JsonSerializable
{
    /**
     * @var int
     */
    private int $number = 0;

    /**
     * @var Action[]
     */
    private array $actions = [];

    /**
     * @var string|null
     */
    private ?string $dateStarted = null;

    /**
     * @var string|null
     */
    private ?string $dateEnded = null;

    /**
     * @return int|null
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * @param int|null $number
     */
    public function setNumber(?int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return Action[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param Action[] $actions
     */
    public function setActions(array $actions): void
    {
        $this->actions = $actions;
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
            'number' => $this->getNumber(),
            'actions' => [],
            'dateStarted' => $this->getDateStarted(),
            'dateEnded' => $this->getDateEnded(),
        ];
    }
}
