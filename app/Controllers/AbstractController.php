<?php

declare(strict_types=1);

namespace App\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractController
 */
abstract class AbstractController
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * AbstractController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return array|null
     */
    protected function data(): ?array
    {
        return json_decode(
            $this->request->getContent(),
            true
        ) ?: null;
    }

    /**
     * @param mixed $data
     * @param int $status
     * @param array $headers
     *
     * @return Response
     */
    protected function json(
        $data = null,
        int $status = 200,
        array $headers = []
    ): Response {
        return new JsonResponse($data, $status, $headers);
    }
}
