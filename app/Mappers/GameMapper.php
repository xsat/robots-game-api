<?php

declare(strict_types=1);

namespace App\Mappers;

use MongoDB\Client;

/**
 * Class GameMapper
 */
class GameMapper
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * GameMapper constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}