<?php

declare(strict_types=1);

namespace App\Validators;

use App\Models\Message;

/**
 * Class PasswordValidator
 */
class PasswordValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private string $field;

    /**
     * @var string|null
     */
    private ?string $hash;

    /**
     * @var string|null
     */
    private ?string $message;

    /**
     * PasswordValidator constructor.
     *
     * @param string $field
     * @param string|null $hash
     * @param string $message
     */
    public function __construct(
        string $field,
        ?string $hash,
        string $message = 'The password is incorrect.'
    ) {
        $this->field = $field;
        $this->hash = $hash;
        $this->message = $message;
    }


    /**
     * {@inheritDoc}
     */
    public function isValid(?array $data): bool
    {
        return isset($data[$this->field])
            && is_scalar($data[$this->field])
            && password_verify($data[$this->field], $this->hash);
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage(): Message
    {
        return new Message($this->field, $this->message);
    }
}
