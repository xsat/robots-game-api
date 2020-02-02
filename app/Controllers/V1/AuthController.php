<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractController;
use App\Exceptions\ValidationException;
use App\Mappers\PlayerMapper;
use App\Models\Message;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthController
 */
class AuthController extends AbstractController
{
    /**
     * @return Response
     *
     * @throws ValidationException
     */
    public function login(): Response
    {
        $player = (new PlayerMapper($this->client()))->findByUsername('xsat');
        if (!$player || !password_verify('1234561', $player->getPassword())) {
            throw new ValidationException(
                [
                    new Message(
                        'password',
                        'Your username or password is invalid.'
                    )
                ]
            );
        }

        var_dump($player);

        return $this->json();
    }
}
