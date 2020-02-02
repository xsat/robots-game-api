<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use JsonSerializable;
use Throwable;

/**
 * Class ForbiddenException
 */
class ForbiddenException extends Exception implements JsonSerializable
{
    /**
     * ForbiddenException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = '',
        int $code = 403,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array|null
     */
    public function jsonSerialize(): ?array
    {
        return [
            'type' => 'forbidden',
            'message' => $this->getMessage(),
        ];
    }
}
