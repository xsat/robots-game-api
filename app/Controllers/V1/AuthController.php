<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractController;
use App\Exceptions\ValidationException;
use App\Mappers\PlayerMapper;
use App\Mappers\PlayerTokenMapper;
use App\Models\Message;
use App\Models\PlayerToken;
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
        $data = $this->data();
        $player = (new PlayerMapper($this->client()))->findByUsername($data['username'] ?? '');
        if (!$player || !password_verify($data['password'] ?? '', $player->getPassword())) {
            throw new ValidationException(
                [
                    new Message(
                        'username',
                        'Your username can not be empty.'
                    ),
                    new Message(
                        'password',
                        'Your username or password is invalid.'
                    )
                ]
            );
        }

        $playerToken = new PlayerToken();
        $playerToken->setPlayerId($player->getPlayerId());
        $playerToken->setToken($playerToken->generateToken());
        $playerToken->setDateExpired(date('Y-m-d H:i:s', strtotime('+1 day')));
        (new PlayerTokenMapper($this->client()))->create($playerToken);

        return $this->json($playerToken);
    }
}
