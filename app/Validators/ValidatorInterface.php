<?php

declare(strict_types=1);

namespace App\Validators;

use App\Models\Message;

/**
 * Interface ValidatorInterface
 */
interface ValidatorInterface
{
    /**
     * @param array|null $data
     *
     * @return bool
     */
    public function isValid(?array $data): bool;

    /**
     * @return Message
     */
    public function getMessage(): Message;
}
