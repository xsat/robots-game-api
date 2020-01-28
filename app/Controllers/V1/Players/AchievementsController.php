<?php

namespace App\Controllers\V1\Players;

use App\Controllers\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AchievementsController
 */
class AchievementsController extends AbstractController
{
    /**
     * @param string $playerId
     *
     * @return Response
     */
    public function show(string $playerId): Response
    {
        return $this->json(
            [
                'playerId' => $playerId,
            ]
        );
    }
}
