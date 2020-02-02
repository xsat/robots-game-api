<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Models\Game;
use App\Models\Game\Player;
use App\Models\Game\Round;
use App\Models\Game\Round\Action;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use MongoDB\Model\BSONDocument;

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
            'players' => array_map(
                function (Player $player): array {
                    return [
                        'playerId' => new ObjectId($player->getPlayerId()),
                        'type' => $player->getHealth(),
                        'winner' => $player->isWinner(),
                    ];
                },
                $game->getPlayers()
            ),
            'rounds' => array_map(
                function (Round $round): array {
                    return [
                        'number' => $round->getNumber(),
                        'actions' => array_map(
                            function (Action $action): array {
                                return [
                                    'player_id' => new ObjectId(
                                        $action->getPlayerId()
                                    ),
                                    'type' => $action->getType(),
                                    'damage' => $action->getDamage(),
                                    'speed' => $action->getSpeed(),
                                ];
                            },
                            $round->getActions()
                        ),
                        'is_ended' => $round->isEnded(),
                        'date_started' => new UTCDateTime(
                            $round->getDateStarted()
                        ),
                        'date_ended' => new UTCDateTime(
                            $round->getDateStarted()
                        ),
                    ];
                },
                $game->getRounds()
            ),
            'is_ended' => $game->isEnded(),
            'date_started' => new UTCDateTime($game->getDateStarted()),
            'date_ended' => new UTCDateTime($game->getDateStarted()),
        ];
    }

    /**
     * @param string $id
     *
     * @return Game|null
     */
    public function findById(string $id): ?Game
    {
        /** @var BSONDocument|null $result */
        $result = $this->client->battle->games->findOne(
            ['_id' => new ObjectId($id)]
        );

        if (!$result) {
            return null;
        }

        $game = new Game();
        $this->assign($result, $game);

        return $game;
    }

    /**
     * @param BSONDocument $document
     * @param Game $game
     */
    private function assign(BSONDocument $document, Game $game): void
    {
        $game->setGameId((string)$document['_id']);
        $game->setEnded($document['is_ended']);
    }
}
