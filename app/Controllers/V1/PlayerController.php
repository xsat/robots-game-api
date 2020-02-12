<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractTokenController;
use App\Mappers\PlayerMapper;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PlayerController
 */
class PlayerController extends AbstractTokenController
{
    /**
     * @return Response
     */
    public function show(): Response
    {
        return $this->json($this->player());
    }

    /**
     * @return Response
     *
     * @throws RuntimeException
     */
    public function update(): Response
    {
        $player = $this->player();
        $player->setUsername('updated');
        if (!(new PlayerMapper($this->database()))->update($player)) {
            throw new RuntimeException('Player was not updated.');
        }

        return $this->json($player);
    }
}
