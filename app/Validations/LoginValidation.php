<?php

declare(strict_types=1);

namespace App\Validations;

use App\Models\Player;
use App\Validators\MaxLengthValidator;
use App\Validators\PasswordValidator;
use App\Validators\RequiredValidator;

/**
 * Class LoginValidation
 */
class LoginValidation extends AbstractValidation
{
    /**
     * @var Player
     */
    private Player $player;

    /**
     * LoginValidation constructor.
     *
     * @param Player|null $player
     */
    public function __construct(?Player $player)
    {
        $this->player = $player ?? new Player();
    }

    /**
     * {@inheritDoc}
     */
    protected function loadValidators(): array
    {
        return [
            new RequiredValidator('username'),
            new MaxLengthValidator('username', 255),
            new RequiredValidator('password'),
            new MaxLengthValidator('password', 255),
            new PasswordValidator(
                'password',
                $this->player->getPassword(),
                'The username or password is incorrect.'
            )
        ];
    }
}