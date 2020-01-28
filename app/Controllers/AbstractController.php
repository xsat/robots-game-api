<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractController
 */
abstract class AbstractController
{
    /**
     * @param array $data
     * @param int $status
     * @param array $headers
     *
     * @return Response
     */
    protected function json(
        array $data = null,
        int $status = 200,
        array $headers = []
    ): Response {
        return new JsonResponse($data, $status, $headers);
    }
}
