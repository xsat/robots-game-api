<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use JsonSerializable;
use Throwable;

/**
 * Class NotFoundException
 */
class NotFoundException extends Exception implements JsonSerializable
{
    /**
     * NotFoundException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = '',
        int $code = 404,
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
            'type' => 'not-found',
            'message' => $this->getMessage(),
        ];
    }
}
