<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Models\Player;
use MongoDB\BSON\ObjectId;
use MongoDB\Client;

/**
 * Class PlayerMapper
 */
class PlayerMapper
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * PlayerMapper constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param Player $player
     *
     * @return bool
     */
    public function create(Player $player): bool
    {
        $result = $this->client->battle->players->insertOne(
            $this->convert($player)
        );

        $player->setPlayerId((string)$result->getInsertedId());

        return $player->getPlayerId() !== null;
    }

    /**
     * @param Player $player
     *
     * @return bool
     */
    public function update(Player $player): bool
    {
        $result = $this->client->battle->players->updateOne(
            ['_id' => new ObjectId($player->getPlayerId())],
            ['$set' => $this->convert($player)]
        );

        return $result->getModifiedCount() != 0;
    }

    /**
     * @param Player $player
     *
     * @return array
     */
    private function convert(Player $player): array
    {
        return [
            'username' => $player->getUsername(),
            'password' => $player->getPassword(),
        ];
    }

    /**
     * @param string $id
     *
     * @return Player|null
     */
    public function findById(string $id): ?Player
    {
        $result = $this->client->battle->players->findOne(
            ['_id' => new ObjectId($id)]
        );

        if (!$result) {
            return null;
        }

        $player = new Player();
        $player->setPlayerId((string)$result['_id']);
        $player->setUsername($result['username']);
        $player->setPassword($result['password']);

        return $player;
    }
}
