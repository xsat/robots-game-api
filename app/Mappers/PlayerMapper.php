<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Models\Player;
use MongoDB\BSON\ObjectId;
use MongoDB\Collection;
use MongoDB\Database;
use MongoDB\Model\BSONDocument;

/**
 * Class PlayerMapper
 */
class PlayerMapper
{
    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * PlayerMapper constructor.
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->collection = $database->selectCollection('players');
    }

    /**
     * @param Player $player
     *
     * @return bool
     */
    public function create(Player $player): bool
    {
        $result = $this->collection->insertOne(
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
        $result = $this->collection->updateOne(
            ['_id' => new ObjectId($player->getPlayerId())],
            ['$set' => $this->convert($player)]
        );

        return $result->getMatchedCount() != 0;
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
     * @param string $playerId
     *
     * @return Player|null
     */
    public function findByPlayerId(string $playerId): ?Player
    {
        /** @var BSONDocument|null $result */
        $result = $this->collection->findOne(
            ['_id' => new ObjectId($playerId)]
        );

        if (!$result) {
            return null;
        }

        $player = new Player();
        $this->assign($result, $player);

        return $player;
    }

    /**
     * @param string $username
     *
     * @return Player|null
     */
    public function findByUsername(string $username): ?Player
    {
        /** @var BSONDocument|null $result */
        $result = $this->collection->findOne(
            ['username' => $username]
        );

        if (!$result) {
            return null;
        }

        $player = new Player();
        $this->assign($result, $player);

        return $player;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return Player[]
     */
    public function list(int $limit = 10, int $offset = 0): array
    {
        $results = $this->collection->find(
            [],
            [
                'limit' => $limit,
                'skip' => $offset,
            ]
        );
        $items = [];

        /** @var BSONDocument $result */
        foreach ($results as $result) {
            $player = new Player();
            $this->assign($result, $player);
            $items[] = $player;
        }

        return $items;
    }

    /**
     * @return int
     */
    public function total(): int
    {
        return $this->collection->countDocuments();
    }

    /**
     * @param BSONDocument $document
     * @param Player $player
     */
    private function assign(BSONDocument $document, Player $player): void
    {
        $player->setPlayerId((string)$document['_id']);
        $player->setUsername($document['username']);
        $player->setPassword($document['password']);
    }
}
