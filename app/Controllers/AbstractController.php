<?php

declare(strict_types=1);

namespace App\Controllers;

use MongoDB\Client;
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
     * @var Client
     */
    private Client $client;

    /**
     * AbstractController constructor.
     *
     * @param Request $request
     * @param Client $client
     */
    public function __construct(Request $request, Client $client)
    {
        $this->request = $request;
        $this->client = $client;
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
     * @param string $key
     *
     * @return array|null
     */
    protected function get(string $key): ?string
    {
        return $this->request->get($key);
    }

    /**
     * @return Client
     */
    protected function client(): Client
    {
        return $this->client;
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
