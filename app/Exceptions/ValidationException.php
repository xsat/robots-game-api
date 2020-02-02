<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Models\Items;
use App\Models\Message;
use Exception;
use JsonSerializable;
use Throwable;

/**
 * Class ValidationException
 */
class ValidationException extends Exception implements JsonSerializable
{
    /**
     * @var Message[]
     */
    private array $messages = [];

    /**
     * ValidationException constructor.
     *
     * @param Message[] $messages
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        array $messages = [],
        int $code = 400,
        Throwable $previous = null
    ) {
        $this->messages = $messages;

        parent::__construct('', $code, $previous);
    }

    /**
     * @return array|null
     */
    public function jsonSerialize(): ?array
    {
        return [
            'type' => 'validation',
            'messages' => (new Items($this->messages))->jsonSerialize(),
        ];
    }
}
