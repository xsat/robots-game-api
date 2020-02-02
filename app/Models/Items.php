<?php

declare(strict_types=1);

namespace App\Models;

use JsonSerializable;

/**
 * Class Items
 */
class Items implements JsonSerializable
{
    /**
     * @var array
     */
    private array $items = [];

    /**
     * Items constructor.
     *
     * @param JsonSerializable[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }


    /**
     * @return array|null
     */
    public function jsonSerialize(): ?array
    {
        $json = [];

        foreach ($this->items as $index => $item) {
            $json[$index] = $item->jsonSerialize();
        }

        return $json;
    }

}