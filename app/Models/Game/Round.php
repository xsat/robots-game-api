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
     * @var bool
     */
    private bool $ended = false;


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
     * @param Action $action
     */
    public function addAction(Action $action): void {
        $this->actions[] = $action;
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
            'number' => $this->getNumber(),
            'actions' => $this->actions,
            'isEnded' => $this->isEnded(),
        ];
    }
}
