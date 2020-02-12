<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractController;
use App\Exceptions\ValidationException;
use App\Mappers\PlayerMapper;
use App\Mappers\PlayerTokenMapper;
use App\Models\Player;
use App\Models\PlayerToken;
use App\Validations\PlayerCreateValidation;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegisterController
 */
class RegisterController extends AbstractController
{
    /**
     * @return Response
     *
     * @throws ValidationException
     */
    public function create(): Response
    {
        $data = $this->data();
        $playerMapper = new PlayerMapper($this->database());
        $playerCreateValidation = new PlayerCreateValidation($playerMapper);
        if (!$playerCreateValidation->validate($data)) {
            throw new ValidationException($playerCreateValidation);
        }

        $player = new Player();
        $player->setUsername($data['username']);
        $player->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        if (!$playerMapper->create($player)) {
            throw new RuntimeException('Player was not created.');
        }

        $playerToken = new PlayerToken();
        $playerToken->setPlayer($player);
        $playerToken->setToken($playerToken->generateToken());
        $playerToken->setDateExpired(date('Y-m-d H:i:s', strtotime('+1 day')));
        (new PlayerTokenMapper($this->database()))->create($playerToken);

        return $this->json($playerToken);
    }
}
