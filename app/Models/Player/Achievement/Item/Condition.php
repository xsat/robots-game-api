<?php

declare(strict_types=1);

namespace App\Models\Player\Achievement\Item;

use JsonSerializable;

/**
 * Class Condition
 */
class Condition implements JsonSerializable
{
    /**
     * @var string|null
     */
    private ?string $type = null;

    /**
     * @var string|null
     */
    private ?string $expected = null;

    /**
     * @var string|null
     */
    private ?string $current = null;

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
     * @return string|null
     */
    public function getExpected(): ?string
    {
        return $this->expected;
    }

    /**
     * @param string|null $expected
     */
    public function setExpected(?string $expected): void
    {
        $this->expected = $expected;
    }

    /**
     * @return string|null
     */
    public function getCurrent(): ?string
    {
        return $this->current;
    }

    /**
     * @param string|null $current
     */
    public function setCurrent(?string $current): void
    {
        $this->current = $current;
    }

    /**
     * @return array|null
     */
    public function jsonSerialize(): ?array
    {
        return [
            'type' => $this->getType(),
            'expected' => $this->getExpected(),
            'current' => $this->getCurrent(),
        ];
    }
}
