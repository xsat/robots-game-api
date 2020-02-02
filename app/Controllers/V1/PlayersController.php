<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractController;
use App\Exceptions\HttpNotFoundException;
use App\Mappers\PlayerMapper;
use App\Mappers\PlayerTokenMapper;
use App\Models\Player;
use App\Models\PlayerToken;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PlayersController
 */
class PlayersController extends AbstractController
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

    /**
     * @param string $playerId
     *
     * @return Response
     *
     * @throws HttpNotFoundException
     */
    public function show(string $playerId): Response
    {
        $player = (new PlayerMapper($this->client()))->findById($playerId);
        if ($player === null) {
            throw new HttpNotFoundException('Player was not found.');
        }

        return $this->json($player);
    }

    /**
     * @param string $playerId
     *
     * @return Response
     *
     * @throws HttpNotFoundException
     * @throws RuntimeException
     */
    public function update(string $playerId): Response
    {
        $playerMapper = new PlayerMapper($this->client());
        $player = $playerMapper->findById($playerId);
        if ($player === null) {
            throw new HttpNotFoundException('Player was not found.');
        }

        $player->setUsername('updated');
        if (!$playerMapper->update($player)) {
            throw new RuntimeException('Player was not updated.');
        }

        return $this->json($player);
    }
}
