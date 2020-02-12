<?php

declare(strict_types=1);

namespace App\Validations;

use App\Models\Message;
use App\Validators\ValidatorInterface;

/**
 * Class AbstractValidation
 */
abstract class AbstractValidation implements MessagesInterface
{
    /**
     * @var Message
     */
    private array $messages = [];

    /**
     * @param array|null $data
     *
     * @return bool
     */
    public function validate(?array $data): bool
    {
        $this->messages = [];

        foreach ($this->loadValidators() as $validator) {
            if (!$validator->isValid($data)) {
                $this->messages[] = $validator->getMessage();
            }
        }

        return empty($this->messages);
    }

    /**
     * @return ValidatorInterface[]
     */
    abstract protected function loadValidators(): array;

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
