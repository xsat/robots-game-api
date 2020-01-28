<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 */
class DefaultController extends AbstractController
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->json(
            [
                'type' => 'error',
                'message' => 'Not Found',
            ],
            404
        );
    }
}
