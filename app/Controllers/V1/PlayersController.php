<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractController;
use App\Exceptions\HttpNotFoundException;
use App\Mappers\PlayerMapper;
use App\Models\Player;
use MongoDB\Client;
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
        if (!(new PlayerMapper(new Client()))->create($player)) {
            throw new RuntimeException('Player was not created.');
        }

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
        $player = (new PlayerMapper(new Client()))->findById($playerId);
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
        $playerMapper = new PlayerMapper(new Client());
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
