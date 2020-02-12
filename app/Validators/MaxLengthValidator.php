<?php

declare(strict_types=1);

namespace App\Validators;

use App\Models\Message;

/**
 * Class MaxLengthValidator
 */
class MaxLengthValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private string $field;

    /**
     * @var int
     */
    private int $max;

    /**
     * @var string
     */
    private string $message;

    /**
     * MaxLengthValidator constructor.
     *
     * @param string $field
     * @param int $max
     * @param string $message
     */
    public function __construct(
        string $field,
        int $max,
        string $message = 'The :attribute may not be greater than :max characters.'
    ) {
        $this->field = $field;
        $this->max = $max;
        $this->message = $message;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid(?array $data): bool
    {
        return isset($data[$this->field])
            && is_scalar($data[$this->field])
            && strlen($data[$this->field]) <= $this->max;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage(): Message
    {
        return new Message(
            $this->field,
            str_replace(
                [':attribute', ':max'],
                [$this->field, $this->max],
                $this->message
            )
        );
    }
}
