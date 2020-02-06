<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Models\Game;
use App\Models\Game\Player;
use App\Models\Game\Round;
use App\Models\Game\Round\Action;
use MongoDB\BSON\ObjectId;
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
                        'player_id' => new ObjectId($player->getPlayerId()),
                        'health' => $player->getHealth(),
                        'is_winner' => $player->isWinner(),
                    ];
                },
                $game->getPlayers()
            ),
            'rounds' => array_map(
                function (Round $round): array {
                    return [
                        'actions' => array_map(
                            function (Action $action): array {
                                return [
                                    'player_id' => new ObjectId(
                                        $action->getPlayerId()
                                    ),
                                    'target_id' => new ObjectId(
                                        $action->getTargetId()
                                    ),
                                    'type' => $action->getType(),
                                    'damage' => $action->getDamage(),
                                    'speed' => $action->getSpeed(),
                                ];
                            },
                            $round->getActions()
                        ),
                        'is_ended' => $round->isEnded(),
                    ];
                },
                $game->getRounds()
            ),
            'is_started' => $game->isStarted(),
            'is_ended' => $game->isEnded(),
        ];
    }

    /**
     * @param string $playerId
     *
     * @return Game|null
     */
    public function findNotStarted(string $playerId): ?Game
    {
        /** @var BSONDocument|null $result */
        $result = $this->client->battle->games->findOne(
            [
                '$or' => [
                    [
                        'players.player_id' => new ObjectId($playerId),
                        'is_started' => false,
                        'is_ended' => false,
                    ],
                    [
                        'is_started' => false,
                        'is_ended' => false,
                    ],
                ]
            ]
        );

        if (!$result) {
            return null;
        }

        $game = new Game();
        $this->assign($result, $game);

        return $game;
    }

    /**
     * @param string $playerId
     *
     * @return Game|null
     */
    public function findByPlayerId(string $playerId): ?Game
    {
        /** @var BSONDocument|null $result */
        $result = $this->client->battle->games->findOne(
            [
                'players.player_id' => new ObjectId($playerId),
                'is_started' => true,
                'is_ended' => false,
            ]
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

        foreach ($document['players'] as $playerDocument) {
            $player = new Player();
            $player->setPlayerId((string)$playerDocument['player_id']);
            $player->setHealth($playerDocument['health']);
            $player->setWinner($playerDocument['is_winner']);

            $game->addPlayer($player);
        }

        foreach ($document['rounds'] as $roundDocument) {
            $round = new Round();

            foreach ($roundDocument['actions'] as $actionDocument) {
                $action = new Action();
                $action->setPlayerId((string)$actionDocument['player_id']);
                $action->setTargetId((string)$actionDocument['target_id']);
                $action->setType($actionDocument['type']);
                $action->setDamage($actionDocument['damage']);
                $action->setSpeed($actionDocument['speed']);

                $round->addAction($action);
            }

            $round->setEnded($roundDocument['is_ended']);

            $game->addRound($round);
        }

        $game->setStarted($document['is_started']);
        $game->setEnded($document['is_ended']);
    }
}
