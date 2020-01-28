<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
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
