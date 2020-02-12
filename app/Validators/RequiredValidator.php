<?php

declare(strict_types=1);

namespace App\Validators;

use App\Models\Message;

/**
 * Class RequiredValidator
 */
class RequiredValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private string $field;

    /**
     * @var string
     */
    private string $message;

    /**
     * RequiredValidator constructor.
     *
     * @param string $field
     * @param string $message
     */
    public function __construct(
        string $field,
        string $message = 'The :attribute field is required.'
    ) {
        $this->field = $field;
        $this->message = $message;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid(?array $data): bool
    {
        return isset($data[$this->field])
            && is_scalar($data[$this->field])
            && !empty(trim($data[$this->field]));
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage(): Message
    {
        return new Message(
            $this->field,
            str_replace(':attribute', $this->field, $this->message)
        );
    }
}
