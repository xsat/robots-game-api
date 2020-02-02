<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Mappers\PlayerMapper;
use App\Mappers\PlayerTokenMapper;
use App\Models\Player;
use App\Models\PlayerToken;
use RuntimeException;

/**
 * Class RegisterController
 */
class RegisterController extends AbstractController
{
    /**
     * @return Response
     */
    public function create(): Response
    {
        $player = new Player();
        $player->setUsername('xsat');
        $player->setPassword(password_hash('123456', PASSWORD_BCRYPT));
        if (!(new PlayerMapper($this->client()))->create($player)) {
            throw new RuntimeException('Player was not created.');
        }

        $playerToken = new PlayerToken();
        $playerToken->setPlayerId($player->getPlayerId());
        $playerToken->setToken('test_token');
        $playerToken->setDateExpired(date('Y-m-d H:i:s', strtotime('+1 day')));

        (new PlayerTokenMapper($this->client()))->create($playerToken);

        return $this->json($player);
    }
}
