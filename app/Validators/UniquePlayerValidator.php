<?php

declare(strict_types=1);

namespace App\Validators;

use App\Mappers\PlayerMapper;
use App\Models\Message;

/**
 * Class UniquePlayerValidator
 */
class UniquePlayerValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private string $field;

    /**
     * @var PlayerMapper
     */
    private PlayerMapper $playerMapper;

    /**
     * @var string
     */
    private string $message;

    /**
     * UniquePlayerValidator constructor.
     *
     * @param string $field
     * @param PlayerMapper $playerMapper
     * @param string $message
     */
    public function __construct(
        string $field,
        PlayerMapper $playerMapper,
        string $message = 'The :attribute has already been taken.'
    ) {
        $this->field = $field;
        $this->playerMapper = $playerMapper;
        $this->message = $message;
    }


    /**
     * {@inheritDoc}
     */
    public function isValid(?array $data): bool
    {
        return isset($data[$this->field])
            && is_scalar($data[$this->field])
            && $this->playerMapper
                ->findByUsername((string)$data[$this->field]) === null;
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
