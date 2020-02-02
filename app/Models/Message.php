<?php

declare(strict_types=1);

namespace App\Models;

use JsonSerializable;

/**
 * Class Message
 */
class Message implements JsonSerializable
{
    /**
     * @var string|null
     */
    private ?string $field = null;

    /**
     * @var string|null
     */
    private ?string $message = null;

    /**
     * Message constructor.
     *
     * @param string|null $field
     * @param string|null $message
     */
    public function __construct(?string $field, ?string $message)
    {
        $this->field = $field;
        $this->message = $message;
    }

    /**
     * @return array|null
     */
    public function jsonSerialize(): ?array
    {
        return [
            'field' => $this->field,
            'message' => $this->message,
        ];
    }
}
