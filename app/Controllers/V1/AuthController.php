<?php

declare(strict_types=1);

namespace App\Controllers\V1;

use App\Controllers\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthController
 */
class AuthController extends AbstractController
{
    /**
     * @return Response
     */
    public function login(): Response
    {
        return $this->json();
    }
}
