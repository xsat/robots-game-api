<?php

declare(strict_types=1);

namespace App\Controllers;

use MongoDB\Database;
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
     * @var Database
     */
    private Database $database;

    /**
     * AbstractController constructor.
     *
     * @param Request $request
     * @param Database $database
     */
    public function __construct(Request $request, Database $database)
    {
        $this->request = $request;
        $this->database = $database;
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
     * @return Database
     */
    protected function database(): Database
    {
        return $this->database;
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
