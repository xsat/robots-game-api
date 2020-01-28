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
                'type' => '1',
                'message' => 'Not Found',
            ],
        );
    }

    /**
     * @param string $test
     * @return Response
     */
    public function test(string $test): Response
    {
        return $this->json(
            [
                'test' => $test,
            ],
        );
    }
}
