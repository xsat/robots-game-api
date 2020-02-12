<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Helpers\MongoHelper;
use App\Models\Game;
use App\Models\Game\Player;
use App\Models\Game\Round;
use App\Models\Game\Round\Action;
use MongoDB\Collection;
use MongoDB\Database;
use MongoDB\Model\BSONDocument;

/**
 * Class GameMapper
 */
class GameMapper
{
    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * GameMapper constructor.
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->collection = $database->selectCollection('games');
    }

    /**
     * @param Game $game
     *
     * @return bool
     */
    public function create(Game $game): bool
    {
        $result = $this->collection->insertOne(
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
        $result = $this->collection->updateOne(
            ['_id' => MongoHelper::objectId($game->getGameId())],
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
                        'player_id' => MongoHelper::objectId(
                            $player->getPlayerId()
                        ),
                        'health' => $player->getHealth(),
                        'condition' => $player->getCondition(),
                        'position' => $player->getPosition(),
                        'color' => $player->getColor(),
                        'is_winner' => $player->isWinner(),
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
                                $data = [];

                                if ($action->getPlayerId() !== null) {
                                    $data['player_id'] = MongoHelper::objectId(
                                        $action->getPlayerId()
                                    );
                                }

                                if ($action->getTargetId() !== null) {
                                    $data['target_id'] = MongoHelper::objectId(
                                        $action->getTargetId()
                                    );
                                }

                                return $data +
                                    [
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
     * @param string $gameId
     * @param string $playerId
     *
     * @return Game|null
     */
    public function findByGameIdAndPlayerId(
        string $gameId,
        string $playerId
    ): ?Game {
        /** @var BSONDocument|null $result */
        $result = $this->collection->findOne(
            [
                '_id' => MongoHelper::objectId($gameId),
                'players.player_id' => MongoHelper::objectId($playerId),
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
    public function findNotStarted(string $playerId): ?Game
    {
        /** @var BSONDocument|null $result */
        $result = $this->collection->findOne(
            [
                '$or' => [
                    [
                        'players.player_id' => MongoHelper::objectId($playerId),
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
            $player->setCondition($playerDocument['condition']);
            $player->setPosition($playerDocument['position']);
            $player->setColor($playerDocument['color']);
            $player->setWinner($playerDocument['is_winner']);

            $game->addPlayer($player);
        }

        foreach ($document['rounds'] as $roundDocument) {
            $round = new Round();
            $round->setNumber($roundDocument['number']);

            foreach ($roundDocument['actions'] as $actionDocument) {
                $action = new Action();

                if (isset($actionDocument['player_id'])) {
                    $action->setPlayerId((string)$actionDocument['player_id']);
                }

                if (isset($actionDocument['target_id'])) {
                    $action->setTargetId((string)$actionDocument['target_id']);
                }

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
