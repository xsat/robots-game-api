<?php

declare(strict_types=1);

namespace App\Validations;

use App\Mappers\PlayerMapper;
use App\Validators\MaxLengthValidator;
use App\Validators\RequiredValidator;
use App\Validators\UniquePlayerValidator;

/**
 * Class PlayerCreateValidation
 */
class PlayerCreateValidation extends AbstractValidation
{
    /**
     * @var PlayerMapper
     */
    private PlayerMapper $playerMapper;

    /**
     * PlayerCreateValidation constructor.
     *
     * @param PlayerMapper $playerMapper
     */
    public function __construct(PlayerMapper $playerMapper)
    {
        $this->playerMapper = $playerMapper;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadValidators(): array
    {
        return [
            new RequiredValidator('username'),
            new MaxLengthValidator('username', 255),
            new UniquePlayerValidator('username', $this->playerMapper),
            new RequiredValidator('password'),
            new MaxLengthValidator('password', 255),
        ];
    }
}