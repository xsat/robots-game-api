<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractController;
use App\Exceptions\ValidationException;
use App\Mappers\PlayerMapper;
use App\Mappers\PlayerTokenMapper;
use App\Models\PlayerToken;
use App\Validations\LoginValidation;
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
        $player = (new PlayerMapper($this->database()))
            ->findByUsername($data['username'] ?? '');
        $loginValidation = new LoginValidation($player);
        if (!$loginValidation->validate($data)) {
            throw new ValidationException($loginValidation);
        }

        $playerToken = new PlayerToken();
        $playerToken->setPlayerId($player->getPlayerId());
        $playerToken->setToken($playerToken->generateToken());
        $playerToken->setDateExpired(date('Y-m-d H:i:s', strtotime('+1 day')));
        (new PlayerTokenMapper($this->database()))->create($playerToken);

        return $this->json($playerToken);
    }
}
