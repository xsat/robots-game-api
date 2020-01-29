<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Models\Game;
use MongoDB\BSON\ObjectId;
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

    /**
     * @param Game $game
     *
     * @return bool
     */
    public function create(Game $game): bool
    {
        $result = $this->client->battle->games->insertOne(
            $this->convert($game)
        );

        $game->setGameId((string)$result->getInsertedId());

        return $game->getGameId() !== null;
    }

    /**
     * @param Game $game
     *
     * @return bool
     */
    public function update(Game $game): bool
    {
        $result = $this->client->battle->games->updateOne(
            ['_id' => new ObjectId($game->getGameId())],
            ['$set' => $this->convert($game)]
        );

        return $result->getMatchedCount() != 0;
    }

    /**
     * @param Game $game
     *
     * @return array
     */
    private function convert(Game $game): array
    {
        return [
            'players' => [],
            'rounds' => [],
            'is_ended' => $game->isEnded(),
            'date_started' => $game->getDateStarted(),
            'date_ended' => $game->getDateEnded(),
        ];
    }

    /**
     * @param string $id
     *
     * @return Game|null
     */
    public function findById(string $id): ?Game
    {
        $result = $this->client->battle->games->findOne(
            ['_id' => new ObjectId($id)]
        );

        if (!$result) {
            return null;
        }

        $game = new Game();
        $game->setGameId((string)$result['_id']);
        $game->setEnded($result['is_ended']);
        $game->setDateStarted($result['date_started']);
        $game->setDateEnded($result['date_ended']);

        return $game;
    }
}