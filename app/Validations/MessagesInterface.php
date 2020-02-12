<?php

declare(strict_types=1);

namespace App\Validations;

use App\Models\Message;

/**
 * Interface MessagesInterface
 */
interface MessagesInterface
{
    /**
     * @return Message[]
     */
    public function getMessages(): array;
}
