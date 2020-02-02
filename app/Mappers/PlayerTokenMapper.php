<?php

declare(strict_types=1);

namespace App\Mappers;

use App\Models\PlayerToken;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use MongoDB\Model\BSONDocument;

/**
 * Class PlayerTokenMapper
 */
class PlayerTokenMapper
{
    /**
     * @var Client
     */
    private Client $client;

    /**
     * PlayerTokenMapper constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param PlayerToken $playerToken
     *
     * @return bool
     */
    public function create(PlayerToken $playerToken): bool
    {
        $result = $this->client->battle->player_tokens->insertOne(
            $this->convert($playerToken)
        );

        $playerToken->setPlayerTokenId((string)$result->getInsertedId());

        return $playerToken->getPlayerTokenId() !== null;
    }

    /**
     * @param PlayerToken $playerToken
     *
     * @return bool
     */
    public function update(PlayerToken $playerToken): bool
    {
        $result = $this->client->battle->player_tokens->updateOne(
            ['_id' => new ObjectId($playerToken->getPlayerTokenId())],
            ['$set' => $this->convert($playerToken)]
        );

        return $result->getMatchedCount() != 0;
    }

    /**
     * @param PlayerToken $playerToken
     *
     * @return array
     */
    private function convert(PlayerToken $playerToken): array
    {
        return [
            'player_id' => new ObjectId($playerToken->getPlayerId()),
            'token' => $playerToken->getToken(),
            'date_generated' => new UTCDateTime(
                null
            ),
            'date_expired' => new UTCDateTime(
                strtotime($playerToken->getDateExpired())
            ),
        ];
    }

    /**
     * @param string $token
     *
     * @return PlayerToken|null
     */
    public function findByToken(string $token): ?PlayerToken
    {
        /** @var BSONDocument|null $result */
        $result = $this->client->battle->player_tokens->findOne(
            [
                'token' => $token,
                'date_expired' => [
                    '$lte' => new UTCDateTime()
                ],
            ]
        );

        if (!$result) {
            return null;
        }

        $playerToken = new PlayerToken();
        $this->assign($result, $playerToken);

        return $playerToken;
    }

    /**
     * @param BSONDocument $document
     * @param PlayerToken $playerToken
     */
    private function assign(
        BSONDocument $document,
        PlayerToken $playerToken
    ): void {
        $playerToken->setPlayerTokenId((string)$document['_id']);
        $playerToken->setPlayerId((string)$document['player_id']);
        $playerToken->setToken((string)$document['token']);
    }
}
