<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractTokenController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AchievementsController
 */
class AchievementsController extends AbstractTokenController
{
    /**
     * @return Response
     */
    public function show(): Response
    {
        return $this->json($this->player()->getAchievements());
    }
}
