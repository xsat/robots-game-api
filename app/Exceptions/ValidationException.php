<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Validations\MessagesInterface;
use Exception;
use JsonSerializable;
use Throwable;

/**
 * Class ValidationException
 */
class ValidationException extends Exception implements JsonSerializable
{
    /**
     * @var MessagesInterface
     */
    private MessagesInterface $messages;

    /**
     * ValidationException constructor.
     *
     * @param MessagesInterface $messages
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        MessagesInterface $messages,
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
            'messages' => $this->messages->getMessages(),
        ];
    }
}
